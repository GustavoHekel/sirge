<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_lotes.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple' ,
	'FUENTE_DATOS'		=> 'OSP' ,
	'ID_FUENTE_DATOS'	=> 6
);

Html::vista($Html , $diccionario);