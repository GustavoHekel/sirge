<?php

require_once '../../../../init.php';

$Html = [
  '../../../vistas/efectores/abm/alta.html'
];

$diccionario = [
  'CSS_GLOBAL' => 'green',
  'ID_GRUPO' => $_SESSION['grupo']
];

Html::vista($Html , $diccionario);
