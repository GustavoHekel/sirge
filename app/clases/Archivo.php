<?php

class Archivo {
	
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
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function listar ($id_fuente) {
		$sirge  = new Sirge();
		$params = array ($id_fuente , 0 , $_SESSION['grupo']);
		$sql 	= "
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
		return $sirge->jsonDT($this->_db->query($sql , $params)->getResults() , true);
	}
	
	public function subir($nombre_padron_array , $archivos){
		$nombre_padron_array 	= array_values ($nombre_padron_array);
		$nombre_padron 			= strtolower ($this->getTipoArchivo($nombre_padron_array[0]));
		foreach ($archivos['archivo']['name'] as $orden => $nombre) {
			$this->_size					= round (($archivos['archivo']['size'][$orden] / 1024),2);
			$this->_uniqueid				= uniqid();
			$this->_nombre_archivo_original = $archivos['archivo']['name'][$orden];
			$this->_nombre_archivo_nuevo 	= $this->_uniqueid . '.txt';
			$this->_ruta_archivo_original 	= $archivos['archivo']['tmp_name'][$orden];
			$this->_ruta_archivo_nuevo 		= '../data/upload/' . $nombre_padron . '/' . $this->_nombre_archivo_nuevo;
			$this->setIds($nombre_padron_array);
			if (
				$archivos['archivo']['error'][$orden] == 0 &&
				$this->moverSubida ($this->_ruta_archivo_original , $this->_ruta_archivo_nuevo)
			) {
				$this->_subidas[] = $nombre;
				$this->registrarSubida ($_SESSION['id_usuario'] , $this->_id_padron , $this->_size , $this->_nombre_archivo_original , $this->_nombre_archivo_nuevo);
				echo json_encode ($this->_subidas);
			}
		}
	}
	
	protected function moverSubida($nombre_original , $nombre_nuevo){
		if (move_uploaded_file ($nombre_original , $nombre_nuevo)) return true;
		else return false;
	}
	
	protected function getIdSubida($nombre_archivo){
		return $this->_db->find('id_subida' , 'sistema.subidas' , array('nombre_actual = ?' , array ($nombre_archivo)));
	}
	
	protected function getNombreArchivo($id_subida){
		return $this->_db->find('nombre_actual' , 'sistema.subidas' , array('id_subida = ?' , array ($nombre_archivo)));
	}
	
	protected function registrarSubidaOsp(){
		$this->_id_subida = $this->_db->lastId('sistema.subidas_id_subida_seq');
		$params = array (
			$this->_id_subida ,
			$this->_osp ,
			$this->_id_archivo ,
		);
		$sql = "
		INSERT INTO sistema.subidas_osp(
				id_subida, codigo_osp, id_archivo)
		VALUES (?, ?, ?);";
		$this->_db->query($sql , $params);
	}
	 
	protected function registrarSubida($id_usuario , $id_padron , $tamanio , $nombre_original , $nombre_nuevo){
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
		$this->_db->query($sql , $params);
		
		if (! is_null ($this->_osp)) {
			$this->registrarSubidaOsp();			
		}
	}
	
	protected function registrarProceso ($id_subida) {
		$params = array ($id_subida , $_SESSION['id_usuario']);
		$sql = "
			with upd1 as (
				update sistema.subidas
				set id_estado = 2
				where id_subida = ?
				returning id_subida
			) insert into sistema.subidas_aceptadas (id_subida , id_usuario)
			values ((select * from upd1),?)";
		$this->_db->query($sql , $params);
	}
	
	protected function setIds ($data = array()) {
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
	
	public function cerrar ($id_subida) {
		$data	= $this->_db->findAll('sistema.subidas' , array ('id_subida = ?' , array($id_subida)));
		$padron = $this->getTipoArchivo($data['id_padron']);
		if (rename($this->getRutaArchivo($data['id_padron'] , $data['nombre_actual']) ,  '../data/upload/' . $padron . '/back/' . $data['nombre_actual'])){
			$this->registrarProceso($id_subida);
		}
	}
	
	protected function getTipoArchivo ($id_fuente) {
		return strtolower ($this->_db->find('nombre' , 'sistema.padrones' , array ('id_padron = ?' , array($id_fuente))));
	}
	
	public function getIdArchivo($tipo_archivo){
		return $this->_db->find('id_padron' , 'sistema.padrones' , array('descripcion = ?' , array($tipo_archivo)));
	}
	
	public function borrar ($id_subida) {
		$data	= $this->_db->findAll('sistema.subidas' , array ('id_subida = ?' , array($id_subida)));
		$nombre = $data['nombre_actual'];
		$padron = $this->getTipoArchivo($data['id_padron']);
		
		if (unlink ('../data/upload/' . $padron . '/' . $nombre )) {
			$this->registraBaja($id_subida);
			echo 'Se ha eliminado el archivo ' . $data['nombre_original'];
		}
	}
	
	protected function registraBaja ($id_subida) {
		$params = array ($id_subida , $_SESSION['id_usuario']);
		$sql = "
			with upd1 as (
				update sistema.subidas set id_estado = 3 where id_subida = ?
				returning id_subida
			) insert into sistema.subidas_eliminadas (id_subida , id_usuario)
			values ((select * from upd1) , ?)";
		if (! $this->_db->query($sql , $params)->getError()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getRutaArchivo ($id_padron , $nombre) {
		return '../data/upload/' . $this->getTipoArchivo($id_padron) . '/' . $nombre;
	}
	
	public function analizar($id_subida){
		
		$iLote 		= new Lote();
		
		$id_padron 	= $this->_db->find('id_padron' , 'sistema.subidas' , array('id_subida = ?',array ($id_subida)));
		$file	 	= $this->_db->find('nombre_actual' , 'sistema.subidas' , array('id_subida = ?',array ($id_subida)));
		$clase 		= ucwords($this->getTipoArchivo($id_padron));
		$ruta 		= $this->getRutaArchivo($id_padron , $file);
		$lote 		= $iLote->crear($_SESSION['grupo'] , $_SESSION['id_usuario'] , $id_subida);

		if ($fp = fopen ($ruta , 'rb')) {
			
			$instancia = new $clase;
			$resultados = $instancia->procesar($fp , $lote);
			
			if (isset ($resultados)) {
				echo '<pre>' , print_r ($resultados) , '</pre>';
				$this->cerrar($id_subida);
			}
		}
	}
	
	public static function comparar ($encabezados , $data) {
		return count ($encabezados) != count ($data) ? false : true;
	}
}

?>