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
		$sql = "select ".key($_POST['val'])." from efectores.compromiso_gestion where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
		$res = pg_query($sql);
		$valor_previo = pg_fetch_row($res, 0);

		/**
		Actualizar tabla
		 **/
		$sql =
		"update efectores.compromiso_gestion
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
					, 'Tabla Compromiso Gestion - Campo ".key($_POST['val']).": ".$valor_previo[0]." -> ".sanear($_POST['val'][$campos[$i]])."')";
			pg_query($sql);
		}
		next($_POST['val']);
		$i++;
	}
	die("Modificaci&oacute;n realizada");
}
else if (isset($_POST['nuevo_compromiso']))
{
	$campo_efector = strlen($_POST['efector']) == 6 ? 'cuie' : 'siisa';

	$sql = "
		insert into efectores.compromiso_gestion
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, '".sanear($_POST['nuevo_compromiso'])."'
			, '".sanear($_POST['nuevo_compromiso_firmante'])."'
			, '".sanear($_POST['nuevo_compromiso_sus'])."'
			, '".sanear($_POST['nuevo_compromiso_ini'])."'
			, '".sanear($_POST['nuevo_compromiso_fin'])."');
		insert into efectores.operaciones
		values (
			(select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')
			, $_SESSION[id_usuario]
			, localtimestamp
			, 6
			, 'Tabla Compromiso gestion - Nuevo valor -> ".sanear($_POST['nuevo_compromiso'])."')";
	$res = pg_query($sql);
	if ($res)
	{
		die('Nuevo convenio administrativo agregado');
	}
	else
	{
		die('Ha ocurrido un error');
	}
}
else if (isset($_POST['ver_compromiso']))
{
	$campo_efector = strlen($_POST['efector']) == 6 ? 'cuie' : 'siisa';
	$sql = "
		select case when integrante = 'N' or compromiso_gestion = 'N' then 'N' end
		from efectores.efectores
		where id_efector = (select id_efector from efectores.efectores where ".$campo_efector." = '".$_POST['efector']."')";
	$res = pg_query($sql);
	die(pg_fetch_row($res)[0]);
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
	numero_compromiso
	, firmante
	, fecha_suscripcion
	, fecha_inicio
	, fecha_fin
	, pago_indirecto
from
	efectores.compromiso_gestion
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

$(".fecha_iso , #mod_fecha_suscripcion , #mod_fecha_inicio , #mod_fecha_fin").datepicker();

$("#mod_compromiso_gestion_form").submit(function(event){
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
				url  : 'app/controladores/efectores/abm/modificacion/compromiso_gestion.php' ,
				success : function (data) {
					$("#mod_mensajes").html(data);
					$("#mod_mensajes").dialog({
						title : 'Modificación' ,
						buttons : [{
							text : 'Aceptar' ,
							class : 'btn green' ,
							click : function () {
								$(this).dialog("close");
							}
						}]
					});
				}
			});
		}
	}
});

$('.nuevo_compromiso').click(function(event){
	event.preventDefault();
	$.ajax({
		type : 'post' ,
		url  : 'app/controladores/efectores/abm/modificacion/compromiso_gestion.php' ,
		data : 'ver_compromiso=1&efector=' + efector ,
		success : function (data) {
			console.log (data);
			if (data == 'N') {
				$('#mod_mensajes').html('Este efector no es integrante o no posee compromiso de gesti&oacute;n').dialog({
					title : 'Atencion!' ,
					buttons : [{
						text : 'Aceptar' ,
						class : 'btn green' ,
						click : function () {
							$(this).dialog('destroy');
						}
					}]
				})
			} else {
				$('#nuevo_compromiso').dialog({
					title : 'Nuevo compromiso de gestión' ,
					width : 420 ,
					buttons : [{
						text : 'Aceptar' ,
						class : 'btn green' ,
						click : function () {
							$('#nuevo_compromiso_form').submit();
						}
					} , {
						text : 'Cancelar' ,
						class : 'btn red' ,
						click : function () {
							$(this).dialog('destroy');
						}
					}]
				});
			}
		}
	});
});

