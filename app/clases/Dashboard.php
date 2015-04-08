<?php

class Dashboard
{
	
	private 
		$_db,
		$_sirge;
	
	public function __construct() {
		$this->_db = Bdd::getInstance();
		$this->_sirge = new Sirge();
	}
	
	public function cantidadPrestaciones() {
        $tabla = "sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida";
        return number_format ($this->_db->findSum('registros_in' , $tabla , array('l.id_estado = ? and id_padron = ?' , array(1,1))));
	}
	
	public function cantidadEfectores() {
        return number_format ($this->_db->findCount('efectores.efectores'));
	}
	
	public function cantidadUsuarios() {
        return number_format ($this->_db->findCount('sistema.usuarios'));
	}
	
	public function cantidadBeneficiarios() {
		return number_format ($this->_db->findCount('beneficiarios.beneficiarios'));
	}
	
	public function detalleTotales($id_total) {
		switch ($id_total) {
			case 1 : $sql = 'detalleTotales_1';
                break;
            case 2 : $sql = 'detalleTotales_2';
                break;
            case 3 : $sql = 'detalleTotales_3';
                break;
            case 4 : $sql = 'detalleTotales_4';
                break;
		}
		return $this->_sirge->jsonDT($this->_db->fquery($sql)->getResults() , true);
	}
	
	public function detallePUCO () {
		return $this->_sirge->jsonDT($this->_db->fquery('detallePuco')->getResults() , true);
	}
	
	public function insertarComentario ($comentario) {
        $campos = array('id_usuario' , 'comentario');
		$params = array( $_SESSION['id_usuario'] , $comentario);
        $this->_db->insert('sistema.comentarios' , $campos , $params);
		return $this->listadoComentarios(true);
	}
	
	public function detalleVisitas () {
		return $this->_sirge->jsonDT($this->_db->fquery('detalleVisitas')->getResults() , true);
	}
	
	public function detallePadron ($id_padron) {
		return $this->_sirge->jsonDT($this->_db->fquery('detallePadron' , array($id_padron))->getResults() , true);
	}
	
	public function porcentaje ($id_padron) {
		if ($id_padron == 4) {
			return $this->_db->fquery('porcentaje_4' , array() , FALSE)->get()['valor'];
		} else {
			return $this->_db->fquery('porcentaje' , array($id_padron) , FALSE)->get()['valor'];
		}
	}
	
	public function visitas () {
		$data = $this->_db->fquery('visitas' , array() , FALSE)->getResults();
		foreach ($data as $visitas) {
			$hits[] = $visitas['visitas'];
		}
		return implode (',' , $hits);
	}
	
	public function listadoComentarios ($ajax = false) {
		$this->_comentario = '';
		foreach ($this->_db->fquery('listadoComentarios' , array() , $ajax)->getResults() as $key) {
			$this->_comentario .= '<div  class="comentario">';
			$this->_comentario .= '<span class="nombre">' . $key['nombre'] . '</span>,' . $key['fecha_comentario'];
			$this->_comentario .= '<div class="body">' . htmlentities ($key['comentario']) . '</div>';
			$this->_comentario .= '</div>';
		}
		if ($ajax) 
			echo $this->_comentario;
		else 
			return $this->_comentario;
	}
}
