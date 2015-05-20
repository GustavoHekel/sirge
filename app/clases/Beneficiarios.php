<?php

class Beneficiarios
{
	
	private
		$_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	private function getUltimoPeriodo () {
		$sql = 'select max (periodo) as p from beneficiarios.beneficiarios_periodos';
		return $this->_db->query($sql)->get()['p'];
	}
	
	public function habitantes ($id_provincia) {
		$params = array ($id_provincia);
		$sql = "
			select
				to_char (habitantes , '999,999,999') as h
			from
				geo.poblacion g
			where
				id_provincia = ?";
		return $this->_db->query($sql , $params)->get()['h'];
	}
	
	public function resumen ($id_provincia , $campo) {
		$periodo = $this->getUltimoPeriodo();
		$params = array($id_provincia , $periodo);
		$sql = "
			select
				to_char ({$campo} , '99,999,999') as c
			from
				beneficiarios.resumen_beneficiarios
			where
				id_provincia = ?
				and periodo = ?";
		return $this->_db->query($sql , $params)->get()['c'];	
	}
	
	public function matriz ($dni) {
		$data 		= $this->_db->fquery('matriz', [$dni , $dni] , false)->getResults();
		$periodos 	= array();
		$info 		= array();
		foreach ($data as $clave => $valor) {
			$periodos[$valor['periodo']] = $valor['cantidad'];
		}
		for ($i = 1 ; $i <= 12 ; $i ++) {
			for ($j = 2004 ; $j <= date('Y') ; $j ++) {
				$info[] = ($i-1) . ',' . ($j-2004) . ',' . $periodos[($j . str_pad($i , 2 , '0' , STR_PAD_LEFT))];
			}
		}
		return '[' . implode('],[' ,$info) . ']';
	}
    
    public function getDataBeneficiarioDNI ($dni){
        return $this->_db->fquery('getDataBeneficiarioDNI' , [$dni] , false)->getResults()[0];
    }
    
    public function listar ($post) {
      if (strlen ($post['search']['value'])){
        $sql = 'listar_filtrado';
        $params = ['%' . $post['search']['value'] . '%' , '%' . $post['search']['value'] . '%' , '%' . $post['search']['value'] . '%' , $post['length'] , $post['start']];
      } else {
        $sql = 'listar';
        $params = [$post['length'] , $post['start']];
      }
      $data = $this->_db->fquery($sql , $params , FALSE)->getResults();
      foreach ($data as $key => $value) {
        $json['data'][$key] = $value;
      }
      $json['recordsFiltered'] = $this->_db->findCount('beneficiarios.beneficiarios' , ['id_provincia_alta in (?,?)' , ['01','24']]);
      $json['recordsTotal'] = $this->_db->findCount('beneficiarios.beneficiarios' , ['id_provincia_alta in (?,?)' , ['01','24']]);
      $json['draw'] = $post['draw']++;
      return (json_encode ($json));
    }
}
