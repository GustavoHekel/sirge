<?php

require_once '../../../../init.php';

if ( ! isset($_POST['efector']))
{

	$Html = [
		'../../../vistas/efectores/abm/modificacion.html',
	];

	$diccionario = [];

	Html::vista($Html, $diccionario);
}
else
{
	$inst_efector = new Efectores();
	$datos = $inst_efector->getEfectorBySiisaOCuie($_POST['efector']);

	if ($datos == null)
	{
		echo 0;
	}
	else
	{
		echo ($_SESSION['grupo'] == $datos[0]['id_provincia'] || $_SESSION['grupo'] > 24) ? $datos[0]['cuie'] : 1;
	}
}

?>
