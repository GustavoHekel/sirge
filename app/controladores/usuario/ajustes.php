<?php

require_once '../../../init.php';

$usuario = new Usuarios();

$html = [
	'../../vistas/usuario/ajustes.html',
];

$diccionario = $usuario->getDatosPerfil($_SESSION['id_usuario']);

if ($diccionario['facebook'] == "#") {
	$diccionario['facebook'] = "";
}
if ($diccionario['twitter'] == "#") {
	$diccionario['twitter'] = "";
}
if ($diccionario['linkedin'] == "#") {
	$diccionario['linkedin'] = "";
}
if ($diccionario['google_plus'] == "#") {
	$diccionario['google_plus'] = "";
}
if ($diccionario['skype'] == "#") {
	$diccionario['skype'] = "";
}

if ($diccionario['fecha_nacimiento'] != "") {
	$fecha                           = DateTime::createFromFormat('Y-m-d', $diccionario['fecha_nacimiento']);
	$diccionario['fecha_nacimiento'] = $fecha->format('d-m-Y');
}

Html::vista($html, $diccionario);