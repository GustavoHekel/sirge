<?php
require '../../../../sistema/conectar_postgresql.php';
if (isset ($_POST['id_provincia'])) {
	$sql = "
	select
		a.mes
		, b.\"2009\"
		, c.\"2010\"
		, d.\"2011\"
		, e.\"2012\"
		, f.\"2013\"
	from (
		select extract (month from fecha_prestacion) as mes
		from prestaciones.p_$_POST[id_provincia]
		group by extract (month from fecha_prestacion)
		order by extract (month from fecha_prestacion)
		) a left join (
			select
				extract (month from fecha_prestacion) as mes
				, count (*) as \"2009\"
			from prestaciones.p_$_POST[id_provincia]
			where fecha_prestacion between '2009-01-01' and '2009-12-31'
			group by extract (month from fecha_prestacion)
		) b on a.mes = b.mes left join (
			select
				extract (month from fecha_prestacion) as mes
				, count (*) as \"2010\"
			from prestaciones.p_$_POST[id_provincia]
			where fecha_prestacion between '2010-01-01' and '2010-12-31'
			group by extract (month from fecha_prestacion)

		) c on a.mes = c.mes left join (
			select
				extract (month from fecha_prestacion) as mes
				, count (*) as \"2011\"
			from prestaciones.p_$_POST[id_provincia]
			where fecha_prestacion between '2011-01-01' and '2011-12-31'
			group by extract (month from fecha_prestacion)
		) d on a.mes = d.mes left join (
			select
				extract (month from fecha_prestacion) as mes
				, count (*) as \"2012\"
			from prestaciones.p_$_POST[id_provincia]
			where fecha_prestacion between '2012-01-01' and '2012-12-31'
			group by extract (month from fecha_prestacion)
		) e on a.mes = e.mes left join (
			select
				extract (month from fecha_prestacion) as mes
				, count (*) as \"2013\"
			from prestaciones.p_$_POST[id_provincia]
			where fecha_prestacion between '2013-01-01' and '2013-12-31'
			group by extract (month from fecha_prestacion)
		) f on a.mes = f.mes";
	$res = pg_query ($sql);
	$i = 0;
	while ($reg = pg_fetch_assoc ($res)) {
		$data['mes'][$i] = $reg['mes'];
		$data['2009'][$i] = $reg['2009'];
		$data['2010'][$i] = $reg['2010'];
		$data['2011'][$i] = $reg['2011'];
		$data['2012'][$i] = $reg['2012'];
		$data['2013'][$i] = $reg['2013'];
		$i ++;
	}
	die (json_encode ($data , JSON_NUMERIC_CHECK));
}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
        
        $.ajax({
			type : 'post' ,
			data : 'id_provincia=03' ,
			url  : 'index.php' ,
			success : function (data) {
				console.log (data);
				prestaciones = JSON.parse (data);
				console.log (prestaciones['2009']);
				
				$('#container').highcharts({
					title: {
						text: 'Prestaciones brindadas mensualmente',
						x: -20 //center
					},
					subtitle: {
						text: 'Fuente: SIRGE',
						x: -20
					},
					xAxis: {
						categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
							'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
					},
					yAxis: {
						title: {
							text: 'Prestaciones brindadas'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						//valueSuffix: ' prestaciones'
						shared: true,
						crosshairs: true
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
					},
					series: [{
						name: '2009',
						data: prestaciones['2009']
					}, {
						name: '2010',
						data: prestaciones['2010']
					}, {
						name: '2011',
						data: prestaciones['2011']
					}, {
						name: '2012',
						data: prestaciones['2012']
					}, {
						name: '2013',
						data: prestaciones['2013']
					}]
				});
			}
		});
    });
</script>
	</head>
	<body>
<script src="../../js/highcharts.js"></script>
<script src="../../js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 50px auto;"></div>

	</body>
</html>
