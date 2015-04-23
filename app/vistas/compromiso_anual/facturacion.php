<script>
$(document).ready(function(){
	mostrar_loading ();
	var provincia = $(this).val();

	$.ajax({
		type : 'post' ,
		data : 'id_provincia=' + provincia ,
		url  : 'modulos/compromiso_anual_2014/facturacion.php' ,
		dataType : 'json' ,
		success : function (data) {
			console.log (data);
			ocultar_loading ();
			$('#container').highcharts({
				chart: {
				},
				title: {
					text: 'Facturacion Decentralizada'
				},
				xAxis: {
					categories: data['nombre'] ,
					labels : {
						rotation : -45 ,
						align : 'right' ,
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				},
				yAxis: {
						title: {
							text: 'Porcentaje de facturación decentralizada'
						}
					},
				tooltip: {
					formatter: function() {
						var s;
							s = ''+ this.series.name  +': '+ this.y + '%';
						return s;
					}
				},
				series: [{
					type: 'column',
					name: 'Facturacion Decentralizada' ,
					data: data['fact_desc']
				}, {
					type: 'scatter',
					name: 'Meta 1º Semestre',
					data: data['primer_semestre'] ,
					marker: {
						lineWidth: 0,
						fillColor: 'orange',
						radius: 5
					}
				}, {
					type: 'scatter',
					name: 'Meta 2º Semestre',
					data: data['segundo_semestre'],
					marker: {
						lineWidth: 0,
						fillColor: 'red' ,
						radius: 5
					}
				}]
			});
		}
	});
});
</script>
<div id="container"></div>
