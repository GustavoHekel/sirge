<?php

class Beneficiarios {
	
	private function GetUltimoPeriodo () {
		$sql = 'select max (periodo) as p from beneficiarios.beneficiarios_periodos';
		return Bdd::GetInstance()->Query($sql)->GetRow()['p'];
	}
	
	public function INDECHabitantes ($id_provincia) {
		
		$sql = "
			select
				to_char (habitantes , '999,999,999') as h
			from
				geo.poblacion g
			where
				id_provincia = '$id_provincia'";

		return Bdd::GetInstance()->Query($sql)->GetRow()['h'];
	}
	
	public function ResumenBeneficiarios ($id_provincia , $campo) {
		
		$periodo = $this->GetUltimoPeriodo();
		
		$sql = "
			select
				to_char ($campo , '99,999,999') as c
			from
				beneficiarios.resumen_beneficiarios
			where
				id_provincia = '$id_provincia'
				and periodo = $periodo";
		
		return Bdd::GetInstance()->Query($sql)->GetRow()['c'];	
	}
	
	public function Matrix ($clave_beneficiario) {
		
		$params = array ($clave_beneficiario , $clave_beneficiario);
		$sql 	= "
			select
				s.periodo
				, coalesce (a.cantidad , 0) as cantidad
			from (
				select (extract (year from generate_series) ::text || lpad (extract (month from generate_series) :: text , 2 , '0')) :: int as periodo
				from generate_series ('2004-01-01' :: timestamp , (extract (year from localtimestamp) :: text || '-12-31') ::timestamp , '1 month')) s left join (
					select
						p.periodo
						, cantidad
					from 
						(
							select * from beneficiarios.beneficiarios_periodos where clave_beneficiario = ?
						) p left join (
							select 
								clave_beneficiario
								, extract (year from fecha_prestacion) ::text || lpad (extract (month from fecha_prestacion) :: text , 2 , '0') as periodo
								, count (*) as cantidad
							from prestaciones.prestaciones 
							where clave_beneficiario = ?
							group by 1,2
						) b on p.clave_beneficiario || p.periodo = b.clave_beneficiario || b.periodo
				) a on s.periodo = a.periodo
			order by 1";
		
		$data 		= Bdd::GetInstance()->Query($sql , $params)->GetResults();
		$periodos 	= array();
		$info 		= array();
		
		
		foreach ($data as $clave => $valor) {
			$periodos[$valor['periodo']] = $valor['cantidad'];
		}
		
		for ($i = 1 ; $i <= 12 ; $i ++) {
			
			for ($j = 2004 ; $j <= date('Y') ; $j ++) {
				$info[][] = ($i-1) . ',' . ($j-2004) . ',' . $periodos[($j . str_pad($i , 2 , '0' , STR_PAD_LEFT))];
				//$info .= '[' . ($i-1) . ',' . ($j-2004) . ',' . $periodos[($j . str_pad($i , 2 , '0' , STR_PAD_LEFT))] .'] , ';
			}
		}
		
		print_r ($info);
	}
}

?>
