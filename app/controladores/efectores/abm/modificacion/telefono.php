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
		$sql = "select ".key($_POST['val'])." from efectores.telefonos where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
		$res = pg_query($sql);
		$valor_previo = pg_fetch_row($res, 0);

		/**
		Actualizar tabla
		 **/
		$sql =
		"update efectores.telefonos
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
					, 'Tabla Telefonos - Campo ".key($_POST['val']).": ".$valor_previo[0]." -> ".sanear($_POST['val'][$campos[$i]])."')";
			pg_query($sql);
		}
		next($_POST['val']);
		$i++;
	}
	die("Modificaci&oacute;n realizada");
}
else if (isset($_POST['nuevo_telefono']))
{
	$campo_efector = strlen($_POST['efector']) == 6 ? 'cuie' : 'siisa';

	$sql = "
		insert into efectores.telefonos (id_efector , numero_telefono , id_tipo_telefono , observaciones)
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, '".sanear($_POST['nuevo_telefono'])."'
			, ".sanear($_POST['nuevo_telefono_tipo'])."
			, '".sanear($_POST['nuevo_telefono_obs'])."');
		insert into efectores.operaciones
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, $_SESSION[id_usuario]
			, localtimestamp
			, 6
			, 'Tabla Telefonos - Nuevo valor -> ".sanear($_POST['nuevo_telefono'])."')";
	$res = pg_query($sql);
	if ($res)
	{
		die('Nuevo tel&eacute;fono agregado');
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
	numero_telefono
	, id_tipo_telefono
	, observaciones
from
	efectores.telefonos
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

$("#mod_telefono").submit(function(event){
	event.preventDefault();
	if ($(this).serialize().length > 0 ) {
		$.ajax({
			type : 'post' ,
			data : $(this).serialize() + '&efector=' + $("#efector").val() ,
			url  : 'app/controladores/efectores/abm/modificacion/telefono.php' ,
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

$('#nuevo_telefono_form').validate({
	rules : {
		nuevo_telefono : {
			required : true ,
			minlength : 5 ,
			digits : true
		}
	} ,
	messages : {
		nuevo_telefono : {
			required : 'Ingrese un tel&eacute;fono' ,
			minlength : 'N&uacute;mero no v&aacute;lido'
		}
	} ,
	submitHandler : function (form) {
		$('#nuevo_telefono').dialog('destroy');
		$.ajax ({
			type : 'post' ,
			data : $(form).serialize() + '&efector=' + $('#efector').val() ,
			url  : 'app/controladores/efectores/abm/modificacion/telefono.php' ,
			success : function (data) {
				$('#nuevo_telefono_form')[0].reset();
				$('#mod_mensajes').html(data).dialog({
					title : 'Atención!' ,
					width : 300 ,
					buttons : [{
						text : 'Ok' ,
						class : 'btn green' ,
						click : function () {
							$('#tel').load('app/controladores/efectores/abm/modificacion/telefono.php?efector=' + efector);
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

$('.nuevo_telefono').click(function(event){
	event.preventDefault();
	$('#nuevo_telefono').dialog({
		title : 'Nuevo email' ,
		width : 420 ,
		buttons : [{
			text : 'Aceptar' ,
			class : 'btn green' ,
			click : function () {
				$('#nuevo_telefono_form').submit();
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
		<div class="caption"><i class="icon-reorder"></i>Modificaci&oacute;n tel&eacute;fono</div>
	</div>
	<?php
if ($valores)
{
	?>
	<div class="portlet-body form">
		<form id="mod_telefono" class="form-horizontal form-row-seperated">
				<?php
for ($i = 0; $i < pg_num_fields($res); $i++)
	{
		echo
		'<div class="control-group">
							<label class="control-label" style="width: 300px">', escribe_campo($res, $i), escribe_check($res, $i), '</label>
							<div class="controls">';
		switch ($i)
		{
			case 1:echo genera_opciones_sql($res, "select id_tipo_telefono , descripcion from efectores.tipo_telefono", $valores, $i, 'descripcion');
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
	<?php
}
else
{
	echo '<a href="#" class="btn green nuevo_telefono"><i class="icon-plus"></i> Agregar tel&eacute;fono</a>';
}
?>
</div>
<div style="display:none">
	<div id="mod_mensajes"></div>
	<div id="nuevo_telefono">
		<form id="nuevo_telefono_form">
			<table class="nuevo-dato">
				<tr>
					<td>Tel&eacute;fono</td>
					<td><input type="text" name="nuevo_telefono" id="input_nuevo_telefono" /></td>
				</tr>
				<tr>
					<td>Tipo</td>
					<td>
						<select name="nuevo_telefono_tipo" >
						<?php
$sql = "select * from efectores.tipo_telefono order by id_tipo_telefono";
$res = pg_query($sql);
while ($reg = pg_fetch_assoc($res))
{
	echo '<option value="'.$reg['id_tipo_telefono'].'">'.$reg['descripcion'].'</option>';
}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Observaciones</td>
					<td><input type="text" name="nuevo_telefono_obs" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
