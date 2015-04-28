<?php

class CompromisoAnual {
	private
	$_db;

	public function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public function getGraficoDescentralizacion($id_provincia, $year) {
		return $this->_db->faquery('getGraficoDescentralizacion', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => ($year - 1)], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoDescentralizacion', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => $year - 1], false)->getErrorInfo());
	}

	public function getGraficoDescentralizacionTotal($year) {
		return $this->_db->faquery('getGraficoDescentralizacionTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoDescentralizacionTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getErrorInfo());
	}

	public function getGraficoFacturacion($id_provincia, $year) {
		return $this->_db->faquery('getGraficoFacturacion', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => $year - 1], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoFacturacion', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => $year - 1], false)->getErrorInfo());
	}

	public function getGraficoFacturacionTotal($year) {
		return $this->_db->faquery('getGraficoFacturacionTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoFacturacionTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getErrorInfo());
	}

	public function getGraficoCodigosValidos($id_provincia, $year) {
		return $this->_db->faquery('getGraficoCodigosValidos', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => $year - 1], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoCodigosValidos', ['id_provincia' => $id_provincia, 'year' => $year, 'yearAnt' => $year - 1], false)->getErrorInfo());
	}

	public function getGraficoCodigosValidosTotal($year) {
		return $this->_db->faquery('getGraficoCodigosValidosTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getResults();
		//var_dump($this->_db->faquery('getGraficoCodigosValidosTotal', ['year' => $year, 'yearAnt' => ($year - 1)], false)->getErrorInfo());
	}
}
