<?php
require '../init.php';

$data = [
	'existe' => 0
];

$db = Bdd::getInstance();
$params = [$_GET['correo']];
$sql = "select count(*) as c from mobile.usuarios where email = ?";
$data['existe'] = $db->query($sql , $params)->get()['c'];
echo $_GET['callback'] . '(' . json_encode($data) . ')';
