<?php

require_once '../../../init.php';

$efectores_priorizados = new Indicadores();

$tz_object = new DateTimeZone('Brazil/East');
//date_default_timezone_set('Argentina/East');

$datetime   = new DateTime();
$start_date = $datetime->setTimezone($tz_object);

$anio_actual  = $start_date->format('Y');
$periodo_text = $anio_actual . '01';
$periodo      = intval($periodo_text);

$datos = $efectores_priorizados->getAnualEfectoresPriorizadosPorcentual($_POST['indicador'], $_POST['provincia'], $periodo);

for ($i = 0; $i < count($datos); $i++) {
	$datos[$i]['c1_color'] = $efectores_priorizados->getColorCuatrimestre($datos[$i]['c1'], $datos[$i]['meta_c1']);
	$datos[$i]['c2_color'] = $efectores_priorizados->getColorCuatrimestre($datos[$i]['c2'], $datos[$i]['meta_c2']);
	$datos[$i]['c3_color'] = $efectores_priorizados->getColorCuatrimestre($datos[$i]['c3'], $datos[$i]['meta_c3']);
}

echo json_encode($datos);

?>