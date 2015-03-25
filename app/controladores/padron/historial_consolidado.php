<?php
session_start();
$nivel = 2;
require '../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';

function devolver_json ($resource) {
	
	for ($j = 0 ; $j < pg_num_fields ($resource) ; $j ++) {
		$data['aoColumns'][$j]['sTitle'] = ucwords (pg_field_name($resource , $j));
		$data['aoColumns'][$j]['mData'] = ucwords (pg_field_name($resource , $j));
		$data['aoColumns'][$j]['sClass'] = 'centrar_texto';
	}
	
	if (pg_num_rows ($resource)) {
		$i = 0;

		while ($registro = pg_fetch_assoc ($resource)) {
			for ($j = 0 ; $j < pg_num_fields ($resource) ; $j ++) {
				$data['aaData'][$i][ucwords (pg_field_name($resource , $j))] = $registro[pg_field_name($resource , $j)];
			}
			$i++;
		}
		
		$data['iTotalRecords'] = pg_num_rows ($resource);
		$data['iTotalDisplayRecords'] = pg_num_rows ($resource);
		$data['sEcho'] = 1;
		
	} else {
		$data['aaData'] = [] ;
		$data['iTotalRecords'] = 0;
		$data['iTotalDisplayRecords'] = 0;
	}
	return (json_encode ($data));
}

if (isset ($_GET['armar_tabla'])) {

	$periodo = date('Ym');
	$fuente =  $_GET['fuente_datos'];

	$sql = "select crosstab_fuente_datos('" . $periodo . "' , $fuente , 'registros_insertados')";
	$res = pg_query ($sql);
	$consulta = pg_fetch_row ($res , 0)[0];
	die (devolver_json (pg_query ($consulta)));

}
?>

<script>
$(document).ready(function(){
	
	$('#contenedor_historial_principal').hide();
	
	$('#fuente_datos').change(function(){
		mostrar_loading ();
		$('#contenedor_historial_principal').fadeOut();
		$.ajax({
			type : 'get' ,
			url  : 'modulos/padron/historial_consolidado.php' ,
			data : 'armar_tabla=1&fuente_datos=' + $('#fuente_datos').val() ,
			dataType : 'json' ,
			success : function (data) {
				console.log (data);
				ocultar_loading ();
				$('#contenedor_historial_principal').fadeIn();
				$('#tabla_historial_consolidado').dataTable({
					"sDom": 'T<"clear">frt<"bottom"l><"clear">' ,
					"bDestroy" : true ,
					"bSort" : true ,
					"iDisplayLength": 25 ,
					"bFilter" : true ,
					"bPaginate" : false ,
					"aaData" : data['aaData'] ,
					"aoColumns" : data['aoColumns'] ,
					"aoColumnDefs": [{
						"aTargets":["_all"],
						"fnCreatedCell": function(nTd, sData, oData, iRow, iCol){
							if(sData == 'x'){
								$(nTd).css('background-color', '#5CD65C');
							} else if (sData == '0'){
								$(nTd).css('background-color', '#ffb848');	
							} else if (sData == '' ){
								$(nTd).css('background-color', '#e7191b');	
							}
						},                   
					},{ 
						"bVisible": false, 
						"aTargets": [1]
					}] ,
					"oTableTools": {
							"sSwfPath": "metronic/assets/plugins/data-tables/table_tools/media/swf/copy_csv_xls_pdf.swf" ,
							"aButtons": [{
								"sExtends": "csv",
								"sButtonText": "Guardar"}]
						} 
				});
			}
		});
	});
});
</script>

<div class="portlet box green listado-simple">
	<div class="portlet-title">
		<div class="caption"><i class="icon-reorder"></i>Seleccione fuente de datos</div>
	</div>
	<div class="portlet-body">
		<form style="text-align: center">
			Fuente de datos
			<select name="fuente_datos" id="fuente_datos">
				<option value="0">Seleccione ...</option>
				<option value="1">Prestaciones</option>
				<option value="3">Comprobantes</option>
				<option value="2">Aplicaci&oacute;n de fondos</option>
			</select>
		</form>
		<div class="error" style="display: none;"></div>
	</div>
</div>

<div class="portlet box green listado-simple" id="contenedor_historial_principal">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-reorder"></i>Historial consolidado de impresiones de DDJJ
		</div>
	</div>
	<div class="portlet-body">
		<div id="contenedor_tabla_historial_consolidado">
			<table id="tabla_historial_consolidado" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"></table>
		</div>
	</div>
</div>

<style>
	.centrar_texto {text-align:center !important;}
</style>


