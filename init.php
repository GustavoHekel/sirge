<?php
session_start();

$GLOBALS['configuracion'] = array(
	'postgresql' => array (
		'host' => '192.6.0.56',
		'usuario' => 'postgres',
		'password' => '110678',
		'db' => 'sirge2'
	)
);

spl_autoload_register(function($clase){
	
	$ruta = '';
	
	switch ($clase) {
		case 'FPDF' : $ruta = 'app/clases/pdf/' . $clase . '.php'; break;
		default : $ruta = 'app/clases/' . $clase . '.php';
	}
	
	require_once $ruta;
});

?>
