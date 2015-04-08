<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_lotes.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'green' ,
	'FUENTE_DATOS'		=> 'Prestaciones' ,
	'ID_FUENTE_DATOS'	=> 1
);

Html::vista($Html , $diccionario);