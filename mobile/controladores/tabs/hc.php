<?php

require_once '../../init.php';

$id = $_REQUEST['user'];
$u = new Usuario();

$html = [
  '../../vistas/tabs/hc.html'
];

$diccionario = [
	'PRACTICAS' => $u->getListadoPracticas($id)
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
