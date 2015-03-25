<?php
//$servidor	= "localhost";
$servidor	= '192.6.0.56';
$base		= "sirge";
$user		= "postgres";
//$password	= "villadelsur";
$password 	= '110678';
if(! $conn =  pg_connect("host=$servidor dbname=$base user=$user password=$password")) {
	die ("Error en la conexión a la base de datos");
}
?>
