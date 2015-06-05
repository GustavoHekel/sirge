<?php

require_once '../../../init.php';

$usuario = new Usuarios();

if (isset($_POST['tipo'])) {

	$tipo                = $_POST['tipo'];
	$_POST['id_usuario'] = $_SESSION['id_usuario'];
	unset($_POST['tipo']);

	switch ($tipo) {

		case 'datos_personales':
			echo $usuario->guardarDatosPersonales($_POST);
			break;
		case 'avatar':
			echo $usuario->guardarAvatar($_POST);
			break;
		case 'password':
			echo $usuario->guardarPassword($_POST);
			break;
		case 'redes_sociales':
			echo $usuario->guardarRedesSociales($_POST);
			break;

		default:
			break;
	}
}

?>