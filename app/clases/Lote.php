<?php

class Lote
{
	
	private $_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function crear($id_provincia , $id_usuario , $id_subida){
		$params = array (
			$id_subida,
			$id_usuario,
			$id_provincia
		);
		return $this->_db->fquery('crear' , $params)->get()['lote'];
	}
    
    public function cerrar($lote){
		$params = array (
			$lote,
			$_SESSION['id_usuario']
		);
		if (! $this->_db->fquery('cerrar' , $params)->getError()){
			echo 'Se ha cerrado el lote ' . $lote;
		}
	}
	
	public function eliminar($lote){
		$params = array (
			$lote,
			$_SESSION['id_usuario']
		);
		if (! $this->_db->fquery('eliminar' , $params)->getError()){
			echo 'Se ha eliminado el lote ' . $lote;
		}
	}
	
	public function completar($lote , $registros_in , $registros_out , $registros_mod){
		$params = array(
			$registros_in
			, $registros_out
			, $registros_mod
			, $lote
		);
		$this->_db->fquery('completar' , $params);
	}
	
	public function listar($id_padron){
		$sirge = new Sirge();
        switch ($_SESSION['grupo']) {
            case 25 :
                $params = array ($id_padron);
                $file = 'listarAdmin';
                break;
            default :
                $params = array ($id_padron , $_SESSION['grupo']);
                $file = 'listarUser';
                break;
        }
		return $sirge->jsonDT($this->_db->fquery($file , $params)->getResults() , true);
	}
	
	public static function getUsuarioCierre ($lote) {
		$estado = Bdd::getInstance()->find('id_estado' , 'sistema.lotes' , array('lote = ?' , array($lote)));
		switch ($estado) {
			case '1' :
                $tabla = 'sistema.lotes_aceptados l left join sistema.usuarios u on l.id_usuario = u.id_usuario';
    			break;
			case '3' : 
				$tabla = 'sistema.lotes_eliminados l left join sistema.usuarios u on l.id_usuario = u.id_usuario';
        		break;
			default : return 'PENDIENTE';
		}
		return Bdd::getInstance()->find('usuario' , $tabla , array('lote = ?' , array($lote)));
	}
	
	public static function getRechazos ($lote) {
        $tabla = 'sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida';
		$padron = Bdd::getInstance()->find('id_padron' , $tabla , array('lote = ?' , array($lote)));
		switch ($padron) {
			case 1 : $nombre_esquema = 'prestaciones'; break;
			case 2 : $nombre_esquema = 'fondos'; break;
			case 3 : $nombre_esquema = 'comprobantes'; break;
			case 4 : $nombre_esquema = 'sss'; break;
			case 5 : $nombre_esquema = 'profe'; break;
			case 6 : $nombre_esquema = 'osp'; break;
		}
		$sql = "select row_number() over() || ' - Motivo(s)-> ' || motivos || ' <br /> Registro-> ' || registro_rechazado as r from {$nombre_esquema}.rechazados where lote = ?";
		return implode ("<br />" , Bdd::getInstance()->query($sql , array($lote))->getList());
	}
}

