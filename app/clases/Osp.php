<?php

class Osp implements Padron
{
    private 
        $_db,
        $_validacion,
        $_lote,
        $_registro,
        $_registro_ori,
        $_registro_data = array(
            'tipo_documento' => '',
            'numero_documento' => '',
            'nombre_apellido' => '',
            'sexo' => '',
            'codigo_os' => '',
            'codigo_postal' => '',
            'tipo_afiliado' => '',
            'lote' => ''
        ),
        $_encabezados = array(
            'tipo_documento',
            'numero_documento',
            'nombre_apellido',
            'sexo',
            'codigo_os',
            'codigo_postal',
            'id_provincia',
            'tipo_afiliado',
            'lote'
        ),
        $_reglas = array(
            'tipo_documento' => array (
				'required' => true,
				'max' => 3
			),
			'numero_documento' => array (
				'required' => true,
				'numeric' => true,
				'min' => 5,
				'max' => 8
			),
			'nombre_apellido' => array (
				'required' => true,
				'max' => 50
			),
			'tipo_afiliado' => array (
				'required' => true,
				'in' => array (
					'A',
					'T'
				)
			) 
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
    
    public function procesar($file_pointer, $lote) {
        $iLote = new Lote();
        $this->_lote = $lote;
        while (!feof($file_pointer)) {
            $this->_registro 	= explode("||" , trim(fgets($file_pointer)));
            if (count ($this->_registro) > 1) {
                $this->_registro_ori = $this->_registro;
                $this->_registro[] = $this->_lote;
                $this->ingresar();
            }
        }
        $iLote->completar($this->_lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
        return $this->_contador;
    }

    public function armarArray() {
        foreach ($this->_registro_data as $campo => $valor) {
            $this->_registro_data[$campo] = $this->_registro[$campo];
        }
    }

    public function ingresar() {
        if (Archivo::comparar($this->_encabezados , $this->_registro)) {
            $this->_registro = array_combine ($this->_encabezados , $this->_registro);
            if ($this->_validacion->validarRegistro($this->_registro , $this->_encabezados , $this->_reglas)->resultado()) {
                $this->ingresarRegistro();
            } else {
                $this->ingresarError($this->_registro_ori , $this->_lote , $this->_validacion->getError());
            }
        } else {
            $this->ingresarError($this->_registro_ori , $this->_lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
        }
    }

    public function ingresarError($registro, $lote, $error) {
        $campos = array ('id_provincia','motivos','registro_rechazado','lote');
        $data = array($_SESSION['grupo'] , $error , implode (';',$registro) , $lote);
        $this->_db->insert('osp.rechazados' , $campos , $data);
        $this->_contador['rechazados']++;
    }

    public function ingresarRegistro() {
        $this->armarArray();
        $sql = "
            INSERT INTO osp.osp_01(
                tipo_documento, numero_documento, nombre_apellido, sexo, codigo_os, 
                codigo_postal, tipo_afiliado , lote)
            VALUES (?, ?, ?, ?, ?, ?, ? , ?);";
        if ($this->_db->query($sql , $this->_registro_data)->getError()){
            $this->ingresarError($this->_registro_ori , $this->_lote , $this->_db->getErrorInfo());
        } else {
            $this->_contador['insertados']++;
        }
    }
}