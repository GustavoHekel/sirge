<?php

class Forms {
	
	public static function getSelectProvincia ($id){
		$id_provincia = Bdd::getInstance()->find('id_provincia' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
		$provincias = Bdd::getInstance()->query('select * from sistema.provincias order by descripcion')->getResults();
		$select = "<select name='id_provincia'>";
		foreach ($provincias as $id => $data){
			if ($data['id_provincia'] == $id_provincia) {
				$select .= "<option value='{$data['id_provincia']}' selected='selected'>{$data['descripcion']}</option>";
			} else {
				$select .= "<option value='{$data['id_provincia']}'>{$data['descripcion']}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	public static function getSelectTipoDocumento ($id){
		$tipo_doc = Bdd::getInstance()->find('tipo_documento' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
		$tipos_doc = [
			'DNI' => 'Documento Nacional de Identidad',
			'CI' => 'Cédula de identidad',
			'LC' => 'Libreta Cívica',
			'LE' => 'Libreta de Enrolamiento',
			'CM' => 'Cédula migratoria'
		];
		$select = '<select name="tipo_documento">';
		foreach ($tipos_doc as $tipo => $desc) {
			if ($tipo == $tipo_doc) {
				$select .= "<option value='{$tipo}' selected='selected'>{$desc}</option>";
			} else {
				$select .= "<option value='{$tipo}'>{$desc}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	public static function getSelectSexo ($id){
		$s = Bdd::getInstance()->find('sexo' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
		$sexos = [
			'F' => 'Femenino',
			'M' => 'Masculino'
		];
		$select = '<select name="genero">';
		foreach ($sexos as $sigla => $desc) {
			if ($sigla == $s) {
				$select .= "<option value='{$sigla}' selected='selected'>{$desc}</option>";
			} else {
				$select .= "<option value='{$sigla}'>{$desc}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	
}
