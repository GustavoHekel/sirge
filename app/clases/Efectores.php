<?php

class Efectores {
	
	
	public function CantidadEfectoresProvincia ($id_provincia) {
		
		$sql = "
			select
				to_char (count (*) , '999,999') as c
			from	
				efectores.efectores e left join
				efectores.datos_geograficos d on e.id_efector = d.id_efector
			where
				id_provincia = '$id_provincia'";
			
		return BDD::GetInstance()->Query($sql)->GetRow()['c'];
		
	}
	
	public function CantidadEfectoresCompromisoProvincia ($id_provincia) {
		
		$sql = "
			select
				to_char (count (*) , '999,999') as c
			from	
				efectores.efectores e left join
				efectores.datos_geograficos d on e.id_efector = d.id_efector
			where
				id_provincia = '$id_provincia'
				and id_estado = 1
				and integrante = 'S'
				and compromiso_gestion = 'S'";
			
		return BDD::GetInstance()->Query($sql)->GetRow()['c'];
		
	}
	
	public function PorcentajeDescentralizacion ($id_provincia) {
		
		$sql = "
			select
				coalesce (round (( efectores_decentralizados ::numeric / cantidad_efectores) * 100 , 2) , 0) :: text || '%' as d
			from (
				select 
					id_provincia 
					, count (*) as cantidad_efectores
				from 
					efectores.efectores e left join
					efectores.datos_geograficos g on e.id_efector = g.id_efector
				where
					integrante = 'S'
					and compromiso_gestion = 'S'
				group by
					id_provincia) e left join (
				select 
					id_provincia 
					, count (*) as efectores_decentralizados
				from 
					efectores.descentralizacion e left join
					efectores.datos_geograficos g on e.id_efector = g.id_efector left join
					efectores.efectores ef on e.id_efector = ef.id_efector
				where
					factura_descentralizada = 'S'
					and integrante = 'S'
					and compromiso_gestion = 'S'
				group by
					id_provincia) d on e.id_provincia = d.id_provincia
			where
				e.id_provincia = '$id_provincia'";
		
		return BDD::GetInstance()->Query($sql)->GetRow()['d'];
	}
	
}

?>
