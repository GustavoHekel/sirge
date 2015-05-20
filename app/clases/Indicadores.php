<?php

class Indicadores {
	private $_db;
	private $consulta;

	public function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public function prueba() {
		return $this->_db->fquery('prueba', [1], false)->getResults();
	}

	public function getIndicadorMedicaRangos($indicador, $id_provincia, $year) {
		return $this->_db->fquery('getIndicadorMedicaRangos', [$indicador, $id_provincia, $year], false)->getResults();
		//echo '<pre>', print_r($this->_db->getResults()), '</pre>';
	}

	public function getResultadosIndicadorProvincial($periodo, $id_provincia, $indicador) {
		return $this->_db->fquery('getResultadosIndicadorProvincial', [$periodo, $id_provincia, $indicador], false)->getResults();
	}

	public function getResultadosIndicadorProvPromedio($fecha, $fecha, $id_provincia, $indicador) {
		return $this->_db->fquery('getResultadosIndicadorProvPromedio', [$fecha, $fecha, $id_provincia, $indicador], false)->getResults();
		//echo '<pre>', print_r($this->_db->getResults()), '</pre>';
	}

	public function getDescripcionIndicador($indicador) {
		return $this->_db->fquery('getDescripcionIndicador', [$indicador], false)->getResults();
	}

	public function getEfectoresPriorizados($indicador, $id_provincia) {
		return $this->_db->faquery('getEfectoresPriorizados', ['indicador' => $indicador, 'id_provincia' => $id_provincia], false)->getResults();
	}

