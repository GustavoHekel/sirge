<?php
class Seguridad {
	
	public static function getNavegador () {
		$n = get_browser (null , true);
		return $n['browser'] == 'IE' ? false : true ;
	}
	
	public static function getSession () {
		return isset ($_SESSION['id_usuario']) ? true : false ;
	}
	
	public function cerrarSesion () {
		session_destroy();
	}
}
