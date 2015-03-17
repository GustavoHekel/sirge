<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/tabs/carga_archivo.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'yellow' ,
	'CSS_PROGRESO'		=> 'progress-warning' ,
	'FUENTE_DATOS_DIV'	=> 'Comprobantes' ,
	'FUENTE_DATOS'		=> 'comprobantes' ,
	'ID_FUENTE_DATOS'	=> 3 ,
	'TEXTO_ALERTA' 		=> 'Seleccione la ruta al archivo de comprobantes dentro de su ordenador. Recuerde respetar la estructura de datos.'
);

Html::vista($html , $diccionario);

?>
