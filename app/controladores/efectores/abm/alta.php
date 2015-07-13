<?php

require_once '../../../../init.php';

$e = new Efectores();
$g = new Geo();

$Html = [
	'../../../vistas/efectores/abm/alta.html',
];

$diccionario = [
	'CSS_GLOBAL' => 'green',
	'ID_GRUPO'   => $_SESSION['grupo'],
	'OPTIONS_TIPO_EFECTOR' => $e->selectTipoEfector(),
	'OPTIONS_CATEGORIZACION' => $e->selectCategorizacion(),
	'OPTIONS_DEPENDENCIA' => $e->selectDependencia(),
	'OPTIONS_TIPO_TELEFONO' => $e->selectTipoTelefono(),
	'OPTIONS_PROVINCIA' => $g->selectProvincias($_SESSION['grupo']),
];

Html::vista($Html, $diccionario);
