<?php

require_once '../../../init.php';

$inst_ddjj = new DdjjBackup();

if (isset($_POST['year']))
{
	var_dump($data = $inst_ddjj->getBackupsAño($_SESSION['grupo'], $_POST['year']));
}
else
{
	$Html = array(
		'../../vistas/backup_ugsp/diario_anual.html',
	);

	$sirge = new Sirge();

	$diccionario = array(
		'provincias' => $sirge->selectProvincia('provincia', $_SESSION['grupo']));

	Html::vista($Html, $diccionario);
}

?>


