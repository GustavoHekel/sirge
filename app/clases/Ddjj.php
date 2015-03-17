<?php

class Ddjj 
{
	private
		$_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function registrar($lotes = array()){
		$params = array ('{' . implode ("," , $lotes) . '}' , $_SESSION['grupo']);
		$sql = "
			with ins1 as (
				insert into ddjj.sirge(lote , id_provincia)
				values (?,?)
				returning id_impresion
			) select * from ins1";
		return $this->_db->query($sql , $params)->get()['id_impresion'];
	}
	
	public function listarPendientes($id_fuente , $actualizar = false){
		$params = array ($id_fuente , $_SESSION['grupo']);
		$sql = "
			select 
				lote 
				, inicio :: date as fecha
				, registros_in as insertados 
				, registros_out as rechazados 
				, registros_mod as modificados 
			from 
				sistema.lotes l left join
				sistema.subidas s on l.id_subida = s.id_subida
			where 
				lote not in (select unnest (lote) from ddjj.sirge) 
				and l.id_estado = 1 
				and id_padron = ?
				and id_provincia = ?";
		if ($actualizar) {
			return $this->registrar($this->_db->query($sql , $params)->getList());
		} else {
			$sirge = new Sirge();
			return $sirge->jsonDT($this->_db->query($sql , $params)->getResults() , true);
		}
	}
	
	public function listarImpresiones ($id_padron) {
		$sirge = new Sirge();
		$params = array ($_SESSION['grupo'] , $id_padron);
		$sql = "
			select
				id_impresion
				, fecha_impresion
				, s.lote as \"Lote(s)\"
				, '<a class=\"imprimir\" id_impresion=\"' || id_impresion || '\"><i class=\"halflings-icon print\"></i></a>' as reimprimir
			from 
				ddjj.sirge s left join
				sistema.lotes l on l.lote = any (s.lote) left join
				sistema.subidas su on l.id_subida = su.id_subida
			where 
				s.id_provincia = ?
				and id_padron = ?
				and l.id_estado = 1
			group by 1,2,3
			order by 1 desc ,2,3";
		return $sirge->jsonDT($this->_db->query($sql , $params)->getResults() , true);
	}
}

?>
