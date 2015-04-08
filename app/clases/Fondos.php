<?php
class Fondos extends Padron 
{
    protected
        $_db,
        $_validacion,
        $_encabezados = array(
            'efector',
            'fecha_gasto',
            'periodo',
            'numero_comprobante',
            'codigo_gasto',
            'efector_cesion',
            'monto',
            'concepto',
            'lote'
        ),
        $_reglas = array(
            'efector' => array (
                    'required' => true
            ),
            'fecha_gasto' => array (
                    'required' => true,
                    'date' => true
            ),
            'periodo' => array (
                    'required' => true,
                    'max' => 7,
                    'min' => 7
            ),
            'numero_comprobante' => array (
                    'max' => 50
            ),
            'codigo_gasto' => array (
                    'required' => true
            ),
            'monto' => array(
                    'required' => true,
                    'numeric' => true
            )
        ),
        $_registro_data = array(
            'efector' => '',
            'fecha_gasto' => '',
            'periodo' => '',
            'numero_comprobante' => '',
            'codigo_gasto' => '',
            'subcodigo_gasto' => '',
            'efector_cesion' => '',
            'monto' => '',
            'concepto' => '',
            'lote' => ''
        ),
        $_contador = array(
            'insertados' => 0 ,
            'rechazados' => 0 ,
            'modificados' => 0
        ),
        $_sql = "
            INSERT INTO fondos.a_01 (
                    efector, fecha_gasto, periodo, numero_comprobante, codigo_gasto, 
                    subcodigo_gasto, efector_cesion, monto, concepto, lote)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		
    
    public function armarArray($registro_data , $registro){
        $codigos = explode ('.' , $registro['codigo_gasto']);
        $registro['codigo_gasto'] = $codigos[0];
        $registro['subcodigo_gasto'] = $codigos[1];
        foreach ($registro_data as $campo => $valor) {
                $registro_data[$campo] = $registro[$campo];
        }
        return $registro_data;
    }	
}