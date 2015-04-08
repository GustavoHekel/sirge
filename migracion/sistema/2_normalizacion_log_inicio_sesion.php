<?php
require_once '../conexion.php';

$sql = "select * from sistema.log_inicio_sesion";
$res = pg_query ($sql);

while ($reg = pg_fetch_assoc ($res)) {
	
	$sql2 = "insert into logs.log_logins (id_usuario , fecha_login , ip) values ('" . implode ("','" , $reg) . "')";
	pg_query ($sql2);
	
}

$sql = "DROP TABLE sistema.log_inicio_sesion;";

pg_query ($sql);