	public function getEfectoresPriorizadosPorcentual($indicador, $id_provincia, $periodo) {
		return $this->_db->faquery('getEfectoresPriorizadosPorcentual', ['indicador' => $indicador, 'id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getResults();
		//var_dump($this->_db->faquery('getEfectoresPriorizadosPorcentual', ['indicador' => $indicador, 'id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getErrorInfo());
	}

	public function getEfectorPriorizadoPorcentual($indicador, $efector, $periodo) {
		return $this->_db->faquery('getEfectorPriorizadoPorcentual', ['indicador' => $indicador, 'efector' => $efector, 'periodo' => $periodo], false)->getResults();
	}

	public function getAnualEfectoresPriorizadosPorcentual($indicador, $id_provincia, $periodo) {
		if ($indicador == "5.3") {
			return $this->_db->faquery('getAnualEfectoresPriorizadosNoPorcentual', ['indicador' => $indicador, 'id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getResults();
		} else {
			return $this->_db->faquery('getAnualEfectoresPriorizadosPorcentual', ['indicador' => $indicador, 'id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getResults();
			//var_dump($this->_db->faquery('getEfectoresPriorizadosPorcentual', ['indicador' => $indicador, 'id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getErrorInfo());
		}
	}

	public function getMetasEfectorPriorizado($indicador, $efector) {
		return $this->_db->faquery('getMetasEfectorPriorizado', ['indicador' => $indicador, 'efector' => $efector], false)->getResults();
	}

	public function getColorCuatrimestre($resultado, $meta) {
		if ($resultado == 0) {
			return 'red';
		} /*elseif ($resultado - $meta < ($meta * -0.5)) {
		return 'red';
		} */elseif ($resultado - $meta < 0) {
			return 'red';
		} else {
			return 'green';
		}
	}

	public function get_ind_priorizado_1_1($provincia, $periodo, $indicador) {
		echo $this->ind_priorizado_1_1($provincia, $periodo, $indicador);
	}

	private function ind_priorizado_1_1($provincia, $periodo, $indicador) {
		echo $this->_db->faquery('ind_priorizado_1_1', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador], false)->getErrorInfo();
	}

	public function get_ind_priorizado_1_2($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_1_2($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_1_2($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_1_2', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha], false)->getErrorInfo();
	}

	public function get_ind_priorizado_3_3_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->ind_priorizado_3_3_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri);
	}

	private function ind_priorizado_3_3_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->_db->faquery('ind_priorizado_3_3_num', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'fecha_inicio_cuatri' => $fecha_inicio_cuatri, 'fecha_fin_cuatri' => $fecha_fin_cuatri, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_3_3_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_3_3_den($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_3_3_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_3_3_den', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_3_4_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->ind_priorizado_3_4_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri);
	}

	private function ind_priorizado_3_4_num($provincia, $periodo, $indicador, $fecha, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->_db->faquery('ind_priorizado_3_4_num', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'fecha_inicio_cuatri' => $fecha_inicio_cuatri, 'fecha_fin_cuatri' => $fecha_fin_cuatri], false)->getErrorInfo();
	}

	public function get_ind_priorizado_3_4_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_3_4_den($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_3_4_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_3_4_den', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha], false)->getErrorInfo();
	}

	public function get_ind_priorizado_4_1_num($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_4_1_num($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_4_1_num($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_4_1_num', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_4_1_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_4_1_den($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_4_1_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_4_1_den', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha], false)->getErrorInfo();
	}

	public function get_ind_priorizado_4_2_num($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_4_1_den($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_4_2_num($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_4_2_num', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_4_2_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_4_2_den($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_4_2_den($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_4_2_den', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha], false)->getErrorInfo();
	}

	public function get_ind_priorizado_4_3($provincia, $periodo, $indicador, $fecha) {
		echo $this->ind_priorizado_4_3($provincia, $periodo, $indicador, $fecha);
	}

	private function ind_priorizado_4_3($provincia, $periodo, $indicador, $fecha) {
		echo $this->_db->faquery('ind_priorizado_4_3', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha' => $fecha, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_5_1($provincia, $periodo, $indicador) {
		echo $this->ind_priorizado_5_1($provincia, $periodo, $indicador);
	}

	private function ind_priorizado_5_1($provincia, $periodo, $indicador) {
		echo $this->_db->faquery('ind_priorizado_5_1', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador], false)->getErrorInfo();
	}

	public function get_ind_priorizado_5_2_num($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->ind_priorizado_5_2_num($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri);
	}

	private function ind_priorizado_5_2_num($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->_db->faquery('ind_priorizado_5_2_num', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha_inicio_cuatri' => $fecha_inicio_cuatri, 'fecha_fin_cuatri' => $fecha_fin_cuatri, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function get_ind_priorizado_5_2_den($provincia, $periodo, $indicador) {
		echo $this->ind_priorizado_5_2_den($provincia, $periodo, $indicador);
	}

	private function ind_priorizado_5_2_den($provincia, $periodo, $indicador) {
		echo $this->_db->faquery('ind_priorizado_5_2_den', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador], false)->getErrorInfo();
	}

	public function get_ind_priorizado_5_3($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->ind_priorizado_5_3($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri);
	}

	private function ind_priorizado_5_3($provincia, $periodo, $indicador, $fecha_inicio_cuatri, $fecha_fin_cuatri) {
		echo $this->_db->faquery('ind_priorizado_5_3', ['provincia' => $provincia, 'periodo' => $periodo, 'indicador' => $indicador, 'fecha_inicio_cuatri' => $fecha_inicio_cuatri, 'fecha_fin_cuatri' => $fecha_fin_cuatri, 'tabla_provincia' => "prestaciones.p_" . $provincia], false)->getErrorInfo();
	}

	public function cargar_beneficiarios_periodos_y_ceb_del_mes($periodo) {
		return $this->beneficiarios_periodos_y_ceb_del_mes($periodo);
	}

	private function beneficiarios_periodos_y_ceb_del_mes($periodo) {
		return $this->_db->faquery('beneficiarios_periodos_y_ceb_del_mes', ['periodo' => $periodo], false)->getResults();
	}

	public function id_provincias() {

		$sql = " SELECT id_provincia FROM indicadores.provincias; ";

		return $this->_db->query($sql, [], false)->getResults();
	}

}