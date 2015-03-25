<?php
class Prestaciones implements Padron
{
    private
        $_db,
        $_validacion,
        $_prestacion,
        $_prestacion_ori,
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
		);
   
    public function __construct() {
        $this->_db = Bdd::getInstance();
        $this->_validacion = new Validar(true);
    }
    
    public function armarArray() {
        foreach ($this->_prestacion_data as $campo => $valor) {
			$this->_prestacion_data[$campo] = $this->_prestacion[$campo];
		}
    }

    public function ingresar() {
        if (Archivo::comparar($this->_encabezados , $this->_prestacion)) {
            $this->_prestacion = array_combine ($this->_encabezados , $this->_prestacion);
            if ($this->_validacion->validarRegistro($this->_prestacion , $this->_encabezados , $this->_reglas)->resultado()) {
                $operacion = array_shift ($this->_prestacion);
                $this->verOperacion($operacion);
            } else {
                $this->ingresarError($this->_prestacion_ori , $this->_lote , $this->_validacion->getError());
            }
        } else {
              $this->ingresarError($this->_prestacion_ori , $this->_lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
        }
    }
    
    private function verOperacion ($operacion){
        switch ($operacion) {
            case 'A':
                $this->ingresarRegistro();
                break;
            case 'M':
                $this->modificarRegistro();
                break;
        }
    }
    

    public function ingresarError($registro, $lote, $error) {
        $campos = array ('id_provincia','motivos','registro_rechazado','lote');
        $data = array($_SESSION['grupo'] , $error , implode (';',$registro) , $lote);
        $this->_db->insert('prestaciones.rechazados', $campos , $data);
        $this->_contador['rechazados']++;
    }

    public function ingresarRegistro() {
        $this->armarArray();
        $sql = "
            INSERT INTO prestaciones.p_01 (
                estado, efector, numero_comprobante, codigo_prestacion, subcodigo_prestacion, 
                precio_unitario, fecha_prestacion, clave_beneficiario, tipo_documento, 
                clase_documento, numero_documento, orden, lote) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($this->_db->query($sql , $this->_prestacion_data)->getError()){
            $this->ingresarError($this->_prestacion_ori , $this->_lote , $this->_db->getErrorInfo());
        } else {
            $this->_contador['insertados']++;
        }
    }

    public function procesar($file_pointer, $lote) {
        $iLote = new Lote();
        fgets ($file_pointer);
        $this->_lote = $lote;
        while (!feof($file_pointer)) {
            $this->_prestacion 	= explode(";" , trim(fgets($file_pointer)));
            if (count ($this->_prestacion) > 1) {
                $this->_prestacion_ori 	= $this->_prestacion;
                $this->_prestacion[] 	= $this->_lote;
                $this->ingresar();
            }
        }
        $iLote->completar($this->_lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
        return $this->_contador;
    }
    
    public function getPrestacionesProvincia($id_provincia) {
		$sql = "select to_char (sum (registros_in) , '999,999,999') as r from sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida where id_provincia = '$id_provincia' and id_padron = 1 and l.id_estado = 1";
		return $this->_db->query($sql)->get()['r'];
	}
    
    private function buscarPrestacion () {
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
		return $this->_db->query($sql , $params)->getCount();
	}
    
    private function modificarEstado () {
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
		$this->_db->query($sql , $params);		
		$this->_contador['modificados'] ++;
	}
    
    private function modificarRegistro(){
        if ($this->buscarPrestacion()) {
			$this->modificarEstado();
		} else {
			$this->ingresarError($this->_prestacion_ori , $this->_lote , 'LA PRESTACIÓN QUE SE INTENTA MODIFICAR NO EXISTE');
		}
    }
}
