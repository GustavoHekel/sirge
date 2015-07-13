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
		$sql = "select ".key($_POST['val'])." from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."'";
		$res = pg_query($sql);
		$valor_previo = pg_fetch_row($res, 0);

		/**
		Actualizar tabla
		 **/
		$sql =
		"update efectores.efectores
			set ".key($_POST['val'])." = '".sanear($_POST['val'][$campos[$i]])."'
			where ".$campo_efector." = '".$_POST['efector']."'";
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
					, 'Tabla Efectores - Campo ".key($_POST['val']).": ".$valor_previo[0]." -> ".sanear($_POST['val'][$campos[$i]])."')";
			pg_query($sql);
		}
		next($_POST['val']);
		$i++;
	}
	die("Modificaci&oacute;n realizada");
}

function escribe_check($resource, $indice)
{
	if ($indice == 14 || $indice == 11)
	{
		if ($_SESSION['grupo'] == 25)
		{
			$check = '<input type="checkbox" name="flag['.pg_field_name($resource, $indice).']" />';
		}
		else
		{
			$check = '<input disabled="disabled" type="checkbox" name="flag['.pg_field_name($resource, $indice).']" />';
		}
	}
	else
	{
		$check = '<input type="checkbox" name="flag['.pg_field_name($resource, $indice).']" />';
	}
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
	$input = '<input style="span6" id="mod_'.pg_field_name($resource, $indice).'" type="text" name="val['.pg_field_name($resource, $indice).']" disabled="disabled" value="'.$valores[$indice].'" />';
	return $input;
}

function genera_opciones_sql($resource_ori, $sql, $valores, $indice)
{
	$resource = pg_query($sql);
	$opcion   = '';
	while ($registro = pg_fetch_array($resource))
	{
		$opcion .= '<option value="'.$registro[0].'"';
		$opcion .= $registro[0] == $valores[$indice] ? 'selected="selected"' : '';
		$opcion .= '>'.$registro['descripcion']."</option>";
	}
	return '<select style="span6" id="mod_'.pg_field_name($resource_ori, $indice).'" name="val['.pg_field_name($resource_ori, $indice).']" disabled="disabled">'.$opcion.'</select>';
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
	return '<select style="span6" id="mod_'.pg_field_name($resource_ori, $indice).'" name="val['.pg_field_name($resource_ori, $indice).']" disabled="disabled">'.$opcion.'</select>';
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
	cuie
	, siisa
	, nombre
	, domicilio
	, codigo_postal
	, id_tipo_efector
	, rural
	, cics
	, id_categorizacion
	, id_dependencia_administrativa
	, dependencia_sanitaria
	, integrante
	, compromiso_gestion
	, priorizado
	, ppac
	, codigo_provincial_efector
	--, sumar
from
	efectores.efectores
where '.$campo.' = \''.$_GET['efector'].'\' and id_estado = 1';
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
$("#mod_cuie").parents(".control-group").find("input:checkbox").attr("disabled","disabled");
$('input:checkbox').uniform();

$("input:checkbox").click(function(){
	if ($(this).is(':checked')) {
		$(this).parents(".control-group").find("input , select").removeAttr("disabled");
	} else {
		$(this).parents(".control-group").find("input:text , select").attr("disabled","disabled");
	}
});

$("#mod_generales").submit(function(event){
	event.preventDefault();

	if ($('#efector').val().length == 0) {
		$('#mod_mensajes').html('Por favor, verifique que el CUIE o SIISA estén ingresados');
		$("#mod_mensajes").dialog({
			title : 'Atención' ,
			buttons : [{
				text : 'Aceptar' ,
				class : 'btn green' ,
				click : function () { $(this).dialog("close"); }
			}]
		});
	} else {
		if ($(this).serialize().length > 0 ) {
			$.ajax({
				type : 'post' ,
				data : $(this).serialize() + '&efector=' + $("#efector").val() ,
				url  : 'modulos/efectores/abm/modificacion/generales.php' ,
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
	}
});

$("#mod_siisa").blur(function(){
	if ($(this).val().length != 14) {
		$(this).parent().append("<span>Verifique que el código SIISA sea de 14 dígitos</span>");
	}
}).focus(function(){
	$(this).parent().find("span").html("");
});

$("#mod_nombre , #mod_domicilio").blur(function(){
	if ($(this).val().length < 5) {
		$(this).parent().append("<span>Ingrese un nombre válido</span>");
	}
}).focus(function(){
	$(this).parent().find("span").html("");
});

$("#mod_id_tipo_efector , #mod_id_categorizacion , #mod_id_dependencia_administrativa").blur(function(){
	if ($(this).val() == 0) {
		$(this).parent().append("<span>Seleccione un valor de la lista</span>");
	}
}).focus(function(){$(this).parent().find("span").html("");});



</script>

<div class="portlet form-up-data">
	<div class="portlet-title">
		<div class="caption"><i class="icon-reorder"></i>Modificaci&oacute;n de informaci&oacute;n general</div>
	</div>
	<div class="portlet-body form">
		<form id="mod_generales" class="form-horizontal form-row-seperated">
				<?php
for ($i = 0; $i < pg_num_fields($res); $i++)
{
	echo
	'<div class="control-group">
							<label class="control-label" style="width: 300px">', escribe_campo($res, $i), escribe_check($res, $i), '</label>
							<div class="controls">';
	switch ($i)
	{
		case 5:echo genera_opciones_sql($res, "select * from efectores.tipo_efector", $valores, $i);
			break;
		case 8:echo genera_opciones_sql($res, "select * from efectores.tipo_categorizacion", $valores, $i);
			break;
		case 9:echo genera_opciones_sql($res, "select * from efectores.tipo_dependencia_administrativa", $valores, $i);
			break;
		case 6:echo genera_opciones_sn($res, $valores, 6);
			break;
		case 7:echo genera_opciones_sn($res, $valores, 7);
			break;
		case 11:echo genera_opciones_sn($res, $valores, 11);
			break;
		case 12:echo genera_opciones_sn($res, $valores, 12);
			break;
		case 13:echo genera_opciones_sn($res, $valores, 13);
			break;
		case 14:echo genera_opciones_sn($res, $valores, 14);
			break;
		//case 15 : echo genera_opciones_sn ($res , $valores , 15); break;
		default:echo escribe_input($res, $valores, $i);
	}
	echo '</div></div>';
}
?>
			<!--</table>-->
			<div class="form-actions">
				<button type="submit" class="btn green"><i class="icon-ok"></i>Modificar</button>
			</div>
		</form>
	</div>
</div>
<div id="mod_mensajes"></div>
