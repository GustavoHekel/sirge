<?php
class Validar {
		
	protected 
		$_errores = array(),
		$_validado = false;
	
	public function validarFecha ($fecha) {
		
		$d = DateTime::createFromFormat('Y-m-d', $fecha);
		return $d && $d->format('Y-m-d') == $fecha;
		
	}
	
	public function validarData ($data = array() , $encabezados = array() , $validaciones = array()) {
		
		unset ($this->_errores);
		if (count ($encabezados) == count ($data)) {
			
			if (! empty ($encabezados)) {
				
				$data = array_combine ($encabezados , $data) ;
			}
			
			foreach ($validaciones as $item => $reglas) {
				
				$valor = trim($data[$item]);
				foreach ($reglas as $regla => $valor_regla) {
					
					if ($regla === 'required' && empty($valor)) {
						
						$this->addError("{$item} es un campo obligatorio");
						
					} else {
						switch ($regla) {
							
							case 'min' :
								if (strlen ($valor) < $valor_regla) {
									$this->addError("{$item} debe tener {$valor_regla} caracteres como minimo. Valor = {$valor}");
								}	
								break;
								
							case 'max' :
								if (strlen ($valor) > $valor_regla) {
									$this->addError("{$item} debe tener {$valor_regla} caracteres como maximo. Valor = {$valor}");
								}	
								break;
								
							case 'in' :
								if (! in_array ($valor , $valor_regla)) {
									$this->addError("{$item} no se encuentra dentro de los valores posibles : " . implode (' , ' , $valor_regla) .". Valor = {$valor}");
								}
								break;
								
							case 'numeric' :
								if (! is_numeric ($valor)) {
									$this->addError("{$item} debe ser un valor numerico. Valor = {$valor}");
								}
								break;
								
							case 'zero' :
								if ($valor == 0) {
									if (! $valor_regla) {
										$this->addError("{$item} debe ser un distinto de 0. Valor = {$valor}");
									}
								}
								break;
								
							case 'date' :
								if (! $this->validarFecha ($valor)) {
									$this->addError("{$item} no es un valor valido. Valor = {$valor}");
								}
								break;
								
							case 'notAllowedDepend' :
								if (in_array ($valor , $valor_regla['valores']) && $data[$valor_regla['trigger']['campo']] . $valor_regla['trigger']['operador'] . $valor_regla['trigger']['valor']) {
									$this->addError("{$item} no es un valor valido para el campo {$valor_regla['trigger']['campo']}. Valor = {$valor}");
								}
								break;
								
							case 'notIn' : 
								if (in_array ($valor , $valor_regla)) {
									$this->addError("{$item} no es un valor valido. Valor = {$valor}");
								}
								break;
								
							case 'nullFields' : 
								$null = false;
								if (! strlen ($valor)) {
									foreach ($valor_regla as $campos) {
										if (! strlen ($data[$campos])) {
											$null = true;
										}
									}
								}
								
								if ($null) {
									$this->addError("{$item} y los otros definidos no poseen datos");
								}
								break;
								
							case 'exception' :
								switch ($valor_regla['grupo']) {
									case 'comunidad' : 
										$sql = "
										select codigo from pss.codigos
										where 
											codigo like 'CMI%' or
											codigo like 'RCM%' or 
											codigo like 'TAT%' or 
											codigo like 'ROX001%' or 
											codigo like 'ROX002%' or 
											codigo like 'DSY001%'";
										break;
									default: break;
								}
								
								$codigos = Bdd::getInstance()->query($sql)->getList();
								
								if (! in_array ($data[$valor_regla['campo']] , $codigos)) {
									$this->addError("{$item} no posee un valor permitido para el campo {$valor_regla['campo']}");
								}
								break;
						}
					}
				}
			}
		} else {
			
			$string = implode (';' , $data);
			$campos_data = count ($data);
			$campos = count ($encabezados);
			
			$this->addError("El registro [{$string}] tiene {$campos_data} campos, debe tener {$campos}");
		}
		
		if (empty ($this->_errores)) {
			$this->_validado = true;
		} else {
			$this->_validado = false;
		}
		
		return $this;
		
	}
	
	protected function addError ($error) {
		$this->_errores[] = $error;
	}
	
	public function getError () {
		return $this->_errores;
	}
	
	public function getValidar () {
		return $this->_validado;
	}
	
}
