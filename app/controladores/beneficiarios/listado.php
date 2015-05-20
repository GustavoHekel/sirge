<?php

require_once '../../../init.php';

$Html = array (
  '../../vistas/beneficiarios/listado.html'
);

$diccionario = array (
  'CSS_GLOBAL' => 'blue'
);

Html::vista($Html , $diccionario);
