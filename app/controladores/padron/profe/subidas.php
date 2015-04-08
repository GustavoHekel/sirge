<?php

require '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'PROFE',
	'ID_FUENTE_DATOS'	=> 5,
	'CLASE_PROCESAR'	=> 'Archivo',
	'METODO_PROCESAR'	=> 'analizar'
   );

Html::vista($Html , $diccionario);