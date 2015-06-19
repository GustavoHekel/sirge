<?php
class Efectores {
	private $_db, $_efector = ['cuie', 'siisa', 'nombre', 'domicilio', 'codigo_postal', 'tipo_efector', 'rural', 'cics', 'categorizacion', 'dependencia_adm', 'dependencia_san', 'integrante', 'compromiso', 'alto_impacto', 'referente', 'numero_compromiso', 'firmante_compromiso', 'fecha_sus_cg', 'fecha_ini_cg', 'fecha_fin_cg', 'pago_indirecto', 'numero_convenio', 'firmante_convenio', 'fecha_sus_ca', 'fecha_ini_ca', 'fecha_fin_ca', 'nombre_adm', 'codigo_adm', 'provincia', 'ciudad', 'departamento', 'localidad', 'email', 'email_observaciones', 'telefono', 'telefono_observaciones'];
	public

	function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public

	function getEfectoresProvincia($id_provincia) {
		return $this->_db->fquery('getEfectoresProvincia', [$id_provincia], FALSE)->get()['c'];
	}

	public

	function getEfectoresCompromisoProvincia($id_provincia) {
		return $this->_db->fquery('getEfectoresCompromisoProvincia', [$id_provincia], FALSE)->get()['c'];
	}

	public

	function getDescentralizacion($id_provincia) {
		return $this->_db->fquery('getDescentralizacion', [$id_provincia], FALSE)->get()['d'];
	}

	public

	function listar($post) {
		if (strlen($post['search']['value'])) {
			$sql    = 'listar_filtrado';
			$params = ['%' . $post['search']['value'] . '%', '%' . $post['search']['value'] . '%', '%' . $post['search']['value'] . '%', $post['length'], $post['start']];
		} else {
			$sql    = 'listar';
			$params = [$post['length'], $post['start']];
		}

		$data = $this->_db->fquery($sql, $params, FALSE)->getResults();
		foreach ($data as $key => $value) {
			$json['data'][$key] = $value;
		}

		$json['recordsFiltered'] = $this->_db->findCount('efectores.efectores', ['id_estado in (?,?)', [1, 4]]);
		$json['recordsTotal']    = $this->_db->findCount('efectores.efectores', ['id_estado in (?,?)', [1, 4]]);
		$json['draw']            = $post['draw']++;
		return (json_encode($json));
	}

	public

	function getEfector($id_efector) {
		return $this->_db->fquery('getEfector', [$id_efector], FALSE)->getResults()[0];
	}

	public

	function getEfectorGeo($id_efector) {
		return $this->_db->fquery('getEfectorGeo', [$id_efector], FALSE)->getResults()[0];
	}

	public

	function getEfectorCompromiso($id_efector) {
		$data = $this->_db->findAll('efectores.compromiso_gestion', ['id_efector = ?', [$id_efector]]);
		if (!$this->_db->getCount()) {
			$data['numero_compromiso'] = '-';
			$data['firmante']          = '-';
			$data['pago_indirecto']    = '-';
			$data['fecha_suscripcion'] = '-';
			$data['fecha_inicio']      = '-';
			$data['fecha_fin']         = '-';
		}

		return $data;
	}

	public

	function getEfectorConvenio($id_efector) {
		$data = $this->_db->findAll('efectores.convenio_administrativo', ['id_efector = ?', [$id_efector]]);
		if (!$this->_db->getCount()) {
			$data['numero_compromiso']           = '-';
			$data['firmante']                    = '-';
			$data['nombre_tercer_administrador'] = '-';
			$data['codigo_tercer_administrador'] = '-';
			$data['fecha_suscripcion']           = '-';
			$data['fecha_inicio']                = '-';
			$data['fecha_fin']                   = '-';
		}

		return $data;
	}

	public

	function getEfectorReferente($id_efector) {
		return $this->_db->find('nombre', 'efectores.referentes', ['id_efector = ?', [$id_efector]]);
	}

	public

	function getEfectorDescentralizacion($id_efector) {
		return $this->_db->findAll('efectores.descentralizacion', ['id_efector = ?', [$id_efector]]);
	}

	public

