<?php
class Configuracion {
	
	public static function Get ($ruta = null) {
		if ($ruta) {
			$configuracion = $GLOBALS['configuracion'];
			$ruta = explode ('/' , $ruta);
			
			foreach ($ruta as $parte) {
				if (isset ($configuracion[$parte])) {
					$configuracion = $configuracion[$parte];
				}
			}
			return $configuracion;
		}
	}
}
