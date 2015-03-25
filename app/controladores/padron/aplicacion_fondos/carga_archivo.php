<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/carga_archivo.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'blue' ,
	'CSS_PROGRESO'		=> '' ,
	'FUENTE_DATOS_DIV'	=> 'Aplicaci&oacute;n de fondos' ,
	'FUENTE_DATOS'		=> 'aplicacion_fondos' ,
	'ID_FUENTE_DATOS'	=> 2 ,
	'TEXTO_ALERTA' 		=> 'Seleccione la ruta al archivo de aplicaci&oacute;n de fondos dentro de su ordenador. Recuerde respetar la estructura de datos.'
);

Html::vista($Html , $diccionario);

?>
