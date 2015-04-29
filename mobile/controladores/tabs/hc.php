<?php

require_once '../../init.php';

$html = [
  '../../vistas/tabs/hc.html'
];

$diccionario = [];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
