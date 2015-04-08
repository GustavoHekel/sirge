<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_lotes.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'Aplicaci&oacute;n de Fondos' ,
	'ID_FUENTE_DATOS'	=> 2
);

Html::vista($Html , $diccionario);

?>
