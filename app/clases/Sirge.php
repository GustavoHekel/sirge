<?php

class SIRGe {
	
	private 
		$_db ,
		$_comentarios ;
	
	public function __construct () {
		$this->_db = Bdd::GetInstance();
	}
	
	public function jsonDT ($data = array() , $ajax = false) {
		
		$i = 0;
		
		if (count ($data)) {
			foreach ($data[0] as $key => $value) {
				$json['aoColumns'][$i]['mData'] = $key;
				$json['aoColumns'][$i]['sTitle'] = str_replace ('_' , ' ' , ucwords ($key));
				$i ++;
			}
		} else {
			$json['aoColumns'][$i]['mData'] = 'no_data';
			$json['aoColumns'][$i]['sTitle'] = 'Atenci&oacute;n!';
		}
		
		if (count ($data)) {
			foreach ($data as $key => $value) {
				$json['data'][$key] = $value;
			}
		} else {
			$json['data'] = null;
		}
		
		$json['recordsTotal'] 		= count ($data);
		$json['recordsFiltered'] 	= count ($data);
		$json['draw'] 				= 1;
		
		if ($ajax) {
			echo (json_encode ($json , JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		} else {
			return (json_encode ($json , JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		}
		
	}
	
	public function mesATexto () {
		switch (date('m')) {
			case 1	: $mes = 'Enero'; break;
			case 2	: $mes = 'Febrero'; break;
			case 3	: $mes = 'Marzo'; break;
			case 4	: $mes = 'Abril'; break;
			case 5	: $mes = 'Mayo'; break;
			case 6	: $mes = 'Junio'; break;
			case 7	: $mes = 'Julio'; break;
			case 8	: $mes = 'Agosto'; break;
			case 9	: $mes = 'Septiembre'; break;
			case 10	: $mes = 'Octubre'; break;
			case 11	: $mes = 'Noviembre'; break;
			case 12	: $mes = 'Diciembre'; break;
			default: break;
		}
		return $mes;
	}

	public static function getIdProvincia ($provincia) {
		$id_provincia;
		switch ($provincia) {
			case 'CIUDAD DE BUENOS AIRES' : $id_provincia = '01'; break;
			case 'BUENOS AIRES' : $id_provincia = '02'; break;
			case 'CATAMARCA' : $id_provincia = '03'; break;
			case 'CÓRDOBA' : $id_provincia = '04'; break;
			case 'CORRIENTES' : $id_provincia = '05'; break;
			case 'ENTRE RIOS' : $id_provincia = '06'; break;
			case 'JUJUY' : $id_provincia = '07'; break;
			case 'LA RIOJA' : $id_provincia = '08'; break;
			case 'MENDOZA' : $id_provincia = '09'; break;
			case 'SALTA' : $id_provincia = '10'; break;
			case 'SAN JUAN' : $id_provincia = '11'; break;
			case 'SAN LUIS' : $id_provincia = '12'; break;
			case 'SANTA FE' : $id_provincia = '13'; break;
			case 'SANTIAGO DEL ESTERO' : $id_provincia = '14'; break;
			case 'TUCUMÁN' : $id_provincia = '15'; break;
			case 'CHACO' : $id_provincia = '16'; break;
			case 'CHUBUT' : $id_provincia = '17'; break;
			case 'FORMOSA' : $id_provincia = '18'; break;
			case 'LA PAMPA' : $id_provincia = '19'; break;
			case 'MISIONES' : $id_provincia = '20'; break;
			case 'NEUQUÉN' : $id_provincia = '21'; break;
			case 'RÍO NEGRO' : $id_provincia = '22'; break;
			case 'SANTA CRUZ' : $id_provincia = '23'; break;
			case 'TIERRA DEL FUEGO' : $id_provincia = '24'; break;
		}
		return $id_provincia;
	}
	
	public static function getNombreProvincia ($id_provincia) {
		$params = array ($id_provincia);
		$sql 	= "select descripcion from sistema.entidades where id_entidad = ?";
		return Bdd::getInstance()->query($sql , $params)->getRow()['descripcion'];
	}
	
	public static function selectProvincia ($id_html , $id_provincia = null) {
		$html = '<select id="' . $id_html . '"><option value="0">Seleccione una entidad</option>';
		$sql 	= "select * from sistema.provincias";
		$data 	= Bdd::getInstance()->query($sql)->getResults();
		foreach ($data as $index => $valor) {
			$html .= '<option value="' . $valor['id_provincia'] . '" ';
			$html .= $id_provincia != '25' ? ($id_provincia == $valor['id_provincia'] ? 'selected="selected"' : 'disabled="disabled"') : '';
			$html .= '>' . mb_convert_case ($valor['descripcion'] , MB_CASE_TITLE , 'UTF-8') . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
}

?>
