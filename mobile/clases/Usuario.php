<?php

class Usuario {
	
	private 
		$_db ,
		$_user = [
			'id_usuario' => 0
		];
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function login ($callback , $email , $pass){
		$sql = "select id_usuario from mobile.usuarios where email = ? and pass = ?";
		$params = [$email , md5($pass)];
		$this->_user['id_usuario'] = $this->_db->query($sql , $params)->get()['id_usuario'];
		echo $callback . '(' . json_encode($this->_user) . ')';
	}
	
	public function getNombreApellido ($id){
		$sql = "select apellido || ', ' || nombre as n from mobile.usuarios where id_usuario = ?";
		return $this->_db->query($sql , [$id])->get()['n'];
	}
	
	public function getEdad ($id){
		$sql = "select age(LOCALTIMESTAMP , fecha_nacimiento) as edad from mobile.usuarios where id_usuario = ?";
		return $this->_db->query($sql , [$id])->get()['edad'];
	}
	
	public function getTipoNumero ($id){
		$sql = "select tipo_documento || ' : ' || numero_documento as tipo_num from mobile.usuarios where id_usuario = ?";
		return $this->_db->query($sql , [$id])->get()['tipo_num'];
	}

}

