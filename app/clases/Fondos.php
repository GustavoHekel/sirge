<?php

class Fondos implements Facturacion {
	
	private
		$_validacion,
		$_db,
                $_lote,
		$_fondo,
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
		$_fondo_data = array(
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
		);
		
	public function __construct(){
            $this->_db = Bdd::getInstance();
            $this->_validacion = new Validar();
	}
	
	public function ingresarError($registro , $lote , $error){
		$campos = array ('id_provincia','motivos','registro_rechazado','lote');
		$data = array($_SESSION['grupo'] , $error , implode (';',$registro) , $lote);
		$this->_db->insert($campos , 'aplicacion_fondos.rechazados' , $data);
	}
	
	public function armarArray(){
		$codigos = explode ('.' , $this->_fondo['codigo_gasto']);
		$this->_fondo['codigo_gasto'] = $codigos[0];
		$this->_fondo['subcodigo_gasto'] = $codigos[1];
		foreach ($this->_fondo_data as $campo => $valor) {
			$this->_fondo_data[$campo] = $this->_fondo[$campo];
		}
	}
	
	public function ingresarRegistro($registro = array()){
		$this->armarArray();
		$sql = '
			INSERT INTO aplicacion_fondos.a_01 (
					efector, fecha_gasto, periodo, numero_comprobante, codigo_gasto, 
					subcodigo_gasto, efector_cesion, monto, concepto, lote)
			VALUES (?, ?, ?, ?, ?, 
					?, ?, ?, ?, ?)';
		if ($this->_db->query($sql , $this->_fondo_data)->getError()){
			$this->ingresarError($this->_fondo_ori , $this->_lote , $this->_db->getErrorInfo());
			$this->_contador['rechazados']++;
		} else {
			$this->_contador['insertados']++;
		}
	}
			
	public function procesar($file_pointer , $lote){
		
		$iLote = new Lote();
		fgets ($file_pointer);
		$this->_lote = $lote;
		while (!feof($file_pointer)) {
			$this->_fondo 		= explode(";" , trim(fgets($file_pointer)));
			$this->_fondo_ori 	= $this->_fondo;
			$this->_fondo[] 	= $this->_lote;
			if (Archivo::comparar($this->_encabezados , $this->_fondo)) {
				$this->_fondo = array_combine ($this->_encabezados , $this->_fondo);
				if ($this->_validacion->validarRegistro($this->_fondo , $this->_encabezados , $this->_reglas)->resultado()) {
					$this->ingresarRegistro($this->_fondo);
				} else {
					$this->ingresarError($this->_fondo_ori , $this->_lote , $this->_validacion->getError());
					$this->_contador['rechazados']++;
				}
			} else {
				$this->ingresarError($this->_fondo_ori , $this->_lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
				$this->_contador['rechazados']++;
			}
		}
		$iLote->completar($this->_lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
		return $this->_contador;
	}
	
}

?>
