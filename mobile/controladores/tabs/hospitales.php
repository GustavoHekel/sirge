<?php

require_once '../../init.php';

$e = new Efector();

$html = [
  '../../vistas/tabs/hospitales.html'
];

$diccionario = [
	'LISTADO' => $e->getListadoCercano($_REQUEST['lat'] , $_REQUEST['lon'])
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
