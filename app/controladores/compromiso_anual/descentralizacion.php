<?php
require_once '../../../init.php';

$inst_comp_anual = new CompromisoAnual();
$inst_sirge      = new Sirge();

if (isset($_POST['year'])) {

	$_SESSION['grupo'] == 25 ? $data = $inst_comp_anual->getGraficoDescentralizacionTotal($_POST['year']) : $data = $inst_comp_anual->getGraficoDescentralizacion($_SESSION['grupo'], $_POST['year']);

	if (count($data) > 0) {
		$i = 0;

		//creo los arrays
		foreach ($data[0] as $key => $value) {
			switch ($key) {
				case 'primer_semestre_anio_ant':
					$datos['primer_semestre_' . ($_POST['year'] - 1)] = array();
					break;
				case 'segundo_semestre_anio_ant':
					$datos['segundo_semestre_' . ($_POST['year'] - 1)] = array();
					break;
				case 'primer_semestre_anio_actual':
					$datos['primer_semestre_' . $_POST['year']] = array();
					break;
				case 'segundo_semestre_anio_actual':
					$datos['segundo_semestre_' . $_POST['year']] = array();
					break;
				default:
					$datos[$key] = array();
					break;
			}
		}

		for ($i = 0; $i < count($data); $i++) {
			foreach ($data[$i] as $key => $value) {
				switch ($key) {
					case 'primer_semestre_anio_ant':
						array_push($datos['primer_semestre_' . ($_POST['year'] - 1)], $value);
						break;
					case 'segundo_semestre_anio_ant':
						array_push($datos['segundo_semestre_' . ($_POST['year'] - 1)], $value);
						break;
					case 'primer_semestre_anio_actual':
						array_push($datos['primer_semestre_' . $_POST['year']], $value);
						break;
					case 'segundo_semestre_anio_actual':
						array_push($datos['segundo_semestre_' . $_POST['year']], $value);
						break;
					default:
						array_push($datos[$key], $value);
						break;
				}
			}
		}

	}
	if ($_SESSION['grupo'] < 25) {
		array_push($datos['nombre'], $datos['nombre'][0] . " " . $_POST['year']);
		array_push($datos['nombre'], $datos['nombre'][0] . " " . $_POST['year']);
		array_push($datos['descentralizacion'], $datos['descentralizacion'][0]);
		$datos['nombre'][0]           = $datos['nombre'][0] . " " . ($_POST['year'] - 1);
		$datos['primer_semestre']     = array();
		$datos['segundo_semestre']    = array();
		$datos['primer_semestre'][0]  = $datos['primer_semestre_' . ($_POST['year'] - 1)][0];
		$datos['segundo_semestre'][0] = $datos['segundo_semestre_' . ($_POST['year'] - 1)][0];
		array_push($datos['primer_semestre'], $datos['primer_semestre_' . $_POST['year']][0]);
		array_push($datos['segundo_semestre'], $datos['segundo_semestre_' . $_POST['year']][0]);

	}
	echo json_encode($datos, JSON_NUMERIC_CHECK);

	//var_dump($data);

	//echo $inst_sirge->jsonDT($data, false);
} else {

	$Html = array(
		'../../vistas/compromiso_anual/descentralizacion.html',
	);

	$diccionario = array('id_provincia' => $_SESSION['grupo']);

	Html::vista($Html, $diccionario);
}
?>