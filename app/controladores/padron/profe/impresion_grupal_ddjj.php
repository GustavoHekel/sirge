<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'FUENTE_DATOS'		=> 'Profe' ,
	'ID_FUENTE_DATOS'	=> 5
);

Html::vista($Html , $diccionario);
