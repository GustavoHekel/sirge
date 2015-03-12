<?php

class Padron extends Archivos {
	
	private 
		$_db ,
		$_lote;
	
	public function __construct () {
		$this->_db = Bdd::getInstance();
	}
	
	protected function comparaCampos ($encabezados , $data) {
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
	
	public function ListadoDDJJ ($id_padron) {
		
		$params = array ($_SESSION['grupo'] , $id_padron);
		$sql = "
			select
				id_impresion
				, fecha_impresion
				, s.lote as \"Lote(s)\"
				, '<a class=\"imprimir\" id_impresion=\"' || id_impresion || '\"><i class=\"halflings-icon print\"></i></a>' as reimprimir
			from 
				ddjj.sirge s left join
				sistema.lotes l on l.lote = any (s.lote)
			where 
				s.id_provincia = ?
				and id_padron = ?
				and id_estado = 1
			group by 1,2,3
			order by 1 desc ,2,3";
		
		return $this->JSONDT($this->_db->Query($sql , $params)->GetResults() , true);
		
		
	}
	
	protected function _IngresaError ($sql , $params , $error) {
			
		$this->_db->query($sql , $params);
	}
	
	
	private function devolverRutaArchivo ($id_padron , $nombre) {
		return '../data/upload/' . $this->getNombrePadron($id_padron) . '/' . $nombre;
	}
	
	private function analizaPadron($id_padron , $id_subida , $lote) {
		$clase 		= $this->getNombrePadron($id_padron);
		$instancia 	= new $clase;
		$instancia->procesaRegistro($id_subida , $lote);
	}
	
	public function procesaPadron ($id_subida) {
		$data 			= $this->_db->select('sistema.subidas' , array ('id_subida' , '=' , $id_subida));
		$this->_lote	= $this->NuevoLote($_SESSION['grupo'] , $_SESSION['id_usuario'] , $data['id_padron'] , $id_subida);
		$this->analizaPadron($data['id_padron'] , $id_subida , $this->_lote);
	}
	
}

?>
