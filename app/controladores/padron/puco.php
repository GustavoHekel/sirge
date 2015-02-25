<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/padron/puco.html'
);

$diccionario = array (
	'FUENTE_DATOS' => 'prestaciones'
);

HTML::Vista($html , $diccionario);

?>
