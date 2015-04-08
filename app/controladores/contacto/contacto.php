<?php

require_once '../../../init.php';

$html = [
  '../../vistas/contacto/formulario.html'
];

$diccionario = [
    'NOMBRE_USUARIO' => $_SESSION['descripcion'],
    'EMAIL_USUARIO' => Usuarios::getEmail($_SESSION['id_usuario'])
];

Html::vista($html , $diccionario);