<?php

class Prestaciones extends Padron {
	
	private 
		$_nombre_archivo ,
		$_ruta_archivo ,
		$_fp;
	
	/**
	 * 
	 * METODOS PARA MANEJO DE INGRESO DE REGISTROS A LA BASE DE DATOS
	 * 
	 **/
	
	protected function ProcesaRegistro ($registro) {}
	protected function IngresaRegistro ($registro) {}
	protected function IngresaError ($registro) {}
	
	public function ProcesaArchivo ($id_carga) {
		
		$this->_nombre_archivo = $this->GetNombreArchivo($id_carga);
		$this->_ruta_archivo = '../data/upload/prestaciones/' . $this->_nombre_archivo;
		
		if ($this->_fp = fopen ($this->_ruta_archivo , 'rb')) {
			
			while (! feof ($this->_fp)) {
				echo fgets ($this->_fp);
			}
			
		} else {
			
		}
		
		
	}
	
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
