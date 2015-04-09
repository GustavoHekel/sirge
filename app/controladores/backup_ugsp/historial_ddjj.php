<?php

require_once '../../../init.php';

$inst_ddjj = new DdjjBackup();

$Html = array(
	'../../vistas/backup_ugsp/historial_ddjj.html',
);

$sirge = new Sirge();

$diccionario = array(
	'provincias' => $sirge->selectProvincia('provincia', $_SESSION['grupo']));

Html::vista($Html, $diccionario);

?>


