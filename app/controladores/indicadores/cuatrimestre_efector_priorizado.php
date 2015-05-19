<?php

require_once '../../../init.php';

$indic = new Indicadores();

$indicador    = $_POST['indicador'];
$efector      = $_POST['efector'];
$cuatrimestre = $_POST['cuatri'];
$valor        = $_POST['valor'];
$year         = $_POST['year'];

$data = $indic->getEfectorPriorizadoPorcentual($indicador, $efector, intval($year . '01'))[0];

//var_dump($data);

$descripcion = $indic->getDescripcionIndicador($indicador)[0];

$metas = $indic->getMetasEfectorPriorizado($indicador, $efector)[0];

//var_dump($descripcion);

switch ($cuatrimestre) {
	case 1:
		$mes1 = $data['enero'];
		$mes2 = $data['febrero'];
		$mes3 = $data['marzo'];
		$mes4 = $data['abril'];
		$meta = $metas['c1'];
		break;
	case 2:
		$mes1 = $data['mayo'];
		$mes2 = $data['junio'];
		$mes3 = $data['julio'];
		$mes4 = $data['agosto'];
		$meta = $metas['c2'];
		break;
	case 3:
		$mes1 = $data['septiembre'];
		$mes2 = $data['octubre'];
		$mes3 = $data['noviembre'];
		$mes4 = $data['diciembre'];
		$meta = $metas['c3'];
		break;

	default:
		# code...
		break;
}

$diccionario = [
	'NOMBRE_INDICADOR'      => $descripcion['descripcion'],
	'EFECTOR'               => $efector,
	'DESCRIPCION_INDICADOR' => $descripcion['descripcion'],
	'NUMERADOR'             => $descripcion['numerador'],
	'DENOMINADOR'           => $descripcion['denominador'],
	'CUATRIMESTRE'          => $cuatrimestre,
	'MES1'                  => $mes1,
	'MES2'                  => $mes2,
	'MES3'                  => $mes3,
	'MES4'                  => $mes4,
	'META'                  => $meta,
	'VALOR'                 => $valor,
	'DIF'                   => $valor - $meta,
];

$html = [
	'../../vistas/indicadores/cuatrimestre_efector_priorizado.html',
];

Html::vista($html, $diccionario);

?>