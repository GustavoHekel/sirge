<?php

require_once '../../init.php';

$html = [
  '../../vistas/panel/sumar.html'
];

$diccionario = [];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
