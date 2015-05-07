<?php

class Indicadores {
	private $_db;

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

}