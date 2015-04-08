<?php

abstract class Padron
{
    public function __construct(){
        $this->_db = Bdd::getInstance();
        $this->_validacion = new Validar();
    }
    
    public function ingresarRegistro($registro){
        $this->_registro_data = $this->armarArray($this->_registro_data , $registro);
        if ($this->_db->query($this->_sql , $this->_registro_data)->getError()){
            $lote = array_pop($registro);
            $this->ingresarError($registro , $lote , $this->_db->getErrorInfo());
        } else {
            $this->_contador['insertados']++;
        }
    }
    
    public function ingresar ($registro){
        if (Archivo::comparar($this->_encabezados , $registro)) {
            $registro = array_combine ($this->_encabezados , $registro);
            if ($this->_validacion->validarRegistro($registro , $this->_encabezados , $this->_reglas)->resultado()) {
                $this->ingresarRegistro($registro);
            } else {
                $lote = array_pop($registro);
                $this->ingresarError($registro , $lote , $this->_validacion->getError());
            }
        } else {
            $lote = array_pop($registro);
            $this->ingresarError($registro , $lote , 'EL NUMERO DE CAMPOS INFORMADOS NO ES CORRECTO');
        }
    }
    
    public function procesar($file_pointer, $lote){
        $iLote = new Lote();
        $delimitador_campos = $this->getDelimiter(get_class($this));
        fgets ($file_pointer);
        while (!feof($file_pointer)) {
            $registro = explode($delimitador_campos , trim(fgets($file_pointer)));
            if (count ($registro) > 1) {
                $registro[] 	= $lote;
                $this->ingresar($registro);
            }
        }
        $iLote->completar($lote , $this->_contador['insertados'] , $this->_contador['rechazados'] , $this->_contador['modificados']);
        return $this->_contador;
    }
    
    public function ingresarError($registro , $lote , $error){
        $esquema = strtolower(get_class($this));
        $campos = array ('id_provincia','motivos','registro_rechazado','lote');
        $data = array($_SESSION['grupo'] , $error , implode (';' , $registro) , $lote);
        $this->_db->insert("{$esquema}.rechazados", $campos , $data);
        $this->_contador['rechazados']++;
    }
    
    public function armarArray($registro_data , $registro){
        foreach ($registro_data as $campo => $valor) {
            $registro_data[$campo] = $registro[$campo];
        }
        return $registro_data;
    }
    
    protected function getDelimiter($clase){
        $d = ';';
        switch (strtolower($clase)){
            case 'sss':     $d = "|"; break;
            case 'profe':   $d = "\t"; break;
            case 'osp':     $d = "||"; break;
        }   
        return $d;
    }
}