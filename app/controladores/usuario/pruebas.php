<?php

require_once '../../../init.php';

$sirge = new Sirge();

if (isset($_POST['graficoProvincia'])) {
	$instGeo = new Geo();

	if (isset($_SESSION['grupo'])) {
		$reg = $instGeo->getGraficoProvincia($_SESSION['grupo']);

		var_dump($reg);

		$anterior = "No tengo nada";

		for ($i = 0; $i < count($reg); $i++) {

			if ($reg[$i]['id_departamento'] == $anterior) {
				$geo['ll'][(int) $reg[$j]['id_departamento']][] = $reg[$i]['ll'];
			} else {
				$anterior                                                      = $reg[$i]['id_departamento'];
				$geo['id_departamento'][]                                      = $reg[$i]['id_departamento'];
				$geo['rgb'][(int) $reg[$i]['id_departamento']]                 = 255 - (255 * $reg[$i]['distribucion'] / 100);
				$geo['nombre_departamento'][(int) $reg[$i]['id_departamento']] = $reg[$i]['nombre_departamento'];
				$geo['distribucion'][(int) $reg[$i]['id_departamento']]        = $reg[$i]['distribucion'];
				$geo['cantidad'][(int) $reg[$i]['id_departamento']]            = $reg[$i]['cantidad'];
			}
		}

		$geo['id_departamento'] = array_merge(array_unique($geo['id_departamento']));

		echo json_encode($geo, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
	}

} else {

	$diccionario = array(
		'provincias' => $sirge->selectProvincia('provincias', $_SESSION['grupo']));

	$html = ['../../vistas/usuario/pruebas.html'];

	Html::vista($html, $diccionario);

}
