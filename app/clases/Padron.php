<?php

class Padron extends Archivo {
	
	private 
		$_db ,
		$_lote;
	
	public function __construct () {
		$this->_db = Bdd::getInstance();
	}
	
	public function comparar ($encabezados , $data) {
		return count ($encabezados) != count ($data) ? false : true;
	}
	
	
	public static function GetUsuarioCierre ($lote) {
		
		$params = array ($lote);
		$sql 	= "select id_estado from sistema.lotes where lote = ?";
		$estado = $this->_db->Query($sql , $params)->GetRow()['id_estado'];
		
		switch ($estado) {
			case '1' :
				$sql = "
					select	
						usuario
					from
						sistema.lotes_aceptados l left join
						sistema.usuarios u on l.id_usuario = u.id_usuario
					where
						lote = ?";
			break;
			case '3' : 
				$sql = "
					select	
						usuario
					from
						sistema.lotes_eliminados l left join
						sistema.usuarios u on l.id_usuario = u.id_usuario
					where
						lote = ?";
			break;
			default : return 'PENDIENTE'; break;
		}
		return $this->_db->Query($sql , $params)->GetRow()['usuario'];
	}
	
	public static function GetRechazos ($lote) {
		
		$params = array ($lote);
		$sql 	= "select id_padron from sistema.lotes where lote = ?";
		$padron	= $this->_db->Query($sql , $params)->GetRow()['id_padron'];
		
		switch ($padron) {
			case 1 : $nombre_esquema = 'prestaciones'; break;
			case 2 : $nombre_esquema = 'aplicacion_fondos'; break;
			case 3 : $nombre_esquema = 'comprobantes'; break;
		}
		
		$sql = "select row_number() over() || ' - Motivo(s)-> ' || motivos || ' <br /> Registro-> ' || registro_rechazado as r from {$nombre_esquema}.rechazados where lote = ?";
		
		return implode ("<br />" , $this->_db->Query($sql , $params)->GetList());
		
	}
	
	public function getIdPadron ($nombre_padron) {
		$id_padron;
		switch ($nombre_padron) {
			case 'prestaciones' : $id_padron = 1; break;
			case 'comprobantes' : $id_padron = 3; break;
			case 'fondos' 		: $id_padron = 2; break;
			case 'sss'			: $id_padron = 4;break;
			case 'profe'		: $id_padron = 5;break;
			case 'osp' 			: $id_padron = 6; break;
			default				: die ("PADRON NO ENCONTRADO");
		}
		return $id_padron;
	}
	
	protected function _IngresaError ($sql , $params , $error) {
			
		$this->_db->query($sql , $params);
	}
	
	public function analizar($id_subida){
		
		$archivo 	= new Archivo();
		$lote 		= new Lote();
		
		$id_padron 	= $this->_db->getField('sistema.subidas' , 'id_padron' , array('id_subida','=',$id_subida));
		$file 		= $this->_db->getField('sistema.subidas' , 'nombre_actual' , array('id_subida','=',$id_subida));
		$clase 		= $archivo->getTipoArchivo($id_padron);
		$ruta 		= $archivo->getRutaArchivo($id_padron , $file);
		$lote 		= $lote->crear($_SESSION['grupo'] , $_SESSION['id_usuario'] , $id_subida);
		
		if ($fp = fopen ($ruta , 'rb') {
			$instancia = new $clase;
			if ($instancia->procesar($fp , $lote)){
				$archivo->cerrar($id_subida);
			}
		}
		
	}
	
}

?>
