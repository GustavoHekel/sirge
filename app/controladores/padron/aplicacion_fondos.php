<?php

require_once '../../../init.php';

$Html = array (
	'../../vistas/padron/fuente_datos.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'aplicacion_fondos'
);

Html::vista($Html , $diccionario);
