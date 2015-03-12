<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'Aplicaci&oacute;n de fondos' ,
	'ID_FUENTE_DATOS'	=> 2
);

Html::Vista($html , $diccionario);

?>

