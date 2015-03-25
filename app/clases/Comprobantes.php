<?php
class Comprobantes implements Padron 
{
    private
        $_db,
        $_validacion,
        $_comprobante,
        $_comprobante_ori,
        $_lote,
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
        $_comprobante_data = array(
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
        );

    public function __construct() {
        $this->_db = Bdd::getInstance();
        $this->_validacion = new Validar();
    }
    
    public function ingresar (){
        if (Archivo::comparar($this->_encabezados , $this->_comprobante)) {
            $this->_comprobante = array_combine ($this->_encabezados , $this->_comprobante);
            if ($this->_validacion->validarRegistro($this->_comprobante , $this->_encabezados , $this->_reglas)->resultado()) {
                $this->ingresarRegistro();
            } else {
                $this->ingresarError($this->_comprobante_ori , $this->_lote , $this->_validacion->getError());
            }
        } else {
            $this->ingresarError($this->_comprobante_ori , $this->_lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
        }
    }   
    
    public function procesar($file_pointer , $lote){
        $iLote = new Lote();
        fgets ($file_pointer);
        $this->_lote = $lote;
        while (!feof($file_pointer)) {
            $this->_comprobante 	= explode(";" , trim(fgets($file_pointer)));
            if (count ($this->_comprobante) > 1) {
                $this->_comprobante_ori	= $this->_comprobante;
                $this->_comprobante[] 	= $this->_lote;
                $this->ingresar();
            }
        }
        $iLote->completar($this->_lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
        return $this->_contador;
    }
    
    public function ingresarRegistro(){
        $this->armarArray();
        $sql = "
            INSERT INTO comprobantes.c_01(
                efector, numero_comprobante, tipo_comprobante, fecha_comprobante, 
                fecha_recepcion, fecha_notificacion, fecha_liquidacion, fecha_debito_bancario, 
                importe, importe_pagado, factura_debitada, concepto, lote)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($this->_db->query($sql , $this->_comprobante_data)->getError()){
            $this->ingresarError($this->_comprobante_ori , $this->_lote , $this->_db->getErrorInfo());
        } else {
            $this->_contador['insertados']++;
        }
    }
    
    public function armarArray(){
        foreach ($this->_comprobante_data as $campo => $valor) {
            $this->_comprobante_data[$campo] = $this->_comprobante[$campo];
        }
    }
    
    public function ingresarError($registro , $lote , $error){
        $campos = array ('id_provincia','motivos','registro_rechazado','lote');
        $data = array($_SESSION['grupo'] , $error , implode (';',$registro) , $lote);
        $this->_db->insert('comprobantes.rechazados', $campos , $data);
        $this->_contador['rechazados']++;
    }
}
