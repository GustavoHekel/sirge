<?php

interface Facturacion
{
	public function procesar($lote , $file_pointer);
	public function ingresarError($registro , $lote , $error);
	public function ingresarRegistro($registro);
	public function armarArray();
}

?>
