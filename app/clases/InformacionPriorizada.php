<?php

class InformacionPriorizada {
	private
	$_db;

	public function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public function getEfectoresIntegrantes($id_provincia) {
		return $this->_db->faquery('getEfectoresIntegrantes', ['id_provincia' => $id_provincia], false)->getResults();
	}

	public function getEfectoresConvenio($id_provincia) {
		return $this->_db->faquery('getEfectoresConvenio', ['id_provincia' => $id_provincia], false)->getResults();
	}

	public function getImpresionesDoiuPeriodo($id_provincia, $periodo) {
		return $this->_db->faquery('getImpresionesDoiuPeriodo', ['id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getCount();
	}

	public function getVersion($id_provincia, $periodo) {
		return $this->_db->faquery('getVersion', ['id_provincia' => $id_provincia, 'periodo' => $periodo], false)->getResults();
	}

	public function insertarDddjjDoiu($array_datos) {
		return $this->_db->faquery('insertarDddjjDoiu', $array_datos, false)->getError();
		//var_dump($this->_db->faquery('insertarDddjjDoiu', $array_datos, false)->getErrorInfo());
	}

	public function insertarMotivoReimpresionDoiu($id_provincia, $periodo, $motivo) {
		return $this->_db->faquery('insertarMotivoReimpresionDoiu', ['id_provincia' => $id_provincia, 'periodo' => $periodo, 'motivo_reimpresion' => $motivo], false)->getError();
		//var_dump($this->_db->faquery('insertarMotivoReimpresionDoiu', ['id_provincia' => $id_provincia, 'periodo' => $periodo, 'motivo_reimpresion' => $motivo], false)->getErrorInfo());
	}
}
