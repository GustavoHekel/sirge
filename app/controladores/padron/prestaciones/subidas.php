<?php

require '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'green' ,
	'FUENTE_DATOS'		=> 'Prestaciones',
	'ID_FUENTE_DATOS'	=> 1,
	'CLASE_PROCESAR'	=> 'Archivo',
	'METODO_PROCESAR'	=> 'analizar'
);

Html::vista($Html , $diccionario);