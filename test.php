<?php
header('Access-Control-Allow-Origin: *');  
require_once 'init.php';

$db = Bdd::getInstance();

//echo json_encode($_SERVER['REQUEST_URI']);
//echo $_GET['callback'] . '(' . json_encode($_GET) . ')';

$sql = "select * from sistema.usuarios where usuario = ? and password = ?";
$params = array(
	$_GET['numero_documento']
	, md5 ($_GET['password'])
);

$user['existe'] = $db->query($sql , $params)->getCount();

echo $_GET['callback'] . '(' . json_encode($user) . ')';
