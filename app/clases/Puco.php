<?php

class PUCO {
	
	private 
		$_db ,
		$_codigo_osp,
		$_id_subida,
		$_id_archivo,
		$_linea,
		$_validator;
		
		
		
		
	
	public function __construct () {
		$this->_db = BDD::GetInstance();
		
	}
	
	public function ListadoSubidas () {
		$sql = "
			select
				nombre_grupo as nombre_obra_social
				, id_archivo as id
				, fecha_subida :: date as fecha
				, '<a nombre=\"'|| nombre_original ||'\" file=\"' || cao.id_subida || '\" href=\"#\" class=\"procesar\"><i class=\"halflings-icon hdd\"></i></a>' as procesar
				, '<a nombre=\"'|| nombre_original ||'\" file=\"' || cao.id_subida || '\" href=\"#\" class=\"eliminar\"><i class=\"halflings-icon trash\"></a></i>' as eliminar
			from
				puco.grupos_obras_sociales gru left join (
					select
						car.*
						, osp.codigo_osp
						, osp.id_archivo
					from 
						sistema.subidas car left join
						sistema.subidas_osp osp on car.id_subida = osp.id_subida
					where
						id_estado = 0
						and id_padron = 6
				) cao on gru.grupo_os = cao.codigo_osp";
		return $this->JSONDT(BDD::GetInstance()->Query($sql)->getResults(), true);
	}
	
	public static function SelectOSP ($id_Html , $id_provincia = null) {
		
		$Html = '<select id="' . $id_Html . '"><option value="0">Seleccione una entidad</option>';
		
		$sql 	= "select * from puco.grupos_obras_sociales where id_entidad <= '24'";
		$data 	= Bdd::getInstance()->query($sql)->getResults();
		
		foreach ($data as $index => $valor) {
			
			$Html .= '<option value="' . $valor['grupo_os'] . '" ';
			$Html .= $id_provincia != '25' ? ($id_provincia == $valor['id_entidad'] ? 'selected="selected"' : 'disabled="disabled"') : '';
			$Html .= '>' . mb_convert_case ($valor['nombre_grupo'] , MB_CASE_TITLE , 'UTF-8') . '</option>';
		}
		$Html .= '</select>';
		
		return $Html;
	}
	
	
	
	private function ProcesaPROFE () {}
	private function ProcesaOSP () {}
	
	public function ProcesaPadron ($id_carga) {
		
		$params = array ($id_carga);
		$sql 	= "
			select 
				o.*
				, s.nombre_actual 
			from
				sistema.subidas s left join
				sistema.subidas_osp o on s.id_subida = o.id_subida
			where 
				s.id_subida = ?";
		$data 	= $this->_db->Query($sql , $params)->GetRow();
		
		$this->_codigo_osp 		= $data['codigo_osp'];
		$this->_id_subida 		= $id_carga;
		$this->_nombre_archivo 	= $data['nombre_actual'];
		$this->_id_archivo 		= $data['id_archivo'];
		
		if ($fp = fopen ('../data/upload/osp/' . $this->_nombre_archivo)) {
			while (!feof ($fp)) {
				$this->_linea = explode ("||" , trim (fgets ($fp)));
				switch ($this->_codigo_osp) {
					case 998001 : 
						$sss = new SSS();
						$sss->ProcesaSSS($this->_linea);
						break;
					case 997001 : $this->ProcesaPROFE(); break;
					default 	: $this->ProcesaOSP(); break;
				}
			}
		}
	}
}

?>
