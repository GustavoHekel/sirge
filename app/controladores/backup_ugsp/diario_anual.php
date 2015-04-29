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

	//$id_provincia = '04'; // HARDCODEO para probar
	$id_provincia = $_SESSION['grupo'];

	$diccionario = array(
		'id_provincia' => $id_provincia);

	Html::vista($Html, $diccionario);
}

?>


