<?php
class Prestaciones extends Padron
{
    protected 
        $_db,
        $_validacion,
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
        $_registro_data = array(
            'estado' => '',
            'cuie' => '',
            'numero_comprobante' => '',
            'codigo_prestacion' => '',
            'subcodigo_prestacion' => '',
            'importe' => '',
            'fecha_prestacion' => '',
            'clave_beneficiario' => '',
            'tipo_documento' => '',
            'clase_documento' => '',
            'numero_documento' => '',
            'orden' => '',
            'lote' => ''
        ),
        $_prestacion_repo = array(
		
		),
		$_contador = array(
			'insertados' => 0 ,
			'rechazados' => 0 ,
			'modificados' => 0
		),
        $_sql = "
            INSERT INTO prestaciones.p_01 (
                estado, efector, numero_comprobante, codigo_prestacion, subcodigo_prestacion, 
                precio_unitario, fecha_prestacion, clave_beneficiario, tipo_documento, 
                clase_documento, numero_documento, orden, lote) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   
    public function ingresar($registro) {
        if (Archivo::comparar($this->_encabezados , $registro)) {
            $registro = array_combine ($this->_encabezados , $registro);
            if ($this->_validacion->validarRegistro($registro , $this->_encabezados , $this->_reglas)->resultado()) {
                $this->verOperacion($registro);
            } else {
                $lote = array_pop($registro);
                $this->ingresarError($registro , $lote , $this->_validacion->getError());
            }
        } else {
            $lote = array_pop($registro);
            $this->ingresarError($registro , $lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
        }
    }
    
    private function verOperacion ($registro){
        $operacion = array_shift ($registro);
        switch ($operacion) {
            case 'A':
                $this->ingresarRegistro($registro);
                break;
            case 'M':
                $this->modificarRegistro($registro);
                break;
        }
    }
    
    public function getPrestacionesProvincia($id_provincia) {
		$sql = "select to_char (sum (registros_in) , '999,999,999') as r from sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida where id_provincia = '$id_provincia' and id_padron = 1 and l.id_estado = 1";
		return $this->_db->query($sql)->get()['r'];
	}
    
    private function armarParams($registro){
        $params = array(
			$registro['numero_comprobante'],
			$registro['codigo_prestacion'],
			$registro['subcodigo_prestacion'],
			$registro['fecha_prestacion'],
			$registro['clave_beneficiario'],
			$registro['orden']
		);
        return $params;
    }
    
    private function buscarPrestacion ($registro) {
        $params = $this->armarParams($registro);
        return $this->_db->findCount('prestaciones.prestaciones' , [ 
            'numero_comprobante = ? 
            and codigo_prestacion = ?
            and subcodigo_prestacion = ?
            and fecha_prestacion = ?
            and clave_beneficiario = ?
            and orden = ?' , $params]);
	}
    
    private function modificarEstado ($registro) {
        $params = $this->armarParams($registro);
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
		$this->_db->query($sql , $params);		
		$this->_contador['modificados'] ++;
	}
    
    private function modificarRegistro($registro){
        if ($this->buscarPrestacion($registro)) {
			$this->modificarEstado($registro);
		} else {
            $lote = array_pop($registro);
			$this->ingresarError($registro , $lote , 'LA PRESTACIÓN QUE SE INTENTA MODIFICAR NO EXISTE');
		}
    }
}
