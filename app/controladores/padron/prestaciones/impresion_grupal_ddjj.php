<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'green' ,
	'FUENTE_DATOS'		=> 'Prestaciones' ,
	'ID_FUENTE_DATOS'	=> 1
);

HTML::Vista($html , $diccionario);

?>
