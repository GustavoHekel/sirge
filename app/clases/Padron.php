<?php

class Padron extends Archivos {
	
	/**
	 * 
	 * METODOS MISC
	 * 
	 **/
	
	protected function ComparaCampos ($encabezados , $data) {
		return count ($encabezados) != count ($data) ? false : true;
	}
	
	
	public static function GetUsuarioCierre ($lote) {
		
		$params = array ($lote);
		$sql 	= "select id_estado from sistema.lotes where lote = ?";
		$estado = BDD::GetInstance()->Query($sql , $params)->GetRow()['id_estado'];
		
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
		return BDD::GetInstance()->Query($sql , $params)->GetRow()['usuario'];
	}
	
	public static function GetRechazos ($lote) {
		
		$params = array ($lote);
		$sql 	= "select id_padron from sistema.lotes where lote = ?";
		$padron	= BDD::GetInstance()->Query($sql , $params)->GetRow()['id_padron'];
		
		switch ($padron) {
			case 1 : $nombre_esquema = 'prestaciones'; break;
			case 2 : $nombre_esquema = 'aplicacion_fondos'; break;
			case 3 : $nombre_esquema = 'comprobantes'; break;
		}
		
		$sql = "select row_number() over() || ' - Motivo(s)-> ' || motivos || ' <br /> Registro-> ' || registro_rechazado as r from {$nombre_esquema}.rechazados where lote = ?";
		
		return implode ("<br />" , BDD::GetInstance()->Query($sql , $params)->GetList());
		
	}
	
	/**
	 * 
	 * METODOS MANEJO DE LOTES
	 * 
	 **/
	 
	public function ListadoLotes ($id_padron) {
		$params = array ($id_padron);
		
		if ($_SESSION['grupo'] <> '25') {
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
					, case when exists (select 1 from declaraciones_juradas.impresiones_ddjj_sirge i where i.lote = l.lote) 
						then '<span class=\"label label-success\">IMPRESA</span>'
						else
							case when e.id_estado = 3 
							then '<span class=\"label label-info\">ELIMINADO</span>' 
							else '<span class=\"label label-warning\">PENDIENTE</span>' 
						end
					end as \"DDJJ\"
				from 
					sistema.lotes l left join
					sistema.estados e on l.id_estado = e.id_estado
				where 
					id_provincia = '$_SESSION[grupo]' 
					and id_padron = ? 
				order by 
					2 desc";
		} else {
			$sql = "
				select 
					'<span id_estado=\"' || e.id_estado || '\" lote=\"' || l.lote ||'\" class=\"row-details row-details-close\"></span>' as _
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
					sistema.estados e on l.id_estado = e.id_estado
				where 
					id_padron = ? 
				order by 
					2 desc";
		}
		return $this->JSONDT(BDD::GetInstance()->Query($sql , $params)->GetResults() , true);
	}
	 
	
	public function NuevoLote ($id_provincia , $id_usuario , $id_padron , $id_subida) {
		
		$params = array (
			$id_provincia
			, $id_usuario
			, $id_padron
			, $id_subida
		);
		
		$sql = "
			insert into sistema.lotes (id_provincia , id_usuario , id_padron , id_subida) 
			values (?,?,?,?)";
		BDD::GetInstance()->Query($sql , $params);
		
		$sqll = "select currval ('sistema.lotes_new_lote_seq') limit 1";
		return BDD::GetInstance()->Query($sqll)->GetRow()['currval'];
		
	}
	
	public function CerrarLote ($lote) {
		
		$params = array (
			$lote
			, $_SESSION['id_usuario']
		);
		
		$sql_1 = "update sistema.lotes set id_estado = 1 where lote = ?";
		$sql_2 = "insert into sistema.lotes_aceptados (lote , id_usuario) values (?,?)";
		
		BDD::GetInstance()->Query($sql_1 , $params);
		BDD::GetInstance()->Query($sql_2 , $params);
		
		echo 'Se ha cerrado el lote ' . $lote;
		
	}
	
	public function EliminarLote ($lote) {
		
		$params_1 = array ($lote);
		$params_2 = array ($lote , $_SESSION['id_usuario']);
		
		$sql_1 = "update sistema.lotes set id_estado = 3 where lote = ?";
		$sql_2 = "insert into sistema.lotes_eliminados (lote , id_usuario) values (?,?)";
		
		BDD::GetInstance()->Query($sql_1 , $params_1);
		BDD::GetInstance()->Query($sql_2 , $params_2);
		
		echo 'Se ha eliminado el lote ' . $lote;
		
	}
	
	
	public function CompletarLote ($lote , $registros_in , $registros_out , $registros_mod) {
		
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
				, fin = LOCALTIMESTAMP
			where 
				lote = ?";
		BDD::GetInstance()->Query($sql , $params);
	}
	
}

?>
