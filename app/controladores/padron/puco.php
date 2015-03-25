<?php

require_once '../../../init.php';

$Html = array (
	'../../vistas/padron/puco.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'prestaciones'
);

Html::vista($Html , $diccionario);

?>
