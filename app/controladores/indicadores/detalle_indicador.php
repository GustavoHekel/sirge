<?php

require_once '../../../init.php';

$indic = new Indicadores();

$indicador = $_POST['indicador'];
$id_provincia = $_POST['id_provincia'];
$year = $_POST['year'];
$resultado = $_POST['resultado'];
$numerador = $_POST['num'];
$denominador = $_POST['den'];

$data = $indic->getIndicadorMedicaRangos($indicador, $id_provincia, $year)[0];

$data2       = $indic->getDescripcionIndicador($indicador)[0];
$descripcion = $data2['descripcion'];
$desc_num    = $data2['numerador'];
$desc_den    = $data2['denominador'];

//echo "<pre>", print_r($data2), "</pre>";

$diccionario = [
	'ANCHO_ROJO'       => (intval($data['min_rojo'] - $data['max_rojo']) * 500 / intval($data['min_verde'])),
	'ANCHO_AMARILLO'   => (intval($data['max_verde'] - $data['min_rojo']) * 500 / intval($data['min_verde'])),
	'ANCHO_VERDE'      => (intval($data['min_verde'] - $data['max_verde']) * 500 / intval($data['min_verde'])),
	'RESULTADO_PX'     => (intval($resultado) * 500 / intval($data['min_verde'])),
	'RESULTADO_XX'     => intval($resultado),
	'NUMERADOR'        => $indicador.'.a        = '.$numerador,
	'DENOMINADOR'      => $indicador.'.b      = '.$denominador,
	'NOMBRE_INDICADOR' => $descripcion.' - ('.$indicador.')',
	'DESC_NUMERADOR'   => html_entity_decode($desc_num),
	'DESC_DENOMINADOR' => html_entity_decode($desc_den)];

$html = [
	'../../vistas/indicadores/detalle_indicador_medica.html',
];

Html::vista($html, $diccionario);

?>