<?php

require_once '../../../../init.php';

$e = new Efectores();
$data = $e->getInfoBaja($_POST['efector']);

$Html = [
  '../../../vistas/efectores/abm/form_baja.html'
];

$diccionario = [
  'CSS_GLOBAL' => 'green',
  'CUIE' => $data['cuie'],
  'SIISA' => $data['siisa'],
  'NOMBRE' => $data['nombre'],
  'INTEGRANTE' => $data['integrante'] ,
  'COMPROMISO' => $data['compromiso_gestion'],
  'SUMAR' => $data['sumar'],
  'PRIORIZADO' => $data['priorizado']
];

Html::vista($Html , $diccionario);
