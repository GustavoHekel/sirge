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
		$sql = "select ".key($_POST['val'])." from efectores.referentes where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
		$res = pg_query($sql);
		$valor_previo = pg_fetch_row($res, 0);

		/**
		Actualizar tabla
		 **/
		$sql =
		"update efectores.referentes
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
					, 'Tabla Referentes - Campo ".key($_POST['val']).": ".$valor_previo[0]." -> ".sanear($_POST['val'][$campos[$i]])."')";
			pg_query($sql);
		}
		next($_POST['val']);
		$i++;
	}
	die("Modificaci&oacute;n realizada");
}
else if (isset($_POST['nuevo_referente']))
{
	$campo_efector = strlen($_POST['efector']) == 6 ? 'cuie' : 'siisa';

	$sql = "
		insert into efectores.referentes (id_efector , nombre)
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, '".sanear($_POST['nuevo_referente'])."');
		insert into efectores.operaciones
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, $_SESSION[id_usuario]
			, localtimestamp
			, 6
			, 'Tabla Referentes - Nuevo valor -> ".sanear($_POST['nuevo_referente'])."')";
	$res = pg_query($sql);
	if ($res)
	{
		die('Nuevo referente agregado');
	}
	else
	{
		die('Ha ocurrido un error');
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
	nombre
from
	efectores.referentes
where id_efector = (select id_efector from efectores.efectores where '.$campo.' = \''.$_GET['efector'].'\' and id_estado = 1)';
$res = pg_query($sql);

if (pg_num_rows($res) == 0)
{
	$valores = 0;
}
else
{
	$valores = pg_fetch_row($res, 0);
}
?>
<script>
$('input:checkbox').uniform();
$("input:checkbox").click(function(){
	if ($(this).is(':checked')) {
		$(this).parents(".control-group").find("input , select").removeAttr("disabled");
	} else {
		$(this).parents(".control-group").find("input:text , select").attr("disabled","disabled");
	}
});

$("#mod_referentes").submit(function(event){
	event.preventDefault();
	if ($(this).serialize().length > 0 ) {
		$.ajax({
			type : 'post' ,
			data : $(this).serialize() + '&efector=' + $("#efector").val() ,
			url  : 'app/controladores/efectores/abm/modificacion/referentes.php' ,
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

$('#nuevo_referente_form').validate({
	rules : {
		nuevo_referente : {
			required : true ,
			minlength : 8
		}
	} ,
	messages : {
		nuevo_referente : {
			required : 'Ingrese un referente' ,
			minlength : 'Referente inv&aacute;lido'
		}
	} ,
	submitHandler : function (form) {
		$('#nuevo_referente').dialog('destroy');
		$.ajax ({
			type : 'post' ,
			data : $(form).serialize() + '&efector=' + $('#efector').val() ,
			url  : 'app/controladores/efectores/abm/modificacion/referentes.php' ,
			success : function (data) {
				$('#nuevo_referente_form')[0].reset();
				$('#mod_mensajes').html(data).dialog({
					title : 'Atención!' ,
					width : 300 ,
					buttons : [{
						text : 'Ok' ,
						class : 'btn green' ,
						click : function () {
							$('#ref').load('app/controladores/efectores/abm/modificacion/referentes.php?efector=' + efector);
							$(this).dialog('destroy');
						}
					}]
				})
			}
		});
	} ,
	invalidHandler : function (event , validator) {
		console.log (validator);
	}
});

$('.nuevo_referente').click(function(event){
	event.preventDefault();
	$('#nuevo_referente').dialog({
		title : 'Nuevo referente' ,
		width : 420 ,
		buttons : [{
			text : 'Aceptar' ,
			class : 'btn green' ,
			click : function () {
				$('#nuevo_referente_form').submit();
			}
		} , {
			text : 'Cancelar' ,
			class : 'btn red' ,
			click : function () {
				$(this).dialog('destroy');
			}
		}]
	});
});
</script>

<div class="portlet form-up-data">
	<div class="portlet-title">
		<div class="caption"><i class="icon-reorder"></i>Modificaci&oacute;n referente</div>
	</div>
	<?php
if ($valores)
{
	?>
	<div class="portlet-body form">
		<form id="mod_referentes" class="form-horizontal form-row-seperated">
				<?php
if ($valores)
	{
		for ($i = 0; $i < pg_num_fields($res); $i++)
		{
			echo
			'<div class="control-group">
								<label class="control-label" style="width: 300px">', escribe_campo($res, $i), escribe_check($res, $i), '</label>
								<div class="controls">';
			switch ($i)
			{
				default:echo escribe_input($res, $valores, $i);
			}
			echo '</div></div>';
		}
	}
	?>
			<div class="form-actions">
				<button type="submit" class="btn green"><i class="icon-ok"></i>Modificar</button>
			</div>
		</form>
	</div>
	<?php
}
else
{
	echo '<a href="#" class="btn green nuevo_referente"><i class="icon-plus"></i> Agregar referente</a>';
}
?>
</div>
<div style="display:none">
	<div id="mod_mensajes"></div>
	<div id="nuevo_referente">
		<form id="nuevo_referente_form">
			<table class="nuevo-dato">
				<tr>
					<td>Nombre</td>
					<td><input type="text" name="nuevo_referente" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
