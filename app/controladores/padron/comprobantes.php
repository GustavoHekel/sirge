<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/padron/fuente_datos.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'comprobantes'
);

HTML::Vista($html , $diccionario);

?>
