<?php

require_once '../../../init.php';

$inst_sirge           = new Sirge();
$inst_info_priorizada = new InformacionPriorizada();

$grupo = $_SESSION['grupo'];
//$grupo = '06';

if (isset($_POST['provincia'])) {

	$data = $inst_info_priorizada->getHistorialDoiu($_POST['provincia']);

	if (count($data) > 0) {
		echo $inst_sirge->jsonDT($data, false);
	} else {
		$data['iTotalRecords']        = 0;
		$data['iTotalDisplayRecords'] = 0;
		echo json_encode($data);
	}
} else {

	$Html = array(
		'../../vistas/informacion_priorizada/historial_ddjj.html',
	);

	$diccionario = array(
		'provincias' => $inst_sirge->selectProvincia('provincia', $_SESSION['grupo']));

	Html::vista($Html, $diccionario);
}
?>