<?php

class Archivos extends SIRGe {
	
	private 
		$_db ,
		$_nombre_archivo_original ,
		$_nombre_archivo_nuevo ,
		$_ruta_archivo_original ,
		$_ruta_archivo_nuevo ,
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
	
	private function MueveArchivo ($nombre_original , $nombre_nuevo) {
		if (move_uploaded_file ($nombre_original , $nombre_nuevo)) return true;
		else return false;
	}
	
	private function GetIDSubida ($nombre_archivo) {
		$params = array ($nombre_archivo);
		$sql = "select id_subida from sistema.subidas where nombre_actual = ?";
		return $this->_db->Query($sql , $params)->GetRow()['id_subida'];
	}
	 
	private function RegistraSubida ($id_usuario , $id_padron , $tamanio , $nombre_original , $nombre_nuevo) {
		$params = array (
			$id_usuario
			, $id_padron
			, $tamanio
			, trim ($nombre_original)
			, $nombre_nuevo
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
	
	protected function RegistraBaja ($id_subida) {
		
		$sql_1 = "update sistema.subidas set id_estado = 3 where id_subida = '$id_subida'";
		$this->_db->Query($sql_1);
		
		$params_2 = array (
			$id_subida
			, $_SESSION['id_usuario']
		);
		
		$sql_2 = "insert into sistema.subidas_eliminadas (id_subida , id_usuario) values (?,?)";
		$this->_db->Query($sql_2 , $params_2);
	
	}
	
	public function Baja ($id_subida) {
		
		$SIRGe 	= new SIRGe();
		
		$params = array ($id_subida);
		$sql 	= "select * from sistema.subidas where id_subida = ?";
		$data 	= $this->_db->Query($sql , $params)->GetRow();
		
		$nombre = $data['nombre_actual'];
		$padron = strtolower($SIRGe->GetNombrePadron($data['id_padron']));
		
		if (unlink ('../data/upload/' . $padron . '/' . $nombre . '')) {
			$this->RegistraBaja($id_subida);
		}
		
	}
	
	public function Sube ($nombre_padron_array , $archivos) {
		
		$SIRGe = new SIRGe();
		
		$nombre_padron_array 	= array_values ($nombre_padron_array);
		$nombre_padron 			= strtolower ($SIRGe->GetNombrePadron($nombre_padron_array[0]));
		
		foreach ($archivos['archivo']['name'] as $orden => $nombre) {
			
			$this->_size					= round (($archivos['archivo']['size'][$orden] / 1024),2);
			$this->_uniqueid				= date("YmdHis");
			$this->_nombre_archivo_original = $archivos['archivo']['name'][$orden];
			$this->_nombre_archivo_nuevo 	= $this->_uniqueid . '.txt';
			$this->_ruta_archivo_original 	= $archivos['archivo']['tmp_name'][$orden];
			$this->_ruta_archivo_nuevo 		= '../data/upload/' . $nombre_padron . '/' . $this->_nombre_archivo_nuevo;
			$this->_id_padron				= $nombre_padron_array[0];
			
			if (
				$archivos['archivo']['error'][$orden] == 0 &&
				$this->MueveArchivo ($this->_ruta_archivo_original , $this->_ruta_archivo_nuevo)
			) {
				$this->_subidas[] = $nombre;
				$this->RegistraSubida ($_SESSION['id_usuario'] , $this->_id_padron , $this->_size , $this->_nombre_archivo_original , $this->_nombre_archivo_nuevo);
				echo json_encode ($this->_subidas);
			}
		}
	}
	
	
	public function ListadoSubidas ($id_fuente) {
		
		$params = array (
			$id_fuente ,
			0 ,
			$_SESSION['grupo']
		);
		
		$sql = "
			select 
				c.nombre_original as nombre
				, c.fecha_subida as fecha_subida
				, round ((size / 1024) :: numeric , 2) || ' MB' as \"tamaño\"
				, '<a file=\"' || id_subida || '\" href=\"#\" class=\"procesar\"><i class=\"halflings-icon hdd\"></i></a>' as procesar
				, '<a file=\"' || id_subida || '\" href=\"#\" class=\"eliminar\"><i class=\"halflings-icon trash\"></a></i>' as eliminar
			from 
				sistema.subidas c left join 
				sistema.usuarios u on c.id_usuario = u.id_usuario
			where 
				id_padron = ?
				and id_estado = ?
				and id_entidad = ?";
		
		return $this->JSONDT($this->_db->Query($sql , $params)->GetResults() , true);
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
