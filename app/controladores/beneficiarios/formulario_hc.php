<?php

require_once '../../../init.php';

$benef = new Beneficiarios();

$Html = array (
  '../../vistas/beneficiarios/formulario_hc.html'
);

$diccionario = array (
	
);

Html::vista($Html , $diccionario);
