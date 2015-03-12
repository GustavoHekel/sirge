<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'Aplicaci&oacute;n de Fondos',
	'ID_FUENTE_DATOS'	=> 2,
	'CLASE_PROCESAR'	=> 'Fondos',
	'METODO_PROCESAR'	=> 'ProcesaArchivo'
);

Html::Vista($html , $diccionario);

?>
