<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'Comprobantes' ,
	'CSS_GLOBAL' 		=> 'yellow',
	'ID_FUENTE_DATOS'	=> 3
);

Html::vista($Html , $diccionario);

?>
