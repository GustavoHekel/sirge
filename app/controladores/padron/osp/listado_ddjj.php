<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/listado_ddjj.html'
);

$diccionario = array(
	'FUENTE_DATOS' 		=> 'Osp' ,
	'CSS_GLOBAL' 		=> 'purple',
	'ID_FUENTE_DATOS'	=> 6
);

Html::vista($Html , $diccionario);
