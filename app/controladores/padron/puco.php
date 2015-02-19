<?php
session_start();
$nivel = 2;
require '../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';
?>
<script>
$(document).ready(function(){
	$(function() {
		$("#tab-puco").tab();
		$("#tab-puco").bind("show", function(e) {    
			var contentID  = $(e.target).attr("data-target");
			var contentURL = $(e.target).attr("href");
			if (typeof(contentURL) != 'undefined')
				$(contentID).load(contentURL, function(){ $("#tab-puco").tab(); });
			else
				$(contentID).tab('show');
		});
		$('#tab-puco a:first').tab("show");
	});
});
</script>

<ul id="tab-puco" class="nav nav-tabs">
	<li><a data-target="#carga_archivos_osp" data-toggle="tab" href="modulos/padron/tab-puco/carga_archivo_osp.php">Carga archivos OSP</a></li>
	<?php
	if ($_SESSION['grupo'] == 25) {
		echo "<li><a data-target=\"#carga_archivos_sss\" data-toggle=\"tab\" href=\"modulos/padron/tab-puco/carga_archivo_sss.php\">Carga archivos SSS</a></li>";
	}
	?>
	<li><a data-target="#subidas" data-toggle="tab" href="modulos/padron/tab-puco/subidas.php">Archivos subidos</a></li>
	<li><a data-target="#resumen" data-toggle="tab" href="modulos/padron/tab-puco/resumen_puco.php">Resumen OSP procesadas</a></li>
	<!--<li><a data-target="#consultas" data-toggle="tab" href="modulos/padron/tab-puco/consultas.php">Consultas</a></li>-->
</ul>

<div class="tab-content tab-contenedor">
	<div class="tab-pane" id="carga_archivos_osp">Cargando...</div>
	<div class="tab-pane" id="carga_archivos_sss">Cargando...</div>
	<div class="tab-pane" id="subidas">Cargando...</div>
	<div class="tab-pane" id="resumen">Cargando...</div>
	<!--<div class="tab-pane" id="consultas">Cargando...</div>-->
</div>
