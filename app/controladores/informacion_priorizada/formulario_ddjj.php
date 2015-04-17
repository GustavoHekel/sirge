<?php

require_once '../../../init.php';

$inst_info_priorizada = new InformacionPriorizada();

$grupo = $_SESSION['grupo'];
//$grupo = '06';

if (isset($_POST['acc'])) {
	switch ($_POST['acc']) {
		case 'consulta':
			if ($grupo > 24) {
				echo "2";
			} else {

				//$sql = "select * from sistema.impresiones_ddjj_doiu where id_provincia = '$_SESSION[grupo]' and periodo_reportado = '$_POST[periodo]'";

				$cantidad = $inst_info_priorizada->getImpresionesDoiuPeriodo($grupo, $_POST['periodo']);

				if ($cantidad > 0) {
					// Ya se informó el periodo
					echo "1";
				} else {
					// No se informó el periodo
					echo "0";
				}
			}
			break;
		case 'generaddjj':

			$datos = $inst_info_priorizada->getVersion($grupo, $_POST['periodo']);

			if (count($datos) > 0) {
				$version = $datos[0]['version'] + 1;
			} else {
				$version = 1;
			}

			$array_datos_assoc = array(
				'id_provincia' => $grupo
				, 'id_usuario' => $_SESSION['id_usuario']
				, 'periodo' => $_POST['periodo']
				, 'periodo_tablero' => $_POST['periodo_tablero']
				, 'fecha_cuenta_capitas' => $_POST['fecha_cuenta_capitas']
				, 'periodo_cuenta_capitas' => $_POST['periodo_cuenta_capitas']
				, 'fecha_sirge' => $_POST['fecha_sirge']
				, 'periodo_sirge' => $_POST['periodo_sirge']
				, 'fecha_reporte_bimestral' => $_POST['fecha_reporte_bimestral']
				, 'bimestre' => $_POST['bimestre']
				, 'anio_bimestre' => $_POST['anio_bimestre']
				, 'version' => $version);

			$data = $inst_info_priorizada->insertarDddjjDoiu($array_datos_assoc);

			if (!$data) {
				echo "1";
			} else {
				echo "0";
			}
			break;

		case 'reimprimir':

			$data = $inst_info_priorizada->insertarMotivoReimpresionDoiu($grupo, $_POST['periodo'], $_POST['motivo_reimpresion']);

			if (!$data) {
				echo "1";
			} else {
				echo "0";
			}

			break;
	}
} else {

	$Html = array(
		'../../vistas/informacion_priorizada/formulario_ddjj.html',
	);

	$diccionario = array('cantidad_efectores_integrantes' => $inst_info_priorizada->getEfectoresIntegrantes($grupo)[0]['count'], 'cantidad_efectores_convenio' => $inst_info_priorizada->getEfectoresConvenio($grupo)[0]['count']);

	Html::vista($Html, $diccionario);
}