$('#nuevo_compromiso_form').validate({
	rules : {
		nuevo_compromiso : {
			required : true ,
			minlength : 3
		} ,
		nuevo_compromiso_firmante : {
			required : true ,
			minlength : 8
		} ,
		nuevo_compromiso_sus : {
			required : true ,
			dateISO : true
		} ,
		nuevo_compromiso_ini : {
			required : true ,
			dateISO : true
		} ,
		nuevo_compromiso_fin : {
			required : true ,
			dateISO : true
		}
	} ,
	messages : {
		nuevo_compromiso : {
			required : 'Ingrese n&uacute;mero de convenio' ,
			minlength : 'N&uacute;mero de convenio inv&aacute;lido'
		} ,
		nuevo_compromiso_firmante : {
			required : 'Ingrese firmante de convenio' ,
			minlength : 'Firmante de convenio inv&aacute;lido'
		} ,
		nuevo_compromiso_sus : {
			required : 'Ingrese una fecha v&aacute;lida'
		} ,
		nuevo_compromiso_ini : {
			required : 'Ingrese una fecha v&aacute;lida'
		} ,
		nuevo_compromiso_fin : {
			required : 'Ingrese una fecha v&aacute;lida'
		}
	} ,
	submitHandler : function (form) {
		$('#nuevo_compromiso').dialog('destroy');
		$.ajax ({
			type : 'post' ,
			data : $(form).serialize() + '&efector=' + $('#efector').val() ,
			url  : 'app/controladores/efectores/abm/modificacion/compromiso_gestion.php' ,
			success : function (data) {
				$('#nuevo_compromiso_form')[0].reset();
				$('#mod_mensajes').html(data).dialog({
					title : 'Atención!' ,
					width : 300 ,
					buttons : [{
						text : 'Ok' ,
						class : 'btn green' ,
						click : function () {
							$('#com_ges').load('app/controladores/efectores/abm/modificacion/compromiso_gestion.php?efector=' + efector);
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

</script>

<div class="portlet form-up-data">
	<div class="portlet-title">
		<div class="caption"><i class="icon-reorder"></i>Modificaci&oacute;n compromiso de gesti&oacute;n</div>
	</div>
	<?php
if ($valores)
{
	?>
	<div class="portlet-body form">
		<form id="mod_compromiso_gestion_form" class="form-horizontal form-row-seperated">
				<?php
for ($i = 0; $i < pg_num_fields($res); $i++)
	{
		echo
		'<div class="control-group">
							<label class="control-label" style="width: 300px">', escribe_campo($res, $i), escribe_check($res, $i), '</label>
							<div class="controls">';
		switch ($i)
		{
			case 5:echo genera_opciones_sn($res, $valores, 5);
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
	echo '<a href="#" class="btn green nuevo_compromiso"><i class="icon-plus"></i> Agregar compromiso de gesti&oacute;n</a>';
}
?>
</div>
<div style="display:none">
	<div id="mod_mensajes"></div>
	<div id="nuevo_compromiso">
		<form id="nuevo_compromiso_form">
			<table class="nuevo-dato">
				<tr>
					<td>N&uacute;mero convenio</td>
					<td><input type="text" name="nuevo_compromiso" /></td>
				</tr>
				<tr>
					<td>Firmante</td>
					<td><input type="text" name="nuevo_compromiso_firmante" /></td>
				</tr>
				<tr>
					<td>Fecha suscripcion</td>
					<td><input class="fecha_iso" type="text" name="nuevo_compromiso_sus" /></td>
				</tr>
				<tr>
					<td>Fecha inicio</td>
					<td><input class="fecha_iso" type="text" name="nuevo_compromiso_ini" /></td>
				</tr>
				<tr>
					<td>Fecha fin</td>
					<td><input class="fecha_iso" type="text" name="nuevo_compromiso_fin" /></td>
				</tr>
				<tr>
					<td>Pago indirecto</td>
					<td>
						<select name="nuevo_compromiso_pago_indirecto" >
							<option value="N">No</option>
							<option value="S">Si</option>
						</select>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

