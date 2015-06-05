<?php

require_once '../../../init.php';

$usuario = new Usuarios();

if (!isset($_POST['conexiones'])) {

	$html = [
		'../../vistas/usuario/perfil.html',
	];

	$diccionario = $usuario->getDatosPerfil($_SESSION['id_usuario']);

	Html::vista($html, $diccionario);

} else {
	//var_dump($usuario->getConexionesEnElAnio($_SESSION['id_usuario']));
	$datos = $usuario->getConexionesEnElAnio($_SESSION['id_usuario']);

	for ($i = 0; $i < count($datos); $i++) {
		$datos[$i]['id_usuario'] = "<text class='id_user'>" . $_SESSION['id_usuario'] . "</text>";
	}

	echo Sirge::jsonDT($datos);
}