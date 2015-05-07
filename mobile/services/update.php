<?php 

require_once '../init.php';

echo $_REQUEST['callback'] . '(' . json_encode($_REQUEST) . ')';