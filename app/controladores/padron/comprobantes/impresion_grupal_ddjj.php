<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'yellow' ,
	'FUENTE_DATOS'		=> 'Comprobantes' ,
	'ID_FUENTE_DATOS'	=> 3
);

Html::vista($Html , $diccionario);

?>
