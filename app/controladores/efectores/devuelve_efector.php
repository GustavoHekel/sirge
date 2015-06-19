<?php

require_once '../../../init.php';

$e = new Efectores();

if (isset($_POST['term'])) {

	$datos = $e->getSugerenciaEfectores($_POST['term']);

	if (count($datos)) {
		foreach ($datos as $fila) {
			$data[] = $fila['cuie'];
		}
	} else {
		$data = 0;
	}
	echo json_encode($data);
} else {
	echo 0;
}
