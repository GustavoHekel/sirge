<?php

require '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'red' ,
	'FUENTE_DATOS'		=> 'SSS',
	'ID_FUENTE_DATOS'	=> 4,
	'CLASE_PROCESAR'	=> 'Archivo',
	'METODO_PROCESAR'	=> 'analizar'
   );

Html::vista($Html , $diccionario);