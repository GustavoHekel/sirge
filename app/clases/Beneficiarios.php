<?php

class Beneficiarios {
	
	private function GetUltimoPeriodo () {
		
		$sql = 'select max (periodo) as p from beneficiarios.beneficiarios_periodos';
		return BDD::GetInstance()->Query($sql)->GetRow()['p'];
	}
	
	public function INDECHabitantes ($id_provincia) {
		
		$sql = "
			select
				to_char (habitantes , '999,999,999') as h
			from
				geo.poblacion g
			where
				id_provincia = '$id_provincia'";

		return BDD::GetInstance()->Query($sql)->GetRow()['h'];
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
		
		return BDD::GetInstance()->Query($sql)->GetRow()['c'];	
	}
}

?>
