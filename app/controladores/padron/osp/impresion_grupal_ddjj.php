<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/impresion_grupal_ddjj.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple' ,
	'FUENTE_DATOS'		=> 'Osp' ,
	'ID_FUENTE_DATOS'	=> 6
);

Html::vista($Html , $diccionario);
