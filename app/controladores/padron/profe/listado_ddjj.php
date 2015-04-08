<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'Profe' ,
	'CSS_GLOBAL' 		=> 'blue',
	'ID_FUENTE_DATOS'	=> 5
);

Html::vista($Html , $diccionario);
