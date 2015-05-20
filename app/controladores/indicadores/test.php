<?php

require_once '../../../init.php';

$inst_sirge = new Sirge();

$start_date = $inst_sirge->get_Datetime_Now();

$periodo_actual = $_GET['fecha'];

$periodo_partido = explode("-", $periodo_actual);

$year  = $periodo_partido[0];
$month = $periodo_partido[1];

$array_periodos = array();

/*for ($i = 2; $i <= $month + 1; $i++) {
$periodo_temp           = intval(date("Ym", mktime(0, 0, 0, $i, 0, $year)));
$array_periodos[$i - 2] = $periodo_temp;
}*/

$periodo = intval(date("Ym", mktime(0, 0, 0, $month + 1, 0, $year)));
$fecha   = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));

echo $periodo;
echo "<br>";

//$indicadores = array("1.1", "1.2", "1.3", "1.4", "3.3", "3.4", "4.1", "4.2", "4.3", "5.1", "5.2", "5.3");
$indicadores = array("1.1", "1.2", "3.3", "3.4", "4.1", "4.2", "4.3", "5.1", "5.2", "5.3");

$inst_consulta = new Indicadores();

$provincias = $inst_consulta->id_provincias();

$fecha_inicio_cuatri = date("Y-m-d", mktime(0, 0, 0, $month - 3, 1, $year));
$fecha_fin_cuatri    = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));

echo "Fecha - " . $fecha;
echo "<br>";
echo "Fecha Ini Cuatri Ant - " . $fecha_inicio_cuatri;
echo "<br>";
echo "Fecha fin Cuatri Ant - " . $fecha_fin_cuatri;
echo "<br>";

//for ($i = 0; $i <= $month; $i++) {

//$periodo = $array_periodos[$i];

for ($j = 0; $j < count($indicadores); $j++) {

	$indicador = $indicadores[$j];

	for ($i = 0; $i < count($provincias); $i++) {

		$provincia = $provincias[$i]['id_provincia'];

		if ($indicador == "1.1") {

//--------CARGO VALORES CON CEB---------------------------------------------------------------------------------

			$consulta = $inst_consulta->get_ind_priorizado_1_1($provincia, $periodo, $indicador);

			if ($consulta) {
				echo "Error en el indicador 1.1";
				exit();
			}

		} else if ($indicador == "1.2") {

//--------CARGO VALORES CON CEB DE 6 a 9 años---------------------------------------------------------------------------------

			$consulta = $inst_consulta->get_ind_priorizado_1_2($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 1.2";
				exit();
			}

		} else if ($indicador == "5.1") {

//--------CARGO VALORES DE INDIGENAS CON CEB Y TOTALES---------------------------------------------------------------------------------

			$consulta = $inst_consulta->get_ind_priorizado_5_1($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri);

			if ($consulta) {
				echo "Error en el indicador 5.1";
				exit();
			}

		} else if ($indicador == "5.2") {

			$consulta = $inst_consulta->get_ind_priorizado_5_2_num($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri);

			if ($consulta) {
				echo "Error en el indicador 5.2 numerador";
				exit();
			}

			$consulta = $inst_consulta->get_ind_priorizado_5_2_den($provincia, $periodo, $indicador);

			if ($consulta) {
				echo "Error en el indicador 5.2 denominador";
				exit();
			}

		} else if ($indicador == "5.3") {

			$consulta = $inst_consulta->get_ind_priorizado_5_3($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri);

			if ($consulta) {
				echo "Error en el indicador 5.3";
				exit();
			}

		} else if ($indicador == "4.1") {

			$consulta = $inst_consulta->get_ind_priorizado_4_1_num($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 4.1 numerador";
				exit();
			}

			$consulta = $inst_consulta->get_ind_priorizado_4_1_den($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 4.1 denominador";
				exit();
			}

		} else if ($indicador == "4.2") {

			$consulta = $inst_consulta->get_ind_priorizado_4_2_num($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 4.2 numerador";
				exit();
			}

			$consulta = $inst_consulta->get_ind_priorizado_4_2_den($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 4.2 denominador";
				exit();
			}

		} else if ($indicador == "4.3") {

			$consulta = $inst_consulta->get_ind_priorizado_4_3($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 4.3";
				exit();
			}

		} else if ($indicador == "3.3") {

			$consulta = $inst_consulta->get_ind_priorizado_3_3_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri);

			if ($consulta) {
				echo "Error en el indicador 3.3 numerador";
				exit();
			}

			$consulta = $inst_consulta->get_ind_priorizado_3_3_den($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 3.3 denominador";
				exit();
			}

		} else if ($indicador == "3.4") {

			$consulta = $inst_consulta->get_ind_priorizado_3_4_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri);

			if ($consulta) {
				echo "Error en el indicador 3.4 numerador";
				exit();
			}

			$consulta = $inst_consulta->get_ind_priorizado_3_4_den($provincia, $periodo, $indicador, $fecha);

			if ($consulta) {
				echo "Error en el indicador 3.4 denominador";
				exit();
			}
		}
	}
}
//}

/*$consulta = " UPDATE indicadores.indicadores_priorizados
SET nombre = e.nombre
FROM efectores.efectores e
WHERE efector = e.cuie ";

$query = pg_query($con, $consulta);

if (!$query) {
echo "Error en la query final <br>";
echo pg_last_error();
exit;
} else {*/
echo "<br> TERMINO SIN ERRORES.<br>";
//}

$end_date = $inst_sirge->get_Datetime_Now();
$dd       = date_diff($end_date, $start_date);
//To get hours use $dd->h, minutes - $dd->i, seconds - $dd->s.
echo "Duracion: " . $dd->h . " horas " . $dd->i . " minutos " . $dd->s . " segundos";

?>