<?php

class Fondo {
	
	private 
		$_validator ,
		$_nombre_archivo ,
		$_ruta_archivo ,
		$_fp ,
		$_primer_linea ,
		$_fondo ,
		$_fondo_ori ,
		$_lote ,
		
	/**
	 * 
	 * METODOS PARA MANEJO DE INGRESO DE REGISTROS A LA BASE DE DATOS
	 * 
	 **/	
	
	public function __construct () {
		$this->_validator = new Validar();
	}
	
	protected function IngresaError ($error) {
			
		$params = array(
			$_SESSION['grupo']
			, $error
			, implode (";" , $this->_fondo_ori)
			, $this->_lote
		);
		
		$sql = "insert into aplicacion_fondos.rechazados (id_provincia , motivos , registro_rechazado , lote) values (?,?,?,?)";
		BDD::GetInstance()->Query($sql , $params);
	}
	
	protected function ArmarArrayRegistro () {
		
		$codigos = explode ('.' , $this->_fondo['codigo_gasto']);
		$this->_fondo['codigo_gasto'] = $codigos[0];
		$this->_fondo['subcodigo_gasto'] = $codigos[1];
		
		foreach ($this->_fondo_data as $campo => $valor) {
			$this->_fondo_data[$campo] = $this->_fondo[$campo];
		}
	}
	
	protected function IngresaRegistro () {
		
		$sql = '
			INSERT INTO aplicacion_fondos.a_01 (
					efector, fecha_gasto, periodo, numero_comprobante, codigo_gasto, 
					subcodigo_gasto, efector_cesion, monto, concepto, lote)
			VALUES (?, ?, ?, ?, ?, 
					?, ?, ?, ?, ?)';
		
		if (BDD::GetInstance()->Query($sql , $this->_fondo_data)->GetError()) {
			$this->IngresaError(BDD::GetInstance()->GetErrorInfo());
			$this->_contador['rechazados'] ++;
		} else {
			$this->_contador['insertados'] ++;
		}
	}
	
	protected function ProcesaRegistro () {
		
		if ($this->_validator->ValidarRegistro($this->_fondo , $this->_encabezados , $this->_reglas)->Resultado()) {
			$this->ArmarArrayRegistro();
			$this->IngresaRegistro();
		} else { 
			$this->IngresaError($this->_validator->GetError());
			$this->_contador['rechazados'] ++;
		}
	}
	
	public function ProcesaArchivo ($id_carga) {
		
		$this->_nombre_archivo 	= $this->GetNombreArchivo($id_carga);
		$this->_ruta_archivo 	= '../data/upload/fondos/' . $this->_nombre_archivo;
		$this->_lote 			= $this->NuevoLote($_SESSION['grupo'] , $_SESSION['id_usuario'] , 2 , $id_carga);
		
		if ($this->_fp = fopen ($this->_ruta_archivo , 'rb')) {
			$this->_primer_linea = fgets ($this->_fp);
			while (! feof ($this->_fp)) {
				$this->_fondo 		= explode (";" , trim (fgets ($this->_fp)));
				$this->_fondo_ori 	= $this->_fondo;
				$this->_fondo[] 	= $this->_lote;

				if ($this->ComparaCampos($this->_encabezados , $this->_fondo)) {
					$this->_fondo = array_combine ($this->_encabezados , $this->_fondo);
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

?>
