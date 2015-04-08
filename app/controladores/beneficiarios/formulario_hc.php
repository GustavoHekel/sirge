<?php

require_once '../../../init.php';

$benef = new Beneficiarios();

<<<<<<< HEAD
$Html = array (
  '../../vistas/beneficiarios/formulario_hc.html'
=======


$Html = array (
	'../../vistas/beneficiarios/formulario_hc.html'
>>>>>>> 97477a8c1cbe908c65c71c0041e8efb517f228f6
);

$diccionario = array (
	
);

Html::vista($Html , $diccionario);