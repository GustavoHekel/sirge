<?php

class Comprobantes implements Facturacion {
    private
            $_db,
            $_validacion,
            $_comprobante,
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
                    'insertados' 	=> 0 ,
                    'rechazados' 	=> 0 ,
                    'modificados' 	=> 0
		);
   
    public function __construct() {
        $this->_db = Bdd::getInstance();
        $this->_validacion = new Validar();
    }
    
    public function procesar($lote , $file_pointer){}
    
    public function ingresarRegistro($registro){}
    public function armarArray(){
        foreach ($this->_comprobante_data as $campo => $valor) {
            $this->_comprobante_data[$campo] = $this->_comprobante[$campo];
        }
    }
    
    public function ingresarError($registro , $lote , $error){
        $campos = array ('id_provincia','motivos','registro_rechazado','lote');
        $data = array($_SESSION['grupo'] , $error , implode (';',$registro) , $lote);
        $this->_db->insert($campos , 'comprobantes.rechazados' , $data);
    }
    
}
/*
class Comprobantes extends Padron {
	
	private 
		$_validator ,
		$_nombre_archivo ,
		$_ruta_archivo ,
		$_fp ,
		$_primer_linea ,
		$_comprobante ,
		$_comprobante_ori ,
		$_lote ,
		
	
	
	public function __construct () {
		$this->_validator = new Validar();
	}
	
	protected function IngresaError ($error) {
			
		$params = array(
			$_SESSION['grupo']
			, $error
			, implode (";" , $this->_comprobante_ori)
			, $this->_lote
		);
		
		$sql = "insert into comprobantes.rechazados (id_provincia , motivos , registro_rechazado , lote) values (?,?,?,?)";
		BDD::GetInstance()->Query($sql , $params);
	}
	
	protected function ArmarArrayRegistro () {
		foreach ($this->_comprobante_data as $campo => $valor) {
			$this->_comprobante_data[$campo] = $this->_comprobante[$campo];
		}
	}
	
	protected function IngresaRegistro () {
		
		$sql = '
			INSERT INTO comprobantes.c_01(
					efector, numero_comprobante, tipo_comprobante, fecha_comprobante, 
					fecha_recepcion, fecha_notificacion, fecha_liquidacion, fecha_debito_bancario, 
					importe, importe_pagado, factura_debitada, concepto, lote)
			VALUES (?, ?, ?, ?, 
					?, ?, ?, ?, 
					?, ?, ?, ?, ?);';
		
		if (BDD::GetInstance()->Query($sql , $this->_comprobante_data)->GetError()) {
			$this->IngresaError(BDD::GetInstance()->GetErrorInfo());
			$this->_contador['rechazados'] ++;
		} else {
			$this->_contador['insertados'] ++;
		}
	}
	
	protected function ProcesaRegistro () {
		
		if ($this->_validator->ValidarRegistro($this->_comprobante , $this->_encabezados , $this->_reglas)->Resultado()) {
			$this->ArmarArrayRegistro();
			$this->IngresaRegistro();
		} else { 
			$this->IngresaError($this->_validator->GetError());
			$this->_contador['rechazados'] ++;
		}
	}
	
	public function ProcesaArchivo ($id_carga) {
		
		$this->_nombre_archivo 	= $this->GetNombreArchivo($id_carga);
		$this->_ruta_archivo 	= '../data/upload/comprobantes/' . $this->_nombre_archivo;
		$this->_lote 			= $this->NuevoLote($_SESSION['grupo'] , $_SESSION['id_usuario'] , 3 , $id_carga);
		
		if ($this->_fp = fopen ($this->_ruta_archivo , 'rb')) {
			$this->_primer_linea = fgets ($this->_fp);
			while (! feof ($this->_fp)) {
				$this->_comprobante 		= explode (";" , trim (fgets ($this->_fp)));
				$this->_comprobante_ori 	= $this->_comprobante;
				$this->_comprobante[] 	= $this->_lote;

				if ($this->ComparaCampos($this->_encabezados , $this->_comprobante)) {
					$this->_comprobante = array_combine ($this->_encabezados , $this->_comprobante);
					$this->ProcesaRegistro();
				}
			}

			$this->CompletarLote($this->_lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
			$this->Cierre($id_carga);
			echo '<pre>' , print_r ($this->_contador) , '</pre>';
			
		} else {
			die ("ERROR AL ABRIR EL ARCHIVO " . $this->_nombre_archivo);
		}
	}
	
}
*/
?>
