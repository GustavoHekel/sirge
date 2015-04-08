<?php
require_once '../conexion.php';

$strun = "truncate sistema.subidas cascade";
$rtrun = pg_query ($strun);

function GetEstado ($estado) {
	$e;
	switch ($estado) {
		case 'N' : $e = 0; break;
		case 'S' : $e = 1; break;
		case 'P' : $e = 2; break;
		case 'E' : $e = 3; break;
		default : die ("ESTADO NO ENCONTRADO");
	}
	return $e;
}

function GetOSP ($id_carga) {
	$sosp = "select codigo_os from sistema.cargas_archivos_osp where id_carga = $id_carga";
	$rosp = pg_query ($sosp);
	
	if (pg_num_rows ($rosp)) {
		return pg_fetch_row ($rosp , 0)[0];
	}
}

function GetN ($id_carga) {
	$snosp = "select id_archivo_sss from sistema.cargas_archivos_osp where id_carga = $id_carga and codigo_os = 998001";
	$rnosp = pg_query ($snosp);
	
	if (pg_num_rows ($rnosp)) {
		return pg_fetch_row ($rnosp , 0)[0];
	}
}

$subidas = array(
	'id_subida' => '' ,
	'id_usuario' => '' ,
	'fecha_subida' => '',
	'id_padron' => '',
	'nombre_original' => '',
	'nombre_actual' => '',
	'size' => '',
	'id_estado' => ''
);

$subidas_aceptadas = array(
	'id_subida' => '',
	'id_usuario' => '',
	'fecha_aceptado' => ''
);

$subidas_eliminadas = array(
	'id_subida' => '',
	'id_usuario' => '',
	'fecha_eliminacion' => ''
);
	
$subidas_osp = array(
	'id_subida' => '',
	'codigo_osp' => '',
	'id_archivo' => '',
	'nombre_backup' => ''
);

$sql = "select * from sistema.cargas_archivos";
$res = pg_query ($sql);
while ($reg = pg_fetch_assoc ($res)) {
	
	$subidas['id_subida'] = $reg['id_carga'];
	$subidas['id_usuario'] = $reg['id_usuario_carga'];
	$subidas['fecha_subida'] = $reg['fecha_carga'];
	$subidas['id_padron'] = $reg['id_padron'];
	$subidas['nombre_original'] = $reg['nombre_original'];
	$subidas['nombre_actual'] = $reg['nombre_actual'];
	$subidas['size'] = $reg['size'];
	$subidas['id_estado'] = GetEstado ($reg['procesado']);
	
	$sql_subidas = "insert into sistema.subidas values ('" . implode ("','" , $subidas) . "')";
	$res_subidas = pg_query ($sql_subidas);
	
	if ($reg['procesado'] == 'S') {
		
		$subidas_aceptadas['id_subida'] = $reg['id_carga'];
		$subidas_aceptadas['id_usuario'] = $reg['id_usuario_carga'];
		$subidas_aceptadas['fecha_aceptado'] = is_null ($reg['fecha_proceso']) ? "2012-01-01 00:00:00.00000" : $reg['fecha_proceso'];
		
		$sql_subidas_aceptadas = "insert into sistema.subidas_aceptadas values ('" . implode ("','" , $subidas_aceptadas) . "')";
		$res_subidas_aceptadas = pg_query ($sql_subidas_aceptadas);
	
	} else if ($reg['procesado'] == 'E') {
		$subidas_eliminadas['id_subida'] = $reg['id_carga'];
		$subidas_eliminadas['id_usuario'] = $reg['id_usuario_carga'];
		$subidas_eliminadas['fecha_eliminacion'] = is_null ($reg['fecha_baja']) ? "2012-01-01 00:00:00.00000" : $reg['fecha_baja'];
		
		$sql_subidas_eliminadas = "insert into sistema.subidas_eliminadas values ('" . implode ("','" , $subidas_eliminadas) . "')";
		$res_subidas_eliminadas = pg_query ($sql_subidas_eliminadas);
	}
	
	
	if ($reg['id_padron'] == 6) {
		
		$subidas_osp['id_subida'] = $reg['id_carga'];
		$subidas_osp['codigo_osp'] = is_null (GetOSP ($reg['id_carga'])) ? 0 : GetOSP ($reg['id_carga']);
		$subidas_osp['id_archivo'] = is_null (GetN ($reg['id_carga'])) ? 0 : GetN ($reg['id_carga']);
		$subidas_osp['nombre_backup'] = $reg['nombre_backup'];
		
		$sql_subidas_osp = "insert into sistema.subidas_osp values ('" . implode ("','" , $subidas_osp) . "')";
		$res_subidas_osp = pg_query ($sql_subidas_osp);
		
	}
}

$sql = "	
	DROP TABLE sistema.cargas_archivos CASCADE;
	DROP TABLE sistema.cargas_archivos_osp;";
pg_query ($sql);

?>
