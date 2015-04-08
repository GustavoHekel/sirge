<?php
class Comprobantes extends Padron 
{
    protected 
        $_db,
        $_validacion,
        $_encabezados = array(
            'efector',
            'numero_comprobante',
            'tipo_comprobante',
            'fecha_comprobante',
            'fecha_recepcion',
            'fecha_notificacion',
            'fecha_liquidacion',
            'fecha_debito_bancario',
            'importe',
            'importe_pagado',
            'factura_debitada',
            'concepto',
            'lote'
        ),
        $_reglas = array(
            'efector' => array (
				'required' => true
            ),
            'numero_comprobante' => array (
				'required' => true,
				'max' => 50
            ),
            'tipo_comprobante' => array (
				'required' => true,
				'in' => array (
						'FC',
						'NC',
						'ND'
				)
            ),
            'fecha_comprobante' => array (
				'required' => true,
				'date' => true
            ),
            'fecha_recepcion' => array (
				'required' => true,
				'date' => true
            ),
            'fecha_notificacion' => array (
				'required' => true,
				'date' => true
            ),
            'fecha_liquidacion' => array (
				'required' => true,
				'date' => true
            ),
            'fecha_debito_bancario' => array (
				'required' => true,
				'date' => true
            ),
            'importe' => array (
                    'required' => true,
                    'numeric' => true
            ),
            'importe_pagado' => array (
                    'required' => true,
                    'numeric' => true
            )
        ),
        $_registro_data = array(
            'efector' => '',
            'numero_comprobante' => '',
            'tipo_comprobante' => '',
            'fecha_comprobante'	=> '',
            'fecha_recepcion' => '',
            'fecha_notificacion' => '',
            'fecha_liquidacion'	=> '',
            'fecha_debito_bancario' => '',
            'importe' => '',
            'importe_pagado' => '',
            'factura_debitada' => '',
            'concepto' => '',
            'lote' => ''
        ),
        $_contador = array(
            'insertados' => 0 ,
            'rechazados' => 0 ,
            'modificados' => 0
        ),
        $_sql = "
            INSERT INTO comprobantes.c_01(
                efector, numero_comprobante, tipo_comprobante, fecha_comprobante, 
                fecha_recepcion, fecha_notificacion, fecha_liquidacion, fecha_debito_bancario, 
                importe, importe_pagado, factura_debitada, concepto, lote)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
}
