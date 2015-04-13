<?php

require_once '../../../../init.php';

$e = new Efectores();

$Html = [
  '../../../vistas/efectores/abm/alta.html'
];

$diccionario = [
  'CSS_GLOBAL' => 'green',
  'ID_GRUPO' => $_SESSION['grupo'],
  'OPTIONS_TIPO_EFECTOR' => $e->selectTipoEfector(),
  'OPTIONS_CATEGORIZACION' => $e->selectCategorizacion(),
  'OPTIONS_DEPENDENCIA' => $e->selectDependencia()
];

Html::vista($Html , $diccionario);
