<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/lotes_procesados.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'yellow' ,
	'FUENTE_DATOS'		=> 'Comprobantes' ,
	'ID_FUENTE_DATOS'	=> 3
);

HTML::Vista($html , $diccionario);

?>
