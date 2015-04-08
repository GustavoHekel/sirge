<?php

require_once '../../../../init.php';

$Html = array (
    '../../../vistas/padron/tabs/carga_archivo_profe.html'
);

$diccionario = array (
    'CSS_GLOBAL' 		=> 'blue',
    'ID_FUENTE_DATOS' 	=> 5
);

Html::vista($Html , $diccionario);