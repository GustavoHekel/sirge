<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/puco/carga_archivo_profe.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple',
	'ID_FUENTE_DATOS' 	=> 6
);

HTML::Vista($html , $diccionario);

?>
