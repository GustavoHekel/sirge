<?php

interface Facturacion
{
	public function procesar($lote , $file_pointer);
	public function aceptar($lote);
	public function rechazar($lote);
	public function ingresarError($registro , $lote , $error);
	public function ingresarRegistro($registro = array());
	public function armarArray();
}

?>
