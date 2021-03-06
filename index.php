<?php
require_once 'init.php';

$Html5 = new Html();

if (Seguridad::getNavegador()) {
	
    if (Seguridad::getSession()) {

        $diccionario = array (
            'NOMBRE_USUARIO' => $_SESSION['descripcion'] ,
            'MENU_IZQUIERDO' => $Html5->armarMenu($_SESSION['id_menu'])
        );

        $Html = array (
            'app/vistas/index/header.html' ,
            'app/vistas/index/banner.html' ,
            'app/vistas/index/menu_izquierdo.html' ,
            'app/vistas/index/footer.html'
        );

        Html::vista($Html , $diccionario);

    } else {
            require_once 'app/vistas/index/header.html';
            require_once 'app/vistas/index/login.html';
    }
} else {
	echo 'Navegador no soportado';
}