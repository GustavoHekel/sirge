<?php
session_start();
$nivel = 2;
require '../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';

if (isset ($_GET['grafica'])) {
	$sql = "
	select 	
		periodo
		, coalesce (centro , 0) as centro
		, coalesce (cuyo, 0) as cuyo
		, coalesce (nea, 0) as nea
		, coalesce (noa, 0) as noa
		, coalesce (patagonia, 0) as patagonia
	from (
		select *
		from crosstab (
			'select * 
			from (
				select
					anio :: text || lpad (mes :: text , 2 , ''0'') as periodo
					, region
					, coalesce (prestaciones , 0) as consultas
				from (
					select
						extract (year from fecha_impresion_ddjj) as anio
						, extract (month from fecha_impresion_ddjj) as mes
						, r.nombre as region
						, sum (registros_insertados) as prestaciones

					from (
						select * 
						from 
							sistema.lotes l left join
							sistema.impresiones_ddjj i on l.lote = i.lote
						where
							id_padron = " . $_GET['fuente'] . "
							and id_estado = 1 ) l left join
						sistema.provincias p on l.id_provincia = p.id_provincia left join
						sistema.tipo_region r on p.id_region = r.id_region
					group by 1,2,3
					order by 1,2 ) a 
				where anio :: text || lpad (mes :: text , 2 , ''0'') <= ''" . date('Ym') . "''
				order by 1 desc ) b
			order by 1',
			'select * from (
				select nombre from sistema.tipo_region ) a
			order by 1')
		as ct (periodo text , CENTRO integer , CUYO integer , NEA integer , NOA integer , PATAGONIA integer)
		order by 1 desc 
		limit 6 ) a
	order by 1";
	
	$res = pg_query ($sql);
	if (pg_num_rows ($res)) {
		$i = 0;
		while ($reg = pg_fetch_assoc ($res)) {
			for ($j = 0 ; $j < pg_num_fields ($res) ; $j ++) {
				$data[pg_field_name($res , $j)][$i] = html_entity_decode ($reg[pg_field_name($res , $j)]);
			}
			$i++;
		}
	}
	
	die (json_encode ($data , JSON_NUMERIC_CHECK));
	
}

?>
<script>
$(document).ready(function(){
	$('#fuente_datos').change(function(){
		mostrar_loading ();
		var fuente = $(this).val();
		
		$.ajax({
			type : 'get' ,
			data : 'grafica=1&fuente=' + fuente ,
			url  : 'modulos/padron/distribucion.php' ,
			dataType : 'json' ,
			success : function (data) {
				ocultar_loading ();
				console.log (data);
				$('#container').highcharts({
					chart : {
						type : 'area'
					} ,
					title : {
						text : 'Distribución de reporte de fuente de datos'
					} ,
					plotOptions : {
						area : {
							stacking : 'percent' ,
							lineColor : '#ffffff' ,
							lineWidth : 1
						}
					} ,
					 tooltip: {
						pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b><br/>',
						shared: true
					},
					xAxis : {
						categories : data['periodo'] ,
						title : {
							text : 'Período'
						}
					} ,
					yAxis : {
						title : {
							text : 'Reporte nivel país'
						}
					} ,
					series : [{
						name : 'CENTRO',
						data : data['centro']
					},{
						name : 'CUYO',
						data : data['cuyo']
					},{
						name : 'NEA',
						data : data['nea']
					},{
						name : 'NOA',
						data : data['noa']
					},{
						name : 'PATAGONIA',
						data : data['patagonia']
					}]
				});
			}
		});
	});
});
</script>


<div class="portlet box blue listado-simple">
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
<div id="container"></div>
