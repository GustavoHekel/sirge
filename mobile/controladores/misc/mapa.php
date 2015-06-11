<?php

require_once '../../init.php';

$bdd = Bdd::getInstance();


$ll = array_map ('floatval' , explode (',' , (str_replace(['(',')'] , ' ' , $_GET['latlong']))));

$sql = "
select
	cuie
	, latitud
	, longitud
	, latitud :: text || ' , ' || longitud :: text as latlong
from
	efectores.efectores e left join
	efectores.datos_geograficos g on e.id_efector = g.id_efector
where
	earth_distance(ll_to_earth({$ll[0]},{$ll[1]}), ll_to_earth(g.latitud, g.longitud)) / 1000 <= 20";
	
$data = $bdd->query($sql)->getResults();

echo $_GET['callback'] . '(' . json_encode($data) . ')';
