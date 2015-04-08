<?php
session_start();
$nivel = 3;
require '../../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';

if (isset ($_GET['armar_tabla'])) {
	$sql = "
	select
		nombre_grupo as nombre
		, id_entidad
		, grupo_os as codigo_os
		, id_archivo_sss
		, fecha_carga :: date as fecha_carga
		, nombre_original
		, nombre_actual
		, round ((size / 1024) :: numeric , 2) || ' MB' as tamanio
		, '<a file=\"' || nombre_actual || '\" href=\"#\" class=\"procesar\"><i class=\"halflings-icon hdd\"></i></a>' as procesar
		, '<a file=\"' || nombre_actual || '\" href=\"#\" class=\"eliminar\"><i class=\"halflings-icon trash\"></a></i>' as eliminar
	from
		puco.grupos_obras_sociales gru left join (
			select *
			from 
				sistema.cargas_archivos car left join
				sistema.cargas_archivos_osp osp on car.id_carga = osp.id_carga
			where
				procesado = 'N'
				and id_padron = 6
		) cao on gru.grupo_os = cao.codigo_os
	";
	$sql .= $_SESSION['grupo'] == 25 ?
		" order by id_entidad , id_archivo_sss" : 
		" where 
			id_entidad = '$_SESSION[grupo]' 
		order by id_entidad , id_archivo_sss";
	$res = pg_query ($sql);
	
	if (pg_num_rows ($res)) {
		$i = 0;
		while ($reg = pg_fetch_assoc ($res)) {
			for ($j = 0 ; $j < pg_num_fields ($res) ; $j ++) {
				$data[$i][pg_field_name($res , $j)] = $reg[pg_field_name($res , $j)];
			}
			$i++;
		}
		die (json_encode ($data));
	} else {
		$data['iTotalRecords'] = 0;
		$data['iTotalDisplayRecords'] = 0;
		die (json_encode ($data));
	}
}

?>
<script>
$(document).ready(function(){

	function armar_tabla () {
		$.ajax({
			type : 'get' ,
			data : 'armar_tabla=1' ,
			url  : 'modulos/padron/tab-puco/subidas.php' ,
			success : function (data){
				console.log(data);
				$("#tabla_archivos_subidos").empty();
				$('#tabla_archivos_subidos').dataTable({
					"sDom": 'frt<"bottom"lp><"clear">' ,
					"bDestroy" : true ,
					"iDisplayLength": 30 ,
					"aaData": data ,
					"aoColumns": [
						{ "sTitle": "Nombre entidad / OSP" , "mData": "nombre" } ,
						{ "sTitle": "Código OSP" , "mData": "codigo_os" } ,
						{ "sTitle": "Id archivo" , "mData": "id_archivo_sss" } ,
						{ "sTitle": "Fecha subida" , "mData": "fecha_carga" } ,
						{ "sTitle": "Tama&ntilde;o" , "mData": "tamanio" } ,
						{ "sTitle": "Procesar" , "mData": "procesar" } ,
						{ "sTitle": "Eliminar" , "mData": "eliminar" }
					] ,
					"bInfo" : true ,
					"bFilter" : true ,
					"bSort": true
				});
			} ,
			dataType : "json"
		});
	}
	
	armar_tabla ();
	estilo_dialog ('purple');
	
	
	$('#tabla_archivos_subidos').on('click' , '.procesar' , function(event){
		event.preventDefault();
		var file_ori = $(this).attr("file");
		var file = $(this).parents("tr").children().html();
		$("#dialog-respuesta").html("Est&aacute; por procesar el archivo <span style='font-weight: bold;'>" + file + "</span>, confirma la operaci&oacute;n ?");
		
		$("#dialog-respuesta").dialog({
			title : 'Procesar?' ,
			buttons: [
				{ 
					text	: "Aceptar" , 
					class 	: "btn green" , 
					click 	: function(){
						$(this).dialog('close');
						mostrar_loading ();
						$.ajax({
							type : 'post' ,
							data : 'archivo=' + file_ori ,
							url  : 'funciones/valida_osp.php' ,
							success : function(data) {
								ocultar_loading ();
								$("#dialog-respuesta").html(data);
								$("#dialog-respuesta").dialog({
									title		: "Archivo procesado!" ,
									buttons: [
										{
											text : "Aceptar" ,
											class : "btn green" , 
											click : function() {
												$(this).dialog("close");
												armar_tabla ();
											}
									}]
									
								});
							}
						});
					}
				} , 
				{ text: "Cancelar", class : "btn red" , click: function() { $(this).dialog("close"); }}]
			});
	});
		
	$('#tabla_archivos_subidos').on('click' , '.eliminar' , function(event){
		event.preventDefault();
		var file_ori = $(this).attr("file");
		var file = $(this).parents("tr").children().html();
		$("#dialog-respuesta").html("Est&aacute; por eliminar el archivo <span style='font-weight: bold;'>" + file + "</span>, confirma la operaci&oacute;n ?");
		$("#dialog-respuesta").dialog({
			title : 'Eliminar?' ,
			buttons: [{ 
				text: "Aceptar" , 
				class : "btn green", 
				click: function() {
					$(this).dialog("close");
					mostrar_loading () ;
					$.ajax({
						type : 'post' ,
						data : 'acc=del-archivo&padron=osp&file=' + file_ori ,
						url  : 'funciones/funciones_adm.php' ,
						success : function(data) {
							ocultar_loading () ;
							$("#dialog-respuesta-ajax").html(data);
							$("#dialog-respuesta-ajax").dialog({
								title		: 'Confirmación' ,
								buttons: [{
									text : 'Ok' ,
									class : 'btn green' ,
									click : function () {
										$(this).dialog('close');
										armar_tabla ();
									}
								}]
							});
						} ,
						error : function (data) {
							console.log (data);
						}
					});
				}
			} , 
				{ text: "Cancelar", class : "btn red" , click: function() { $(this).dialog("close"); }}] ,
		});
	});
	
});
</script>
<div class="portlet box purple up-list-data">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-reorder"></i>Listado de archivos - Obras Sociales Provinciales
		</div>
	</div>
	<div class="portlet-body">
		<div class="container" style="width: 850px;">
			<table id="tabla_archivos_subidos" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"></table>
		</div>
	</div>
</div>

<div id="dialog-respuesta"></div>
<div id="dialog-respuesta-ajax"></div>
