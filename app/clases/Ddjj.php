<?php

class Ddjj 
{
	private
		$_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function registrar($lotes = array()){
		$params = array ('{' . implode ("," , $lotes) . '}' , $_SESSION['grupo']);
		return $this->_db->fquery('registrar' , $params)->get()['id_impresion'];
	}
	
	public function listarPendientes($id_fuente , $actualizar = false){
      $params = array ($id_fuente , $_SESSION['grupo']);
      if ($actualizar) {
        return $this->registrar($this->_db->fquery('listarPendientes' , $params)->getList());
      } else {
        $sirge = new Sirge();
        return $sirge->jsonDT($this->_db->fquery('listarPendientes' , $params)->getResults() , true);
      }
	}
	
	public function listarImpresiones ($id_padron) {
		$sirge = new Sirge();
		$params = array ($_SESSION['grupo'] , $id_padron);
		return $sirge->jsonDT($this->_db->fquery('listarImpresiones' , $params)->getResults() , true);
	}
}
