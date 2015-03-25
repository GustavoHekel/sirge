<?php

class Usuarios {
	
	private
		$_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function login ($usuario , $clave) {
		$params = array($usuario , md5 ($clave));
		$usuario = $this->_db->findAll('sistema.usuarios' , array('usuario = ? and password = ?', $params));
		if ($this->_db->getCount()) {
			$_SESSION['grupo'] 			= $usuario['id_entidad'];
			$_SESSION['id_menu']		= $usuario['id_menu'];
			$_SESSION['descripcion'] 	= $usuario['descripcion'];
			$_SESSION['id_usuario'] 	= $usuario['id_usuario'];
			
			$params = array ($_SESSION['id_usuario'] , $_SERVER['REMOTE_ADDR']);
			$sql = "insert into logs.log_logins (id_usuario , ip) values (?,?)";
			$this->_db->query($sql , $params);
			
			echo json_encode(true);
		} else {
			echo json_encode(false);;
		}
	}
	
}

?>
