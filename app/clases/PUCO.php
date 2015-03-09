<?php

class PUCO extends Padron {
	
	private $_db;
	
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
		return $this->JSONDT(BDD::GetInstance()->Query($sql)->GetResults(), true);
	}
	
	public static function SelectOSP ($id_html , $id_provincia = null) {
		
		$html = '<select id="' . $id_html . '"><option value="0">Seleccione una entidad</option>';
		
		$sql 	= "select * from puco.grupos_obras_sociales where id_entidad <= '24'";
		$data 	= BDD::GetInstance()->Query($sql)->GetResults();
		
		foreach ($data as $index => $valor) {
			
			$html .= '<option value="' . $valor['grupo_os'] . '" ';
			$html .= $id_provincia != '25' ? ($id_provincia == $valor['id_entidad'] ? 'selected="selected"' : 'disabled="disabled"') : '';
			$html .= '>' . mb_convert_case ($valor['nombre_grupo'] , MB_CASE_TITLE , 'UTF-8') . '</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
		
	public function ProcesaOSP ($id_carga) {
		$this->_db->Get('sistema.subidas_osp' , 'codigo_osp' , array ('id_subida' , '=' , $id_carga));
	}
}

?>
