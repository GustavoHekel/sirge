<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'Aplicaci&oacute;n Fondos' ,
	'CSS_GLOBAL' 		=> 'blue',
	'ID_FUENTE_DATOS'	=> 2
);

Html::Vista($html , $diccionario);

?>
