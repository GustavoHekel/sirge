<?php

class Notificaciones extends SIRGe {
	
	private $_db;
	
	public function __construct () {
		$this->_db = Bdd::getInstance();
	}
	
	private function armaNotificacion ($texto) {
		$js = "
			$.gritter.add({
				title 	: 'ATENCIÓN' ,
				text 	: '$texto' ,
				sticky 	: true 
			});";
		return $js;
	}
	
	private function gritterFuenteDatos ($id_provincia , $id_fuente) {
		
		$params = array(
			$id_provincia ,
			$id_fuente
		);
		
		$sql = "
			select
				current_date - max (fecha_aceptado) :: date as dias
			from 
				sistema.lotes l left join
				sistema.lotes_aceptados a on l.lote = a.lote
			where 
				id_provincia = ?
				and id_padron = ?";
		
		$dias = $this->_db->query($sql , $params)->getRow()['dias'];
		
		if ($dias > 30) {
			return $this->armaNotificacion('No se han presentado ' . $this->getNombrePadron($id_fuente) .' por ' . $dias . ' dias');
		}
	}
	
	public function gritterSIRGe ($id_provincia) {

		$gr = '';
		
		if ($id_provincia < '25') {
			$f = array (1 ,	2 ,	3);
			foreach ($f as $id) {
				$gr .= $this->gritterFuenteDatos ($id_provincia , $id);
			}
		}
		return $gr;
	}
	
	public function gritterDOIU9 ($id_provincia) {
		
		$gr = '';
		
		if ($id_provincia < '25') {
		
			$params = array($id_provincia);
			
			$sql = "
				select 
					current_date - max (fecha_impresion) :: date as dias
				from declaraciones_juradas.impresiones_ddjj_doiu9
				where 
					fecha_impresion >= '2013-12-01'
					and id_provincia = ?
				group by id_provincia";
				
			$dias = $this->_db->query($sql , $params)->getRow()['dias'];
			
			if ($dias > 30) {
				$gr = $this->armaNotificacion('RECUERDE GENERAR LA DDJJ DE INFORMACIÓN PRIORIZADA');	
			}
		}
		
		return $gr;
	}
	
}

?>
