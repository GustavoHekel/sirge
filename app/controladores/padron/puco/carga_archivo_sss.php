<?php
session_start();
$nivel = 3;
require '../../../seguridad.php';
require $ruta.'sistema/conectar_postgresql.php';

if (isset($_POST['busqueda_carga'])){
	$sql = "
		select 
		*
	from 
		sistema.cargas_archivos c
		left join sistema.cargas_archivos_osp o on c.id_carga = o.id_carga
	where 
		c.id_padron = 6
		and procesado = 'N'
		and o.codigo_os = '$_POST[entidad_puco]'
		and o.id_archivo_sss = '$_POST[archivo]'";
	$res = pg_query($sql);
	if (pg_num_rows($res)) { die ("1"); } else { die("0"); }
}

if (!empty ($_FILES['padron'])) {
	foreach ($_FILES['padron']['name'] as $orden => $nombre) {
		$grupo = strlen ($_SESSION['grupo']) == 2 ? $_SESSION['grupo'] : "0" . $_SESSION['grupo'];
		
		if ($_FILES['padron']['error'][$orden] == 0 && move_uploaded_file($_FILES['padron']['tmp_name'][$orden] , '../../../upload/osp/' . $_POST['entidad-sss'] . '_' . $_POST['archivo'] . '.txt')) {
			$subidas[] = $nombre;
			
			$sql = "
				insert into sistema.cargas_archivos (
					id_usuario_carga
					, id_padron
					, nombre_original
					, nombre_actual
					, size) 
				values (
					$_SESSION[id_usuario]
					, 6
					, '" . $nombre . "'
					, '" . $_POST['entidad-sss'] . "_" . $_POST['archivo'] . ".txt'
					, '" . round ($_FILES['padron']['size'][$orden] / 1024 , 2) . "');
				select currval ('sistema.cargas_sirge_id_carga_seq') limit 1";
			$res = pg_query ($sql);
			$id = pg_fetch_row($res);
			$sql = "
				insert into sistema.cargas_archivos_osp (id_carga , codigo_os , id_archivo_sss)
				values ($id[0] , " . $_POST['entidad-sss'] . " , '$_POST[archivo]')";
			$res = pg_query($sql);
			
			die (json_encode ($subidas));
		}
	}
}

$sql = 
	"select
	grupo_os as codigo_os
	, nombre_grupo as nombre_os
	, id_entidad
from 
	puco.grupos_obras_sociales 
where id_entidad = '26' 
order by id_entidad";
$res = pg_query ($sql);

