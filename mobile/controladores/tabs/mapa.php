<?php

require_once '../../init.php';

$html = [
  '../../vistas/tabs/hospitales.html'
];

$diccionario = [
	'LISTADO' => getListadoCercano($_REQUEST['ll'])
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
