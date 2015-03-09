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
		$_id_padron ,
		$_id_subida ,
		$_id_archivo ,
		$_osp = null;
	
	public function __construct () {
		$this->_db = BDD::GetInstance();
	}
	
	/**
	 * 
	 * FUNCIONES DE MANEJO DE ARCHIVOS EN LA BASE DE DATOS Y DIRECTORIOS
	 *
	 **/
	
	protected function MueveArchivo ($nombre_original , $nombre_nuevo) {
		if (move_uploaded_file ($nombre_original , $nombre_nuevo)) return true;
		else return false;
	}
	
	protected function GetIDSubida ($nombre_archivo) {
		$params = array ($nombre_archivo);
		$sql 	= "select id_subida from sistema.subidas where nombre_actual = ?";
		return $this->_db->Query($sql , $params)->GetRow()['id_subida'];
	}
	
	protected function GetNombreArchivo ($id_subida) {
		$params = array ($id_subida);
		$sql 	= "select nombre_actual from sistema.subidas where id_subida = ?";
		return BDD::GetInstance()->Query($sql , $params)->GetRow()['nombre_actual'];
	}
	
	protected function RegistraSubidaOSP () {
		$this->_id_subida = BDD::GetInstance()->Query("select currval ('sistema.subidas_id_subida_seq')")->GetRow()['currval'];
		$params = array (
			$this->_id_subida ,
			$this->_osp ,
			$this->_id_archivo ,
			$this->_nombre_archivo_nuevo
		);
		$sql = "
		INSERT INTO sistema.subidas_osp(
				id_subida, codigo_osp, id_archivo, nombre_backup)
		VALUES (?, ?, ?, ?);";
		BDD::GetInstance()->Query($sql , $params);
	}
	 
	protected function RegistraSubida ($id_usuario , $id_padron , $tamanio , $nombre_original , $nombre_nuevo) {
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
		
		if (! is_null ($this->_osp)) {
			$this->RegistraSubidaOSP();			
		}
		
	}
	
	protected function RegistraProceso ($id_subida) {
		$params_1 = array ($id_subida);
		$sql_1 = "update sistema.subidas set id_estado = 2 where id_subida = ?";
		BDD::GetInstance()->Query($sql_1 , $params_1);
		
		$params_2 = array ($id_subida , $_SESSION['id_usuario']);
		$sql_2 = "insert into sistema.subidas_aceptadas (id_subida , id_usuario) values (?,?)";
		BDD::GetInstance()->Query($sql_2 , $params_2);
	}
	
	protected function RegistraBaja ($id_subida) {
		$params_1 = array ($id_subida);
		$sql_1 = "update sistema.subidas set id_estado = 3 where id_subida = ?";
		$this->_db->Query($sql_1 , $params_1);
		
		$params_2 = array ($id_subida , $_SESSION['id_usuario']);
		$sql_2 = "insert into sistema.subidas_eliminadas (id_subida , id_usuario) values (?,?)";
		$this->_db->Query($sql_2 , $params_2);
	}
	
	protected function GetData ($id_subida) {
		$params = array ($id_subida);
		$sql 	= "select * from sistema.subidas where id_subida = ?";
		return BDD::GetInstance()->Query($sql , $params)->GetRow();
	}
	
	public function Cierre ($id_subida) {

		$data 	= $this->GetData($id_subida);
		$nombre = $data['nombre_actual'];
		$padron = strtolower($this->GetNombrePadron($data['id_padron']));
		
		if (rename('../data/upload/' . $padron . '/' . $nombre , '../data/upload/' . $padron . '/back/' . $nombre)) {
			$this->RegistraProceso($id_subida);
		} else {
			echo '../data/upload/' . $padron . '/' . $nombre;
			echo '../data/upload/' . $padron . '/back/' . $nombre;
		}
		
	}
	
	public function Baja ($id_subida) {
		$data 	= $this->GetData($id_subida);
		$nombre = $data['nombre_actual'];
		$padron = strtolower($this->GetNombrePadron($data['id_padron']));
		
		if (unlink ('../data/upload/' . $padron . '/' . $nombre )) {
			$this->RegistraBaja($id_subida);
			echo 'Se ha eliminado el archivo ' . $data['nombre_original'];
		}
	}
	
	protected function InicializarDatosOSP ($data = array()) {
		
		$this->_id_padron = $data[0];
		
		if ($this->_id_padron == 6) {
			$this->_osp	= $data[1];
			if (isset ($data[2])) {
				$this->_id_archivo = $data[2];
			} else {
				$this->_id_archivo = 1;
			}
		}
	}
	
	public function Sube ($nombre_padron_array , $archivos) {
		
		$SIRGe = new SIRGe();
		
		$nombre_padron_array 	= array_values ($nombre_padron_array);
		$nombre_padron 			= strtolower ($SIRGe->GetNombrePadron($nombre_padron_array[0]));
		
		foreach ($archivos['archivo']['name'] as $orden => $nombre) {
			
			$this->_size					= round (($archivos['archivo']['size'][$orden] / 1024),2);
			$this->_uniqueid				= uniqid();
			$this->_nombre_archivo_original = $archivos['archivo']['name'][$orden];
			$this->_nombre_archivo_nuevo 	= $this->_uniqueid . '.txt';
			$this->_ruta_archivo_original 	= $archivos['archivo']['tmp_name'][$orden];
			$this->_ruta_archivo_nuevo 		= '../data/upload/' . $nombre_padron . '/' . $this->_nombre_archivo_nuevo;
			$this->InicializarDatosOSP($nombre_padron_array);
			
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
