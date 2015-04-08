<?php

require_once '../../../init.php';

$diccionario = [
    'ANCHO_ROJO' => (25*500/100),
    'ANCHO_AMARILLO' => (35*500/100),
    'ANCHO_VERDE' => (40*500/100),
    'RESULTADO_PX' => (70*500/100),
    'RESULTADO_XX' => 70
];

$html = [
    '../../vistas/indicadores/detalle_indicador_medica.html'
];

Html::vista($html, $diccionario);