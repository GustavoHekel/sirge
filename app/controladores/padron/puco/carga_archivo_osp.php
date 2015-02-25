<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/puco/carga_archivo_osp.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple',
	'SELECT_PROVINCIA' 	=> SIRGe::SelectProvincia('id_entidad' , $_SESSION['grupo']),
	'ID_FUENTE_DATOS' 	=> 6
);

HTML::Vista($html , $diccionario);

?>
