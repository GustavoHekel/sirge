<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/padron/fuente_datos.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'comprobantes'
);

Html::Vista($html , $diccionario);

?>
