<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'Prestaciones' ,
	'CSS_GLOBAL' 		=> 'green',
	'ID_FUENTE_DATOS'	=> 1
);

Html::vista($Html , $diccionario);

?>
