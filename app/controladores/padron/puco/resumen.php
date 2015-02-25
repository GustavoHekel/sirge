<?php
session_start();
$nivel = 3;
require '../../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';

if (isset ($_GET['armar_tabla'])) {
	$sql = "
	select
		o.*
		, a.*
		, a.registros_insertados as registros_actuales
		, b.registros_insertados 
		, round (((a.registros_insertados :: numeric / b.registros_insertados) -1 ) * 100 , 2) :: text || '%' as variacion
	from 
		puco.grupos_obras_sociales o left join (
			select * 
			from sistema.procesos_obras_sociales
			where periodo = (extract (year from localtimestamp) :: text || lpad ((extract (month from localtimestamp) :: text) , 2 , '0')) :: int --". date ('Ym') ." 
		) a on o.grupo_os = a.codigo_os left join (
			select * 
			from sistema.procesos_obras_sociales
			where periodo = (extract (year from (localtimestamp - interval '1 month')) :: text || lpad (extract (month from (localtimestamp - interval '1 month')) :: text , 2 , '0')) :: int
		) b on a.codigo_os = b.codigo_os";
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
	
	$("#generar-puco").click(function(){
		mostrar_loading ();
		$.ajax({
			url : 'funciones/generar_puco.php' ,
			success : function (data) {
				ocultar_loading ();
				
				$("#dialog-respuesta").html(data);
				$("#dialog-respuesta").dialog({
					title : "PUCO listo!" ,
					buttons : [
					{
						text : 'Ok' ,
						class : 'btn green' ,
						click : function() {
							$(this).dialog("close");
							armar_tabla ();
						}
					}]
				});
				
			}
		});
	});
	
	function armar_tabla () {
		$.ajax({
			type : 'get' ,
			data : 'armar_tabla=1' ,
			url  : 'modulos/padron/tab-puco/resumen_puco.php' ,
			success : function (data){
				$("#tabla_resumen_datos").empty();
				$('#tabla_resumen_datos').dataTable({
					"sDom": 'T<"clear">frt<"bottom"lp><"clear">' ,
					"bDestroy" : true ,
					"iDisplayLength": 30 ,
					"bFilter" : true ,
					"aaData": data ,
					"aoColumns": [
						{ "sTitle": "Nombre entidad / OSP" , "mData": "nombre_grupo" } ,
						{ "sTitle": "Código OSP" , "mData": "grupo_os" } ,
						{ "sTitle": "Per&iacute;odo (PUCO)" , "mData": "periodo" } ,
						{ "sTitle": "Aceptados" , "mData": "registros_actuales" } ,
						{ "sTitle": "Rechazados" , "mData": "registros_rechazados" } ,
						{ "sTitle": "Totales" , "mData": "registros_totales" } ,
						{ "sTitle": "Variaci&oacute;n" , "mData": "variacion" }
					] ,
					"oTableTools": {
						"sSwfPath": "metronic/assets/plugins/data-tables/table_tools/media/swf/copy_csv_xls_pdf.swf" ,
						"aButtons": [{
							"sExtends": "csv",
							"sButtonText": "Guardar"}]
					} ,
					"bSort": true
				});
			} ,	dataType : "json"
		});
	}
	
	armar_tabla ();
	estilo_dialog ('purple');



});
</script>

<div class="portlet box purple up-list-data">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-reorder"></i>Resumen de procesos - Obras Sociales Provinciales
		</div>
	</div>
	<div class="portlet-body">
		<div class="container" style="width: 850px;">
			<table id="tabla_resumen_datos" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"></table>
		</div>
	</div>
</div>
<?php
if ($_SESSION['grupo'] == 25) {
	echo '<div id="generar-puco" class="boton-pie-tabla"><button type="button" class="btn green">Generar P.U.C.O.</button></div>';
}
?>
<div id="dialog-respuesta"></div>
<div id="dialog-respuesta-ajax"></div>
