<?php
session_start();

$GLOBALS['configuracion'] = array(
	'postgresql' => array (
		'host' => '192.6.0.71',
		'usuario' => 'postgres',
		'password' => '110678',
		'db' => 'sirge2'
	)
);

spl_autoload_register(function($clase){
	
	switch ($clase) {
		default : $ruta = 'clases/' . $clase . '.php';
	}
	
	require_once $ruta;
});
