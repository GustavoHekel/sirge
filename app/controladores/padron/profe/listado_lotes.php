<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_lotes.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'PROFE' ,
	'ID_FUENTE_DATOS'	=> 5
);

Html::vista($Html , $diccionario);