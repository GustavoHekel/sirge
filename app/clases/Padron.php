<?php

class Padron extends Archivos {
	
	/**
	 * 
	 * METODOS MISC
	 * 
	 **/
	
	protected function ComparaCampos ($array , $numero_campos) {
		
		if (count ($array) <> $numero_campos)
			return 0;
		else
			return 1;
	}
	
	/**
	 * 
	 * METODOS MANEJO DE LOTES
	 * 
	 **/
	
	public function NuevoLote ($id_provincia , $id_usuario , $id_padron , $nombre_archivo) {
		
		$params = array (
			$id_provincia
			, $id_usuario
			, 'LOCALTIMESTAMP'
			, $id_padron
			, $this->GetIDSubida($nombre_archivo)
		);
		
		$sql = "
			insert into sistema.lotes (id_provincia , id_usuario_proceso , inicio , id_padron , id_subida) 
			values (?,?,?,?);
			select currval ('sistema.lotes_lote_seq') limit 1";
		
		return $this->_db->Query($sql , $params)->GetRow();
		
	}
	
	public function CerrarLote ($lote , $id_usuario) {
		
		$params_2 = array (
			$lote
			, $id_usuario
			, 'LOCALTIMESTAMP'
		);
		
		$sql_1 = "update sistema.lotes set id_estado = 1 where lote = $lote";
		$sql_2 = "insert into sistema.lotes_aceptados values (?,?,?)";
		
		$this->_db->Query($sql_1);
		$this->_db->Query($sql_2 , $params_2);
		
	}
	
	public function EliminarLote ($lote , $id_usuario) {
		
		$params_2 = array (
			$lote
			, $id_usuario
			, 'LOCALTIMESTAMP'
		);
		
		$sql_1 = "update sistema.lotes set id_estado = 3 where lote = $lote";
		$sql_2 = "insert into sistema.lotes_eliminados values (?,?,?)";
		
		$this->_db->Query($sql_1);
		$this->_db->Query($sql_2 , $params_2);
		
	}
	
	
	public function CompletarLote ($lote , $registros_in , $registros_out) {
		
		$sql = "
			update sistema.lotes
			set
				registros_in = $registros_in
				, registros_out = $registros_out
				, fin = LOCALTIMESTAMP";
		$this->_db->Query($sql);
		
	}
	
	
	
}

?>
