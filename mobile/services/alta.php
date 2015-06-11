<?php

require_once '../init.php';

$return['id'] = $db->query($sql , $params)->lastId();

$u->enviarEmail($return['id']);

echo $_REQUEST['callback'] . '(' . json_encode($return) . ')';
