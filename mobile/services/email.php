<?php
require '../init.php';

$db = Bdd::getInstance();
$params = [$_GET['correo']];
$sql = "select count(*) from mobile.usuarios where email = ?";

echo $db->query($sql , $params)->getCount();
