<?php 

require_once '../init.php';

$db = Bdd::getInstance();

$sql = "
	UPDATE
		mobile.usuarios
	SET
		pass = ?
	WHERE
		id_usuario = ?
		and pass = ?";
$params = [
	md5($_REQUEST['pass_new']),
	$_REQUEST['user'],
	md5($_REQUEST['pass_actual'])
];

$data['actualizado'] = $db->query($sql , $params)->getCount();

echo $_REQUEST['callback'] . '(' . json_encode($_REQUEST) . ')';
