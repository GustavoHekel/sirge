<?php

require_once '../../../../init.php';

$Html = array (
	'../../../vistas/padron/tabs/carga_archivo_osp.html'
);

$diccionario = array (
	'CSS_GLOBAL' 		=> 'purple',
	'SELECT_PROVINCIA' 	=> Puco::SelectOSP('id_entidad' , $_SESSION['grupo']),
	'ID_FUENTE_DATOS' 	=> 6
);

Html::vista($Html , $diccionario);