<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/padron/fuente_datos.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'aplicacion_fondos'
);

HTML::Vista($html , $diccionario);

?>
