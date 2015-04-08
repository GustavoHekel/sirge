<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'yellow',
	'FUENTE_DATOS'		=> 'Comprobantes',
	'ID_FUENTE_DATOS'	=> 3,
	'CLASE_PROCESAR'	=> 'Archivo',
	'METODO_PROCESAR'	=> 'analizar'
);

Html::vista($Html , $diccionario);

?>
