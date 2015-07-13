<?php

require_once '../../../../../init.php';

$servidor = '192.6.0.66';
$base = "sirge2";
$user = "postgres";
$password = '110678';

if ( ! $conn = pg_connect("host=$servidor dbname=$base user=$user password=$password"))
{
	die("Error en la conexión a la base de datos");
}

function sanear($string)
{
	return htmlentities(pg_escape_string($string), ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['flag']))
{

	$campo_efector = strlen($_POST['efector']) == 6 ? 'cuie' : 'siisa';
	$campos = array_keys($_POST['flag']);

	$i = 0;
	while ($campo = current($_POST['val']))
	{
		/**
		Traer valor anterior del campo
		 **/
		$sql = "select ".key($_POST['val'])." from efectores.datos_geograficos where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
		$res = pg_query($sql);
		$valor_previo = pg_fetch_row($res, 0);

		/**
		Actualizar tabla
		 **/
		$sql =
		"update efectores.datos_geograficos
			set ".key($_POST['val'])." = '".sanear($_POST['val'][$campos[$i]])."'
			where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
		$res = pg_query($sql);

		if ($res)
		{
			/**
			Insertar en log la modificación
			 **/
			$sql = "
				insert into efectores.operaciones
				values (
					 (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
					, $_SESSION[id_usuario]
					, localtimestamp
					, 6
					, 'Tabla Datos Geograficos - Campo ".key($_POST['val']).": ".$valor_previo[0]." -> ".sanear($_POST['val'][$campos[$i]])."')";
			pg_query($sql);
		}
		next($_POST['val']);
		$i++;
	}
	die("Modificaci&oacute;n realizada");
}
else if (isset($_POST['acc']))
{

	switch ($_POST['acc'])
	{
		case 'departamento':
			$sql = "select * from efectores.localidades where id_provincia = '$_POST[provincia]' and id_departamento = '$_POST[departamento]'";
			$res = pg_query($sql);

			$i = 0;
			while ($reg = pg_fetch_assoc($res))
			{
				$loca[$i]['id_localidad'] = $reg['id_localidad'];
				$loca[$i]['nombre'] = html_entity_decode($reg['nombre_localidad']);
				$i++;
			}
			die(json_encode($loca));
			break;
	}
}

function escribe_check($resource, $indice)
{
	$check = '<input id="check_'.pg_field_name($resource, $indice).'" type="checkbox" name="flag['.pg_field_name($resource, $indice).']" />';
	return $check;
}

function escribe_campo($resource, $indice)
{
	$busqueda = array(
		'id_'
		, '_',
	);

	return ucwords(str_replace($busqueda, ' ', pg_field_name($resource, $indice)));
}

function escribe_input($resource, $valores, $indice)
{
	$input = '<input id="mod_'.pg_field_name($resource, $indice).'" type="text" name="val['.pg_field_name($resource, $indice).']" disabled="disabled" value="'.$valores[$indice].'" />';
	return $input;
}

function genera_opciones_sql($resource_ori, $sql, $valores, $indice, $nombre_campo)
{
	$resource = pg_query($sql);
	$opcion   = '';
	while ($registro = pg_fetch_array($resource))
	{
		$opcion .= '<option value="'.$registro[0].'"';
		$opcion .= $registro[0] == $valores[$indice] ? 'selected="selected"' : '';
		$opcion .= '>'.$registro[$nombre_campo]."</option>";
	}
	return '<select id="mod_'.pg_field_name($resource_ori, $indice).'" name="val['.pg_field_name($resource_ori, $indice).']" disabled="disabled">'.$opcion.'</select>';
}

function genera_opciones_sn($resource_ori, $valores, $indice)
{
	$sn = array(
		0 => array('N', 'No'),
		1 => array('S', 'Si'),
	);

	$opcion = '';
	for ($i = 0; $i < count($sn); $i++)
	{
		$opcion .= '<option value="'.$sn[$i][0].'"';
		$opcion .= $sn[$i][0] == $valores[$indice] ? 'selected="selected"' : '';
		$opcion .= '>'.$sn[$i][1].'</option>';
	}
	return '<select id="mod_'.pg_field_name($resource_ori, $indice).'" name="val['.pg_field_name($resource_ori, $indice).']" disabled="disabled">'.$opcion.'</select>';
}

switch (strlen($_GET['efector']))
{
	case 6:$campo = 'cuie';
		break;
	case 14:$campo = 'siisa';
		break;
}

$sql = '
select
	id_provincia
	, id_departamento
	, id_localidad
	, ciudad
from
	efectores.datos_geograficos
where id_efector = (select id_efector from efectores.efectores where '.$campo.' = \''.$_GET['efector'].'\' and id_estado = 1)';
$res = pg_query($sql);

if (pg_num_rows($res) == 0)
{
	die("Efector no encontrado");
}
else
{
	$valores = pg_fetch_row($res, 0);
}
?>
<script>
$("#mod_id_provincia").parents(".control-group").find("input:checkbox").attr("disabled","disabled");
$('input:checkbox').uniform();

$("input:checkbox").click(function(){
	if ($(this).is(':checked')) {
		$(this).parents(".control-group").find("input , select").removeAttr("disabled");
	} else {
		$(this).parents(".control-group").find("input:text , select").attr("disabled","disabled");
	}
});

$("#mod_id_departamento").change(function(){
	var provincia = $("#mod_id_provincia").val();
	var departamento = $(this).val();
	$("#mod_id_localidad").html('');

	$.ajax({
		type : 'post' ,
		url  : 'app/controladores/efectores/abm/modificacion/datos_geograficos.php' ,
		data : 'acc=departamento&provincia=' + provincia + '&departamento=' + departamento ,
		success : function (data) {
			data = JSON.parse (data);
			console.log (data);

			$("#mod_id_localidad").append($("<option>" , {
				value : '0' ,
				text  : 'Seleccione localidad'
			}));

			for ( i = 0 ; i < (data.length) ; i ++ ) {
				$("#mod_id_localidad").append($("<option>",{
					value : data[i]['id_localidad'] ,
					text  : data[i]['nombre']
				}));
			}
		}
	});
});

$("#mod_geograficos").submit(function(event){
	event.preventDefault();
	if ($(this).serialize().length > 0 ) {
		$.ajax({
			type : 'post' ,
			data : $(this).serialize() + '&efector=' + $("#efector").val() ,
			url  : 'app/controladores/efectores/abm/modificacion/datos_geograficos.php' ,
			success : function (data) {
				$("#mod_mensajes").html(data);
				$("#mod_mensajes").dialog({
					title : 'Modificación' ,
					buttons : [{
						text : 'Aceptar' ,
						class : 'btn green' ,
						click : function () { $(this).dialog("close"); }
					}]
				});
			}
		});
	}
});

</script>

<div class="portlet form-up-data">
	<div class="portlet-title">
		<div class="caption"><i class="icon-reorder"></i>Modificaci&oacute;n de datos geogr&aacute;ficos</div>
	</div>
	<div class="portlet-body form">
		<form id="mod_geograficos" class="form-horizontal form-row-seperated">
			<!--<table class="modificacion-efectores">-->
				<?php
$campo_efector = strlen($_GET['efector']) == 6 ? 'cuie' : 'siisa';
for ($i = 0; $i < pg_num_fields($res); $i++)
{
	echo
	'<div class="control-group">
							<label class="control-label" style="width: 300px">', escribe_campo($res, $i), escribe_check($res, $i), '</label>
							<div class="controls">';
	switch ($i)
	{
		case 0:echo genera_opciones_sql($res, "select * from sistema.entidades", $valores, $i, 'descripcion');
			break;
		case 1:echo genera_opciones_sql(
				$res
				, "select id_departamento , nombre_departamento
								from geo.departamentos
								where id_provincia = (
									select id_provincia
									from efectores.datos_geograficos
									where id_efector = (
										select id_efector
										from efectores.efectores
										where $campo_efector = '$_GET[efector]'))"
				, $valores
				, $i
				, 'nombre_departamento');
			break;
		case 2:echo genera_opciones_sql(
				$res
				, "select id_localidad , nombre_localidad
							from
								geo.localidades
							where
								id_provincia = (
									select id_provincia
									from efectores.datos_geograficos
									where id_efector = (
										select id_efector
										from efectores.efectores
										where cuie = '$_GET[efector]'))
								and id_departamento = (
									select id_departamento
									from efectores.datos_geograficos
									where id_efector = (
										select id_efector
										from efectores.efectores
										where cuie = '$_GET[efector]'))"
				, $valores
				, $i
				, 'nombre_localidad');
			break;

		default:echo escribe_input($res, $valores, $i);
	}
	echo '</div></div>';
}
?>
			<div class="form-actions">
				<button type="submit" class="btn green"><i class="icon-ok"></i>Modificar</button>
			</div>
		</form>
	</div>
</div>
<div id="mod_mensajes"></div>
