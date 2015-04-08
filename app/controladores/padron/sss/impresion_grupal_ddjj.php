<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'red' ,
	'FUENTE_DATOS'		=> 'SSS' ,
	'ID_FUENTE_DATOS'	=> 4
);

Html::vista($Html , $diccionario);
