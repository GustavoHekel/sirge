<?php

require_once '../../../init.php';

$benef = new Beneficiarios();



$html = array (
	'../../vistas/beneficiarios/historia_clinica.html'
);

$diccionario = array (
	//'MATRIZ' => $benef->Matrix('2070000700331621')
);

HTML::Vista($html , $diccionario);

?>
