<?php

class Sss extends Puco {
	
	private 
		$_validator,
		$_encabezados = array (
			'cuil_beneficiario',
			'tipo_documento',
			'numero_documento',
			'nombre_apellido',
			'sexo',
			'fecha_nacimiento',
			'tipo_beneficiario',
			'codigo_parentesco',
			'codigo_postal',
			'id_provincia',
			'cuil_titular',
			'codigo_os',
			'ultimo_aporte',
			'cuil_valido',
			'cuit_empleador',
		),
		$_reglas = array (
			'tipo_documento' => array (
				'required' => true,
				'max' => 3
			),
			'numero_documento' => array (
				'required' => true,
				'numeric' => true,
				'min' => 5,
				'max' => 8
			),
			'nombre_apellido' => array (
				'required' => true,
				'max' => 50
			),
			'sexo' => array (
				'in' => array (
					'M',
					'F'
				)
			),
			'codigo_parentesco' => array (
				'required' => true
			),
			'codigo_os' => array (
				'required' => true
			),
			'ultimo_aporte' => array (
				'required' => true,
				'maxPeriodoSSS' => true
			)
		);
	
	public function __construct () {
		$this->_validator = new Validar();
	}
	
	public function ProcesaSSS ($array) {
		if ($this->_validator->ValidarRegistro($this->_linea , $this->_encabezados , $this->_reglas)->Resultado()) {
			$sql = "
				INSERT INTO puco.osp_26(
						cuil_beneficiario, tipo_documento, numero_documento, nombre_apellido, 
						sexo, fecha_nacimiento, tipo_beneficiario, codigo_parentesco, 
						codigo_postal, id_provincia, cuil_titular, codigo_os, ultimo_aporte, 
						cuil_valido, cuit_empleador)
				VALUES (?, ?, ?, ?, 
						?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						?, ?);";
			if ($this->_db->Query($sql)->GetError()) {
				
			}
		}
	}
	
}

?>
