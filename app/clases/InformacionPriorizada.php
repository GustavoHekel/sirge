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

	public function getIdImpresionDdjjDoiuGen($array_assoc) {
		return $this->_db->faquery('getIdImpresionDdjjDoiuGen', $array_assoc, false)->getResults();
	}

	public function getHistorialDoiu($id_provincia) {
		return $this->_db->faquery('getHistorialDoiu', ['id_provincia' => $id_provincia], false)->getResults();
	}

	public function getHistorialConsilidadoDoiu() {

		$i       = '201310';
		$periodo = $i;
		$ano     = substr($i, 0, 4);
		$mes     = substr($i, 4, 2);
		$sql     = 'select nombre as "Provincia" ';

		while ($periodo < date('Ym')) {

			$mes++;

			if ($mes > '12') {
				$mes = '1';
				$ano++;
			}

			$periodo = $ano . str_pad($mes, 2, '0', STR_PAD_LEFT);
			//echo $periodo , '<br />';

			$sql = "
			select descripcion as \"Provincia\" , case
				when exists (
					select periodo_reportado
					from ddjj.doiu9 d
					where
						d.id_provincia = p.id_entidad
						and periodo_reportado = '" . $ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . "'
					)
				then 'X'
				else ''
			end as \"" . $ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . "\"";
		}

		$sql .= ' from sistema.entidades p order by id_entidad';

		return $this->_db->query($sql, [], false)->getResults();
		//var_dump($this->_db->query($sql, [], false)->getErrorInfo());

	}
}
