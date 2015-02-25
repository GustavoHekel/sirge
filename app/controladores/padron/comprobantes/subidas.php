<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'yellow',
	'FUENTE_DATOS'		=> 'Comprobantes',
	'ID_FUENTE_DATOS'	=> 3,
	'CLASE_PROCESAR'	=> 'Comprobantes',
	'METODO_PROCESAR'	=> 'ProcesaArchivo'
);

HTML::Vista($html , $diccionario);

?>
