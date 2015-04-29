<?php
require_once '../../../init.php';

$inst_comp_anual = new CompromisoAnual();
$inst_sirge      = new Sirge();

if (isset($_POST['year'])) {

	$_SESSION['grupo'] == 25 ? $data = $inst_comp_anual->getGraficoCodigosValidosTotal($_POST['year']) : $data = $inst_comp_anual->getGraficoCodigosValidos($_SESSION['grupo'], $_POST['year']);

	if (count($data) > 0) {

		if ($_SESSION['grupo'] < 25) {
			$i = 0;

			//creo los arrays

			foreach ($data[0] as $key => $value) {
				$datos[$key] = array();
			}

			for ($i = 0; $i < count($data); $i++) {
				foreach ($data[$i] as $key => $value) {
					switch ($key) {

						case 'nombre':
							array_push($datos['nombre'], $value . " " . $data[$i]['year']);
							break;

						default:
							array_push($datos[$key], $value);
							break;
					}
				}
			}
		} else {

			$datos['primer_semestre_' . $data[0]['year']]  = array();
			$datos['segundo_semestre_' . $data[0]['year']] = array();
			$datos['primer_semestre_' . $data[1]['year']]  = array();
			$datos['segundo_semestre_' . $data[1]['year']] = array();

			for ($i = 0; $i < count($data[0]); $i++) {
				foreach ($data[0] as $key => $value) {
					if ($key != "primer_semestre" && $key != "segundo_semestre") {
						$datos[$key] = array();
					}
				}
			}

			for ($i = 0; $i < count($data); $i++) {
				foreach ($data[$i] as $key => $value) {

					if ($i % 2 != 0) {

						switch ($key) {

							case 'primer_semestre':
								array_push($datos['primer_semestre_' . $data[$i]['year']], $value);
								break;
							case 'segundo_semestre':
								array_push($datos['segundo_semestre_' . $data[$i]['year']], $value);
								break;
						}
					} else {

						switch ($key) {

							case 'primer_semestre':
								array_push($datos['primer_semestre_' . $data[$i]['year']], $value);
								break;
							case 'segundo_semestre':
								array_push($datos['segundo_semestre_' . $data[$i]['year']], $value);
								break;
							case 'nombre':
								array_push($datos['nombre'], $value);
								break;
							default:
								array_push($datos[$key], $value);
								break;
						}
					}
				}
			}
		}

	}

	echo json_encode($datos, JSON_NUMERIC_CHECK);

} else {

	$Html = array(
		'../../vistas/compromiso_anual/codigos_validos.html',
	);

	$diccionario = array('id_provincia' => $_SESSION['grupo']);

	Html::vista($Html, $diccionario);
}

?>