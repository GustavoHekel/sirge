<?php

class Profe extends Padron 
{
    protected 
        $_db,
        $_validacion,
        $_lote,
        $_registro_data = array(
            'tipo_documento' => '',
			'numero_documento' => '',
			'nombre_apellido' => '',
			'sexo' => '',
			'fecha_nacimiento' => '',
			'fecha_alta' => '',
			'id_beneficiario_profe' => '',
			'id_parentezco' => '',
			'ley_aplicada' => '',
			'fecha_desde' => '',
			'fecha_hasta' => '',
			'id_provincia' => '',
		//	'codigo_os' => '',
            'lote' => ''
        ),
        $_encabezados = array (
			'tipo_documento',
			'numero_documento',
			'nombre_apellido',
			'sexo',
			'fecha_nacimiento',
			'fecha_alta',
			'id_beneficiario_profe',
			'id_parentezco',
			'ley_aplicada',
			'fecha_desde',
			'fecha_hasta',
			'id_provincia',
		//	'codigo_os',
            'lote'
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
			)
		),
        $_contador = array(
            'insertados' => 0 ,
            'rechazados' => 0 ,
            'modificados' => 0
        ),
        $_sql = "
            INSERT INTO profe.beneficiarios(
                tipo_documento, numero_documento, nombre_apellido, sexo, fecha_nacimiento, 
                fecha_alta, id_beneficiario_profe, id_parentezco, ley_aplicada, 
                fecha_desde, fecha_hasta, id_provincia, lote)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    
}
