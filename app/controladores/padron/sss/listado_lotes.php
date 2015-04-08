<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_lotes.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'red' ,
	'FUENTE_DATOS'		=> 'SSS' ,
	'ID_FUENTE_DATOS'	=> 4
);

Html::vista($Html , $diccionario);