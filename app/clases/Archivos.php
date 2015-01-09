<?php

class Archivos {
	
	private 
		$_db ,
		$_nombre_archivo_original ,
		$_nombre_archivo_nuevo ,
		$_uniqueid ,
		$_subidas = array() ,
		$_size ,
		$_id_padron;
	
	public function __construct () {
		
		$this->_db = BDD::GetInstance();
	}
	
	/**
	 * 
	 * FUNCIONES DE MANEJO DE ARCHIVOS EN LA BASE DE DATOS Y DIRECTORIOS
	 *
	 **/
	
	protected function Sube ($archivos , $nombre_padron) {
		
		foreach ($archivos['archivo']['name'] as $orden => $nombre) {
			
			$this->_size					= round (($archivos['archivo']['size'][$orden] / 1024),2);
			$this->_uniqueid				= date("YmdHis");
			$this->_nombre_archivo_original = $archivos['archivo']['tmp_name'][$orden];
			$this->_nombre_archivo_nuevo 	= '../../../upload/' . $nombre_padron . '/' . $this->_uniqueid . '.txt';
			$this->_id_padron				= $this->GetNombrePadron ($nombre_padron);
			
			if (
				$archivos['archivo']['error'][$orden] == 0 &&
				$this->MueveArchivo ($this->_nombre_archivo_original , $this->_nombre_archivo_nuevo)
			) {
				$this->_subidas[] = $nombre;
				$this->RegistraSubida ($_SESSION['id_usuario'] , $this->_id_padron , $this->_size , $this->_nombre_archivo_original , $this->_nombre_archivo_nuevo);
				return json_encode ($subidas);
			}
		}
	}
	
	private function RegistraSubida ($id_usuario , $id_padron , $tamanio , $nombre_original , $nombre_nuevo) {
		
		$params = array (
			$id_usuario
			, $id_padron
			, $tamanio
			, trim ($nombre_original)
			, $nombre_actual
		);
		
		$sql = "
			insert into sistema.subidas (id_usuario , id_padron , size , nombre_original , nombre_actual)
			values (?,?,?,?,?) ";
			
		$this->_db->Query($sql , $params);

	}
	
	protected function RegistraCierre ($nombre_archivo) {
		
		$sql = "update sistema.subidas set id_estado = 1 where nombre_actual = '$nombre_archivo'";
		$this->_db->Query($sql);
	
	}
	
	protected function RegistraProceso ($nombre_archivo , $id_usuario) {
		
		$id_subida;
		
		$params_1 = array (
			$nombre_archivo
		);
		
		$sql_1 = "
			update sistema.subidas set id_estado = 2 where nombre_actual = '$nombre_archivo';
			select id_subida from sistema.subidas where nombre_actual = ?";
		$id_subida = $this->_db->Query($sql_1 , $params_1)->GetRow();
		
		$params_2 = array (
			$id_subida
			, $id_usuario
		);
		
		$sql_2 = "insert into sistema.subidas_aceptadas (id_subida , id_usuario) values (?,?)";
		$this->_db->Query($sql_2 , $params_2);
		
	}
	
	protected function RegistraBaja ($nombre_archivo , $id_usuario) {
		
		$id_subida;
		
		$params_1 = array (
			$nombre_archivo
		);
		
		$sql_1 = "
			update sistema.subidas set id_estado = 3 where nombre_actual = '$nombre_archivo';
			select id_subida from sistema.subidas where nombre_actual = ?";
		$id_subida = $this->_db->Query($sql_1 , $params_1)->GetRow();
		
		$params_2 = array (
			$id_subida
			, $id_usuario
		);
		
		$sql_2 = "insert into sistema.subidas_eliminadas (id_subida , id_usuario) values (?,?)";
		$this->_db->Query($sql_2 , $params_2);
	
	}
	
	private function GetNombrePadron ($nombre_padron) {
		
		$id_padron;
		
		switch ($nombre_padron) {
			case 'prestaciones' : $id_padron = 1; break;
			case 'comprobantes' : $id_padron = 3; break;
			case 'fondos' 		: $id_padron = 2; break;
			case 'osp' 			: $id_padron = 6; break;
			default				: die ("PADRON NO ENCONTRADO");
		}
		
		return $id_padron;
		
	}
	
	private function MueveArchivo ($nombre_original , $nombre_nuevo) {
	
		move_uploaded_file ($nombre_original , $nombre_nuevo);
		
	}
	
	private function GetIDSubida ($nombre_archivo) {
		
		$params = array (
			$nombre_archivo
		);
		
		$sql = "select id_subida from sistema.subidas where nombre_actual = ?";
		
		return $this->_db->Query($sql , $params)->GetRow();
		
	}
	
	/**
	 * 
	 * METODOS PARA MANEJO DE ARCHIVOS
	 * 
	 **/
	
	protected function TotalRegistros ($nombre_archivo) {
		
		return count (file ($nombre_archivo));
	}
	
	protected function GetLinea ($nombre_archivo) {
		
		return explode (";" , trim (fgets ($nombre_archivo) , "\r\n"));
	}
	
}

?>