	function getPrestaciones($id_efector) {
		return $this->_db->findCount('prestaciones.prestaciones', ['efector = (select cuie from efectores.efectores where id_efector = ?)', [$id_efector]]);
	}

	public

	function getBeneficiariosInscriptos($id_efector) {
		$sql = "select
  count (*)
from
  beneficiarios.beneficiarios_periodos
where
  efector_asignado = (select cuie from efectores.efectores where id_efector = ?)
  and periodo = (select max (periodo) from beneficiarios.beneficiarios_periodos)";
		return $this->_db->query($sql, [$id_efector])->getResults()[0]['count'];
	}

	public

	function getPrestacionesPriorizadas($id_efector) {
		$sql = "
          select count (*) as c
          from
             prestaciones.prestaciones
          where
            efector = (select cuie from efectores.efectores where id_efector = ?)
            and codigo_prestacion in (select codigo_prestacion from pss.codigos_priorizadas)";
		return $this->_db->query($sql, [$id_efector])->getResults()[0]['c'];
	}

	public

	function getBeneficiariosCeb($id_efector) {
		return $this->_db->fquery('getBeneficiariosCeb', [$id_efector], FALSE)->getResults()[0]['c'];
	}

	public

	function descargarTabla() {
		$file        = 'efectores.csv';
		$ruta        = '../data/padrones/' . $file;
		$data        = $this->_db->fquery('descargarTabla')->getResults();
		$encabezados = array_keys($data[0]);
		unlink($ruta);
		file_put_contents($ruta, implode("\t", $encabezados) . "\r\n", FILE_APPEND);
		foreach ($data as $index => $valor) {
			file_put_contents($ruta, html_entity_decode(implode("\t", $valor), ENT_QUOTES, 'UTF-8') . "\r\n", FILE_APPEND);
		}
	}

	public

	function efectorJson($busqueda) {
		$params = [$busqueda . '%', $busqueda . '%'];
		echo json_encode($this->_db->fquery('efectorJson', $params, TRUE)->getList());
	}

	public

	function getInfoBaja($cuie) {
		return $this->_db->findAll('efectores.efectores', ['cuie = ?', [$cuie]]);
	}

	public

	function baja($cuie) {
		$sql    = "update efectores.efectores set id_estado = 3 where cuie = ?";
		$params = [$cuie];
		if (!$this->_db->query($sql, $params)->getError()) {
			echo 'Se ha soliciado la baja del efector ' . $cuie . ', estar&aacute; a revisi&oacute;n del &aacute;rea C&aacute;pitas.';
		} else {
			echo 'Ha ocurrido un error.';
		}
	}

	public

	function getSiisa($jurisdiccion) {
		$sql = "
        select '99999999' || '{$jurisdiccion}' || lpad ((max (substring (siisa from 11 for 4)) :: numeric + 1 ) :: varchar , 4 , '0') as siisa
        from
          efectores.efectores
        where
          substring (siisa from 1 for 8) = '99999999'
          and substring (siisa from 9 for 2) = ?";
		$this->_db->query($sql, [$jurisdiccion]);
		if ($this->_db->getCount()) {
			echo $this->_db->get()['siisa'];
		} else {
			echo '99999999' . $jurisdiccion . '0001';
		}
	}

	public

	function selectTipoEfector() {
		$sql    = "select * from efectores.tipo_efector where id_tipo_efector <> 9";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value) {
			$select .= "<option value='{$value['id_tipo_efector']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public

	function selectCategorizacion() {
		$sql    = "select * from efectores.tipo_categorizacion where id_categorizacion <> 10";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value) {
			$select .= "<option value='{$value['id_categorizacion']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public

	function selectDependencia() {
		$sql    = "select * from efectores.tipo_dependencia_administrativa where id_dependencia_administrativa <> 5";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value) {
			$select .= "<option value='{$value['id_dependencia_administrativa']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public

	function alta() {
		$data = (array_combine($this->_efector, func_get_args()));
	}

	public function getSugerenciaEfectores($efector) {
		$sql = "select cuie from efectores.efectores where cuie ilike '$efector%' OR siisa ilike '$efector%' limit 10";
		return $this->_db->query($sql)->getResults();
	}

}
