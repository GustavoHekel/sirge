<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'SSS' ,
	'CSS_GLOBAL' 		=> 'red',
	'ID_FUENTE_DATOS'	=> 4
);

Html::vista($Html , $diccionario);
