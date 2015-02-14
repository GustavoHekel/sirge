<?php

class Prestaciones extends Padron {
	
	/**
	 * 
	 * METODOS PARA MANEJO DE INGRESO DE REGISTROS A LA BASE DE DATOS
	 * 
	 **/
	
	protected function ProcesaRegistro ($registro) {}
	protected function IngresaRegistro ($registro) {}
	protected function IngresaError ($registro) {}
	
	/**
	 * 
	 * METODOS VARIOS
	 * 
	 **/
	
	public function CantidadPrestacionesProvincia ($id_provincia) {
		
		$sql = "
			select
				to_char (sum (registros_in) , '999,999,999') as r
			from
				sistema.lotes
			where
				id_provincia = '$id_provincia'
				and id_padron = 1
				and id_estado = 1";
		
		return BDD::GetInstance()->Query($sql)->GetRow()['r'];
		
	}
}

?>
