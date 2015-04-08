<?php

class Efectores {
		
	private $_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function getEfectoresProvincia ($id_provincia) {
      return $this->_db->fquery('getEfectoresProvincia' , [$id_provincia] , FALSE)->get()['c'];
	}
	
	public function getEfectoresCompromisoProvincia ($id_provincia) {
      return $this->_db->fquery('getEfectoresCompromisoProvincia' , [$id_provincia] , FALSE)->get()['c'];
	}
	
	public function getDescentralizacion ($id_provincia) {
      return $this->_db->fquery('getDescentralizacion' , [$id_provincia] , FALSE)->get()['d'];
	}
	
    public function listar ($post) {
      
      if (strlen ($post['search']['value'])){
        $sql = 'listar_filtrado';
        $params = [
          '%' . $post['search']['value'] . '%',
          '%' . $post['search']['value'] . '%',
          '%' . $post['search']['value'] . '%',
          $post['length'],
          $post['start'],
        ];
      } else {
        $sql = 'listar';
        $params = [
          $post['length'],
          $post['start'],
        ];
      }
      
      $data = $this->_db->fquery($sql , $params , FALSE)->getResults();
      
      foreach ($data as $key => $value) {
        $json['data'][$key] = $value;
      }
      
      $json['recordsFiltered'] = $this->_db->findCount('efectores.efectores' , ['id_estado in (?,?)' , [1,4]]);
      $json['recordsTotal'] = $this->_db->findCount('efectores.efectores' , ['id_estado in (?,?)' , [1,4]]);
      $json['draw'] = $post['draw']++;

      return (json_encode ($json));
    }
      
    public function getEfector ($id_efector) {
      return $this->_db->fquery('getEfector' , [$id_efector] , FALSE)->getResults()[0];
    }
    
    public function getEfectorGeo ($id_efector) {
      return $this->_db->fquery('getEfectorGeo' , [$id_efector] , FALSE)->getResults()[0];
    }
    
    public function getEfectorCompromiso ($id_efector) {
      $data = $this->_db->findAll('efectores.compromiso_gestion' , ['id_efector = ?' , [$id_efector]]);
      if (! $this->_db->getCount()) {
        $data['numero_compromiso'] = '-';
        $data['firmante'] = '-';
        $data['pago_indirecto'] = '-';
        $data['fecha_suscripcion'] = '-';
        $data['fecha_inicio'] = '-';
        $data['fecha_fin'] = '-';
      }
      return $data;
    }
    
    public function getEfectorConvenio ($id_efector){
      $data = $this->_db->findAll('efectores.convenio_administrativo' , ['id_efector = ?' , [$id_efector]]);
      if (! $this->_db->getCount()) {
        $data['numero_compromiso'] = '-';
        $data['firmante'] = '-';
        $data['nombre_tercer_administrador'] = '-';
        $data['codigo_tercer_administrador'] = '-';
        $data['fecha_suscripcion'] = '-';
        $data['fecha_inicio'] = '-';
        $data['fecha_fin'] = '-';
      }
      return $data;
    }
    
    public function getEfectorReferente ($id_efector){
      return $this->_db->find('nombre' , 'efectores.referentes' , ['id_efector = ?',[$id_efector]]);
    }
    
    public function getEfectorDescentralizacion ($id_efector) {
      return $this->_db->findAll('efectores.descentralizacion' , ['id_efector = ?' , [$id_efector]]);
    }
    
    public function getPrestaciones ($id_efector) {
      return $this->_db->findCount('prestaciones.prestaciones' , ['efector = (select cuie from efectores.efectores where id_efector = ?)' , [$id_efector]]);
    }
    
    public function getBeneficiariosInscriptos ($id_efector){
      $sql = "select 
  count (*) 
from 
  beneficiarios.beneficiarios_periodos
where 
  efector_asignado = (select cuie from efectores.efectores where id_efector = ?)
  and periodo = (select max (periodo) from beneficiarios.beneficiarios_periodos)";
      return $this->_db->query($sql , [$id_efector])->getResults()[0]['count'];
    }
    
    public function getPrestacionesPriorizadas ($id_efector) {
      $sql =  "
          select count (*) as c
          from 
             prestaciones.prestaciones
          where 
            efector = (select cuie from efectores.efectores where id_efector = ?)
            and codigo_prestacion in (select codigo_prestacion from pss.codigos_priorizadas)";
      return $this->_db->query($sql , [$id_efector])->getResults()[0]['c'];
    }
    
    public function getBeneficiariosCeb ($id_efector) {
      return $this->_db->fquery('getBeneficiariosCeb' , [$id_efector] , FALSE)->getResults()[0]['c'];
    }
}
