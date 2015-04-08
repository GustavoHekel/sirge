<?php

class Osp extends Padron
{
    protected
        $_db,
        $_validacion,
        $_registro_data = array(
            'tipo_documento' => '',
            'numero_documento' => '',
            'nombre_apellido' => '',
            'sexo' => '',
            'codigo_os' => '',
            'codigo_postal' => '',
            'tipo_afiliado' => '',
            'lote' => ''
        ),
        $_encabezados = array(
            'tipo_documento',
            'numero_documento',
            'nombre_apellido',
            'sexo',
            'codigo_os',
            'codigo_postal',
            'id_provincia',
            'tipo_afiliado',
            'lote'
        ),
        $_reglas = array(
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
			'tipo_afiliado' => array (
				'required' => true,
				'in' => array (
					'A',
					'T'
				)
			) 
        ), 
        $_contador = array(
            'insertados' => 0 ,
            'rechazados' => 0 ,
            'modificados' => 0
        ),
        $_sql = "
            INSERT INTO osp.osp_01(
                tipo_documento, numero_documento, nombre_apellido, sexo, codigo_os, 
                codigo_postal, tipo_afiliado , lote)
            VALUES (?, ?, ?, ?, ?, ?, ? , ?);";
}