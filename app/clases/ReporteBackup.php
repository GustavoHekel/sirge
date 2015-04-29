<?php

class DdjjBackup extends PdfDdjjSirge {
	private $_db;

	public function __construct()
	{
		$this->_db = Bdd::getInstance();
	}

	public function getImpresionEnPeriodo($grupo, $periodo)
	{
		return $this->_db->fquery('getImpresionEnPeriodo', [$grupo, $periodo], false)->getResults();
	}
}