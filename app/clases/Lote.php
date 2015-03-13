<?php

class Lote
{
	
	private
		$_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function crear($id_provincia , $id_usuario , $id_subida){
		$params = array (
			$id_subida,
			$id_usuario,
			$id_provincia
		);
		$sql = "
			with ins1 as (
				insert into sistema.lotes (id_subida , id_usuario , id_provincia)
				values (? , ? , ?)
				returning lote , id_usuario
			) select * from ins2";
		return $this->_db->query($sql , $params)->getRow()['lote'];
	}
	
	public function cerrar($lote){
		$params = array (
			$lote,
			$_SESSION['id_usuario']
		);
		$sql = "
			with upd1 as (
				update sistema.lotes 
				set id_estado = 1 
				where lote = ?
				returning lote
			) insert into sistema.lotes_aceptados (lote , id_usuario) 
			values ((select * from upd1) , ?)";
		if (! $this->_db->query($sql , $params)->getError()){
			echo 'Se ha cerrado el lote ' . $lote;
		}
	}
	
	public function eliminar($lote){
		$params = array (
			$lote,
			$_SESSION['id_usuario']
		);
		$sql = "
			with upd1 as (
				update sistema.lotes set id_estado = 1 where lote = 5159
				returning lote
			) insert into sistema.lotes_eliminados (lote , id_usuario) 
			values ((select * from upd1) , ?)";
		if (! $this->_db->query($sql , $params)->getError()){
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
		$sql = "
			update sistema.lotes
			set
				registros_in = ?
				, registros_out = ?
				, registros_mod = ?
				, fin = localtimestamp
			where 
				lote = ?";
		$this->_db->query($sql , $params);
	}
	
	public function mostrarPendientesDdjj($id_fuente , $aux = false){
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
				and id_estado = 1 
				and id_padron = ?
				and id_provincia = ?";
		if (! $aux) {
			$sirge = new Sirge();
			return $sirge->jsonDT($this->_db->query($sql , $params)->getResults() , true);
		} else {
			return $this->registrarDdjj($this->_db->query($sql , $params)->getList());
		}
	}
	
	private function registrarDdjj($lotes = array()){
		$params = array ('{' . implode ("," , $lotes) . '}' , $_SESSION['grupo']);
		$sql = "
			with ins1 as (
				insert into ddjj.sirge(lote , id_provincia)
				values (?,?)
				returning id_impresion
			) select * from ins1";
		return $this->_db->query($sql , $params)->getRow()['id_impresion'];
	}
	
	public function listarImpresionesDdjj ($id_padron) {
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
	
	public function listar($id_padron){
		$sirge = new Sirge();
		$params = array ($id_padron);
		$sql = "
			select 
				'<span class=\"row-details row-details-close\"></span>' as _
				, lote
				, inicio :: date as fecha
				, case
					when e.id_estado = 3 then '<span class=\"label label-info\">'|| e.descripcion ||'</span>' 
					when e.id_estado = 2 then '<span class=\"label label-warning\">'|| e.descripcion ||'</span>' 
					when e.id_estado = 1 then '<span class=\"label label-success\">'|| e.descripcion ||'</span>'
				end as estado
				, case when exists (select 1 from ddjj.sirge i where i.lote @> array[l.lote]) 
					then '<span class=\"label label-success\">IMPRESA</span>'
					else
						case when e.id_estado = 3 
						then '<span class=\"label label-info\">ELIMINADO</span>' 
						else '<span class=\"label label-warning\">PENDIENTE</span>' 
					end
				end as \"DDJJ\"
			from 
				sistema.lotes l left join
				sistema.subidas s on l.id_subida = s.id_subida left join
				sistema.estados e on l.id_estado = e.id_estado
			where ";
		$sql .= $_SESSION['grupo'] != 25 ? "id_provincia = '$_SESSION[grupo]' and " : " ";
		$sql .= "id_padron = ? 
			order by 
				2 desc";
		return $sirge->jsonDT($this->_db->query($sql , $params)->getResults() , true);
	}
	
}

?>
