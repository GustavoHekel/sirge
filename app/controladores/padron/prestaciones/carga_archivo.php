<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/carga_archivo.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'green' ,
	'CSS_PROGRESO'		=> 'progress-success' ,
	'FUENTE_DATOS_DIV'	=> 'Prestaciones' ,
	'FUENTE_DATOS'		=> 'prestaciones' ,
	'ID_FUENTE_DATOS'	=> 1 ,
	'TEXTO_ALERTA' 		=> 'Seleccione la ruta al archivo de prestaciones dentro de su ordenador. Recuerde respetar la estructura de datos.'
);

Html::vista($Html , $diccionario);

?>
