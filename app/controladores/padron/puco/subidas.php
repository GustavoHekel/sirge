<?php

require_once '../../../../init.php';

$html = array (
	'../../../vistas/padron/puco/subidas.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple',
	'SELECT_PROVINCIA' 	=> PUCO::SelectOSP('id_entidad' , $_SESSION['grupo']),
	'ID_FUENTE_DATOS' 	=> 6
);

HTML::Vista($html , $diccionario);

?>