?>
<script>
$(document).ready(function(){
	
	function busca_archivo_previo (entidad , archivo) {
		
		$.ajax({
			type : 'post' ,
			async : false ,
			url  : 'modulos/padron/tab-puco/carga_archivo_sss.php' ,
			data : 'busqueda_carga=1&entidad_puco=' + entidad + '&archivo=' + archivo ,
			success : function (data) {
				datos = data ;
			}
		});
		return datos ;
	}

	$(".error").hide();
	$("#submit_sss").click(function(event){
		event.preventDefault();
		event.stopPropagation();

		var entidad = $('#entidad-sss').val();
		var archivo = $('#id_archivo_sss').val();

		if (entidad == 0) {
			$("input:submit").prop('disabled', true);
		} else {
			$("input:submit").prop('disabled', false);
			if (busca_archivo_previo (entidad , archivo) == '1') {
				$("input:submit").prop('disabled', true);
				$(".error").fadeIn().html("Se tiene pendiente un padr&oacute;n de esta entidad.");
			} else {
				$("input:submit").prop('disabled', false);
				$(".error").hide();
					var maxInputSize = 800;
					var fileInput = $("#padron_sss")[0];
					var data = new FormData();
					
					for ( var i = 0 ; i < fileInput.files.length ; i++ ) {
						data.append("padron[]" , fileInput.files[i]);
						
						if (fileInput.files[i].type == 'text/plain') {
							console.log ("Type: OK");
							if (fileInput.files[i].size / (1024 * 1024) < maxInputSize) {
								console.log ("Size: OK");
								var validacion_archivo = 1;
							} else {
								console.log ("Size: NO");
								var validacion_archivo = 2;
							}
						} else {
							console.log ("Type: NO");
							var validacion_archivo = 0;
						}
					}
					
					data.append("entidad-sss" , entidad);
					data.append("archivo" , archivo);
					
					switch (validacion_archivo) {
						case 0 :
							$(".error").fadeIn().html("Tipo de archivo no permitido, solo se admiten archivos .txt");
							break;
						case 1 :
							var request = new XMLHttpRequest();
							
							request.upload.addEventListener("progress" , function (event){
								if (event.lengthComputable) {
									var percent = event.loaded / event.total;
									
									$(function(){
										$('#progeso_sss').css( 'width' , function (index){
											console.log (percent);
											return ((percent * 100) + '%');
										});
									});
								}
							});
							
							request.upload.addEventListener("load" , function (event){
								var percent = 0;
								document.getElementById("progeso_sss").style.display = "none";
							});
							
							request.upload.addEventListener("error" , function (event){
								alert ("Fallo la carga");
							});
							
							request.addEventListener("readystatechange" , function (event){
								if (this.readyState == 4) {
									if (this.status == 200) {
										var links = document.getElementById("subidas-sss");
										console.log (this.response);
										var subidas = eval(this.response);
										var div, a;
										
										for (var i = 0 ; i < subidas.length ; i ++ ) {
											div = document.createElement("div");

											div.appendChild(document.createTextNode('Se ha cargado el archivo ' + subidas[i]));
											links.appendChild(div);
											
										}
									} else {
										console.log ("El servidor respondio con " + this.status);
									}
								}
							});
							
							request.open("post" , "modulos/padron/tab-puco/carga_archivo_sss.php");
							request.setRequestHeader("Cache-Control" , "no-cache");
							document.getElementById("progeso_sss").style.display = "block";
							request.send (data);
						break;
						
						case 2 :
							$(".error").fadeIn().html("Tama&ntilde;o m&aacute;ximo exedido: " + maxInputSize + "MB");
						break;
						
						default: break;
					}
			}
		}
	});

	$('#id_archivo_sss').change(function(){
		$(".error").hide();
		var entidad = $('#entidad-sss').val();
		var archivo = $(this).val();

		if (entidad == 0) {
			$("input:submit").prop('disabled', true);
		} else {
			$("input:submit").prop('disabled', false);
			if (busca_archivo_previo (entidad , archivo) == '1') {
				$("input:submit").prop('disabled', true);
				$(".error").fadeIn().html("Se tiene pendiente un padr&oacute;n de esta entidad.");
			} else {
				$("input:submit").prop('disabled', false);
			}
		}
	});
});
</script>

<div class="portlet box purple form-up-data">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-reorder"></i>Ruta de archivo - Super Intendencia de Servicios de Salud
		</div>
	</div>
	<div class="portlet-body">
		<form method="post" enctype="multipart/form-data">
			<table>
				<tbody>
					<tr>
						<td>
							<select id="entidad-sss" name="provincia">
								<?php
								while ($reg = pg_fetch_assoc ($res)) {
									echo '<option value="' . $reg['codigo_os'] .'">' . $reg['nombre_os'] . '</option>';
								}
								?>
							</select>
							<select id="id_archivo_sss" name="id_archivo_sss">
								<option value="0">Seleccione archivo a cargar</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td><input type="file" name="padron" id="padron_sss"/></td>
						<td><input class="btn purple" type="submit" id="submit_sss" value="Enviar" /></td>
					</tr>
					<tr>
						<td colspan="3">
							<div id="subidas-sss"></div>
							<div class="progress">
								<div class="bar" id="progeso_sss"></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<p class="error"></p>
<p class="alert">Seleccione la ruta al archivo de obra social dentro de su ordenador. Recuerde respetar la estructura de datos.</p>

