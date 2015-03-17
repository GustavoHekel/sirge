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
				returning lote
			) select * from ins1";
		return $this->_db->query($sql , $params)->get()['lote'];
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
				update sistema.lotes 
				set id_estado = 3 
				where lote = ?
				returning lote
			), ins1 as (
				insert into sistema.lotes_eliminados (lote , id_usuario)
				values ((select * from upd1) , ?)
				returning lote
			) delete from aplicacion_fondos.rechazados where lote = (select * from ins1)
			";
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
	
	public function listar($id_padron){
		$sirge = new Sirge();
		$params = array ($id_padron);
		$sql = "
			select 
				'<span lote=\"'|| lote ||'\" id_estado=\"'|| l.id_estado ||'\" class=\"row-details row-details-close\"></span>' as _
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
	
	public static function getUsuarioCierre ($lote) {
		$estado = Bdd::getInstance()->find('id_estado' , 'sistema.lotes' , array($lote));
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
		return Bdd::getInstance()->query($sql , $params)->get()['usuario'];
	}
	
	public static function getRechazos ($lote) {
		$params = array ($lote);
		$sql 	= "select id_padron from sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida where lote = ?";
		$padron	= Bdd::getInstance()->query($sql , $params)->get()['id_padron'];
		switch ($padron) {
			case 1 : $nombre_esquema = 'prestaciones'; break;
			case 2 : $nombre_esquema = 'aplicacion_fondos'; break;
			case 3 : $nombre_esquema = 'comprobantes'; break;
			case 4 : $nombre_esquema = 'sss'; break;
			case 5 : $nombre_esquema = 'profe'; break;
			case 6 : $nombre_esquema = 'osp'; break;
		}
		$sql = "select row_number() over() || ' - Motivo(s)-> ' || motivos || ' <br /> Registro-> ' || registro_rechazado as r from {$nombre_esquema}.rechazados where lote = ?";
		return implode ("<br />" , Bdd::getInstance()->query($sql , $params)->getList());
	}
}

?>
