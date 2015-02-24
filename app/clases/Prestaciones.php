<?php

class Prestaciones extends Padron {
	
	private 
		$_validator ,
		$_nombre_archivo ,
		$_ruta_archivo ,
		$_fp ,
		$_primer_linea ,
		$_prestacion ,
		$_prestacion_ori ,
		$_lote ,
		$_encabezados = array(
				'operacion',
				'estado',
				'numero_comprobante',
				'codigo_prestacion',
				'subcodigo_prestacion',
				'importe',
				'fecha_prestacion',
				'clave_beneficiario',
				'tipo_documento',
				'clase_documento',
				'numero_documento',
				'id_dr_1',
				'val_dr_1',
				'id_dr_2',
				'val_dr_2',
				'id_dr_3',
				'val_dr_3',
				'id_dr_4',
				'val_dr_4',
				'orden',
				'cuie',
				'lote'
			),
		$_reglas = array(
				'operacion' => array(
					'required' => true,
					'max' => 1,
					'in' => array(
						'A',
						'M'
					)
				),
				'estado' => array(
					'required' => true,
					'max' => 1,
					'in' => array(
						'L',
						'D'
					)
				),
				'numero_comprobante' => array(
					'required' => true,
					'min' => 1,
					'max' => 50
				),
				'codigo_prestacion'	=> array(
					'required' => true,
					'min' => 5,
					'max' => 11
				),
				'importe' => array(
					'min' => 1,
					'numeric' => true,
					'zero' => false
				),
				'fecha_prestacion' => array(
					'required' => true,
					'date' => true
				),
				'clave_beneficiario' => array(
					'required' => true,
					'min' => 16,
					'max' => 16,
					'numeric' => true,
					'exception' => array(
						'indice'=> 'clave_beneficiario',
						'valor' => 9999999999999999,
						'campo' => 'codigo_prestacion',
						'grupo' => 'comunidad'
					)
				),
				'tipo_documento' => array(
					'max' => 3
				),
				'clase_documento' => array(
					'max' => 1
				),
				'numero_documento' => array(
					'max' => 14
				),
				'orden' => array(
					'numeric' => true
				),
				'cuie' => array(
					'max' => 6,
					'min' => 6
				)
			),
		$_prestacion_data = array(
			'estado'				=> '',
			'cuie'					=> '',
			'numero_comprobante'	=> '',
			'codigo_prestacion'		=> '',
			'subcodigo_prestacion'	=> '',
			'importe'				=> '',
			'fecha_prestacion'		=> '',
			'clave_beneficiario'	=> '',
			'tipo_documento'		=> '',
			'clase_documento'		=> '',
			'numero_documento'		=> '',
			'orden'					=> '',
			'lote'					=> ''
		),
		$_prestacion_repo = array(
		
		),
		$_contador = array(
			'insertados' 	=> 0 ,
			'rechazados' 	=> 0 ,
			'modificados' 	=> 0
		);

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
			, implode (";" , $this->_prestacion_ori)
			, $this->_lote
		);
		
		$sql = "insert into prestaciones.rechazados (id_provincia , motivos , registro_rechazado , lote) values (?,?,?,?)";
		BDD::GetInstance()->Query($sql , $params);
	}
	
	protected function ArmarArrayRegistro () {
		foreach ($this->_prestacion_data as $campo => $valor) {
			$this->_prestacion_data[$campo] = $this->_prestacion[$campo];
		}		
	}
	
	protected function IngresaRegistro () {
		
		$sql = '
			insert into prestaciones.p_01 (
				estado , efector , numero_comprobante , codigo_prestacion ,
				subcodigo_prestacion , precio_unitario , fecha_prestacion , clave_beneficiario , 
				tipo_documento , clase_documento , numero_documento , orden , lote)
			values (?,?,?,?,?,?,?,?,?,?,?,?,?)';
		
		if (BDD::GetInstance()->Query($sql , $this->_prestacion_data)->GetError()) {
			$this->IngresaError(BDD::GetInstance()->GetErrorInfo());
			$this->_contador['rechazados'] ++;
		} else {
			$this->_contador['insertados'] ++;
		}
	}
	
	protected function BuscarPrestacion () {
		
		$params = array(
			$this->_prestacion_data['numero_comprobante'],
			$this->_prestacion_data['codigo_prestacion'],
			$this->_prestacion_data['subcodigo_prestacion'],
			$this->_prestacion_data['fecha_prestacion'],
			$this->_prestacion_data['clave_beneficiario'],
			$this->_prestacion_data['orden']
		);
		
		$sql = "
			select *
			from
				prestaciones.p_01
			where
				numero_comprobante = ?
				and codigo_prestacion = ?
				and subcodigo_prestacion = ?
				and fecha_prestacion = ?
				and clave_beneficiario = ?
				and orden = ?";
		
		return BDD::GetInstance()->Query($sql , $params)->GetCount();
	}
	
	protected function ModificaEstado () {
		
		$params = array(
			$this->_prestacion_data['numero_comprobante'],
			$this->_prestacion_data['codigo_prestacion'],
			$this->_prestacion_data['subcodigo_prestacion'],
			$this->_prestacion_data['fecha_prestacion'],
			$this->_prestacion_data['clave_beneficiario'],
			$this->_prestacion_data['orden']
		);
		
		$sql = "
			update
				prestaciones.p_01
			set
				estado = 'D'
			where
				numero_comprobante = ?
				and codigo_prestacion = ?
				and subcodigo_prestacion = ?
				and fecha_prestacion = ?
				and clave_beneficiario = ?
				and orden = ?";
			
		BDD::GetInstance()->Query($sql , $params);		
		$this->_contador['modificados'] ++;
	}
	
	protected function ModificaRegistro () {
		if ($this->BuscarPrestacion()) {
			$this->ModificaEstado();
		} else {
			$this->IngresaError('LA PRESTACIÓN QUE SE INTENTA MODIFICAR NO EXISTE');
			$this->_contador['rechazados'] ++;
		}
	}
	
	protected function ProcesaRegistro () {
		
		if ($this->_validator->ValidarRegistro($this->_prestacion , $this->_encabezados , $this->_reglas)->Resultado()) {
			$operacion = array_shift ($this->_prestacion);
			$this->ArmarArrayRegistro();
			switch ($operacion) {
				case 'A':
					$this->IngresaRegistro();
					break;
				case 'M':
					$this->ModificaRegistro();
					break;
			}
		} else { 
			$this->IngresaError($this->_validator->GetError());
			$this->_contador['rechazados'] ++;
		}
	}
	
	public function ProcesaArchivo ($id_carga) {
		
		$this->_nombre_archivo 	= $this->GetNombreArchivo($id_carga);
		$this->_ruta_archivo 	= '../data/upload/prestaciones/' . $this->_nombre_archivo;
		$this->_lote 			= $this->NuevoLote($_SESSION['grupo'] , $_SESSION['id_usuario'] , 1 , $id_carga);
		
		if ($this->_fp = fopen ($this->_ruta_archivo , 'rb')) {
			$this->_primer_linea = fgets ($this->_fp);
			while (! feof ($this->_fp)) {
				$this->_prestacion 		= explode (";" , trim (fgets ($this->_fp)));
				$this->_prestacion_ori 	= $this->_prestacion;
				$this->_prestacion[] 	= $this->_lote;
				
				if ($this->ComparaCampos($this->_encabezados , $this->_prestacion)) {
				
					$this->_prestacion	= array_combine ($this->_encabezados , $this->_prestacion);
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
	
	/**
	 * 
	 * METODOS VARIOS
	 * 
	 **/
	
	public function CantidadPrestacionesProvincia ($id_provincia) {
		
		$sql = "
			select
				to_char (sum (registros_in) , '999,999,999') as r
			from
				sistema.lotes
			where
				id_provincia = '$id_provincia'
				and id_padron = 1
				and id_estado = 1";
		
		return BDD::GetInstance()->Query($sql)->GetRow()['r'];
		
	}
}

?>
