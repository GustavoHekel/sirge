<?php
interface Facturacion
{
    public function procesar($lote , $file_pointer);
    public function ingresarError($registro , $lote , $error);
    public function ingresarRegistro();
    public function armarArray();
    public function ingresar();
}