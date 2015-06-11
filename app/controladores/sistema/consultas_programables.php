<?php
require_once '../../../init.php';

$html = array (
	'../../vistas/sistema/consultas_programables.html'
);

$diccionario = array (
	'CSS_GLOBAL' => 'grey'
);

Html::vista($html , $diccionario);
