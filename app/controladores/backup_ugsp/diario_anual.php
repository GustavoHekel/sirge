<?php

require_once '../../../init.php';

$inst_ddjj  = new DdjjBackup();
$inst_sirge = new Sirge();

if (isset($_POST['year'])) {
	$data = $inst_ddjj->getBackupsAño($_SESSION['grupo'], $_POST['year']);
	echo $inst_sirge->jsonDT($data, false);
} else {
	$Html = array(
		'../../vistas/backup_ugsp/diario_anual.html',
	);

	$diccionario = array(
		'id_provincia' => $_SESSION['grupo']);

	Html::vista($Html, $diccionario);
}

?>


