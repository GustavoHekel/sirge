<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/carga_archivo_sss.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'red',
	'ID_FUENTE_DATOS' 	=> 4
);

Html::vista($Html , $diccionario);