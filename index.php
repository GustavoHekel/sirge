<?php
require_once 'init.php';

$menu = new HTML();

if (Seguridad::getNavegador()) {
	
	if (Seguridad::getSession()) {
		
		$diccionario = array (
			'NOMBRE_USUARIO' => $_SESSION['descripcion'] ,
			'MENU_IZQUIERDO' => $menu->armarMenu($_SESSION['id_menu'])
		);
		
		$html = array (
			'app/vistas/index/header.html' ,
			'app/vistas/index/banner.html' ,
			'app/vistas/index/menu_izquierdo.html' ,
			'app/vistas/index/footer.html'
		);
		
		HTML::Vista($html , $diccionario);
		
	} else {
		require_once 'app/vistas/index/header.html';
		require_once 'app/vistas/index/login.html';
	}
} else {
	echo 'Navegador no soportado';
}

?>

