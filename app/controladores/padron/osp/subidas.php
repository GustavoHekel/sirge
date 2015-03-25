<?php

require '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple' ,
	'FUENTE_DATOS'		=> 'Osp',
	'ID_FUENTE_DATOS'	=> 6,
	'CLASE_PROCESAR'	=> 'Archivo',
	'METODO_PROCESAR'	=> 'analizar'
   );

Html::vista($Html , $diccionario);