<?php

require_once '../../../init.php';

$sirge = new Sirge();

if (isset($_POST['graficoProvincia'])) {
	$instGeo = new Geo();

	if (isset($_POST['provincia'])) {
		$reg = $instGeo->getGraficoProvincia($_POST['provincia']);

		$anterior = "No tengo nada";
		$j        = 0;

		for ($i = 0; $i < count($reg); $i++) {

			if ($reg[$i]['id_dto'] == $anterior) {
				$geo['ll'][(int) $reg[$i]['id_dto']][] = $reg[$i]['ll'];
			} else {
				$anterior                         = $reg[$i]['id_dto'];
				$geo['id_departamento'][(int) $j] = $reg[$i]['id_dto'];
				$j++;
				//$geo['id_departamento'][]                                      = $reg[$i]['id_departamento'];
				$geo['rgb'][(int) $reg[$i]['id_dto']]                 = round(255 - (255 * $reg[$i]['distribucion'] / 100));
				$geo['nombre_departamento'][(int) $reg[$i]['id_dto']] = $reg[$i]['nombre_departamento'];
				$geo['distribucion'][(int) $reg[$i]['id_dto']]        = $reg[$i]['distribucion'];
				$geo['cantidad'][(int) $reg[$i]['id_dto']]            = $reg[$i]['cantidad'];
			}
		}

		$geo['pos_provincia'] = $instGeo->getPosicionProvincia($_POST['provincia']);
		//var_dump($geo);
		//$geo['id_departamento'] = array_merge(array_unique($geo['id_departamento']));

		echo json_encode($geo, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
		//echo "";
	}

} else {

	$diccionario = array(
		'provincias' => $sirge->selectProvincia('provincias', $_SESSION['grupo']));

	$html = ['../../vistas/usuario/pruebas.html'];

	Html::vista($html, $diccionario);

}
