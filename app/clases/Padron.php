<?php
interface Padron
{
    public function procesar($file_pointer , $lote);
    public function ingresarError($registro , $lote , $error);
    public function ingresarRegistro();
    public function armarArray();
    public function ingresar();
}