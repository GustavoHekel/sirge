<?php

class Indicadores 
{
	
  private $_db;

	public function __construct()
	{
		$this->_db = Bdd::getInstance();
	}

	public function prueba()
	{
		return $this->_db->fquery('prueba', [1], false)->getResults();
	}

	public function get_indicador_medica_rangos($indicador, $id_provincia, $year)
	{
		return $this->_db->fquery('indicador_medica_rangos', [$indicador, $id_provincia, $year], false)->getResults();
		//echo '<pre>', print_r($this->_db->getResults()), '</pre>';
	}

	public function get_res_ind_prov($periodo, $id_provincia, $indicador)
	{
		$this->_db->fquery('res_ind_prov', [$periodo, $id_provincia, $indicador], false)->getResults();
	}
}