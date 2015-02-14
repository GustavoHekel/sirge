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
	require_once 'app/clases/' . $clase . '.php';
});

?>
