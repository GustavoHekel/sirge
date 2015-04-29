<?php
header('Access-Control-Allow-Origin: *');  
require_once 'init.php';

echo $_GET['callback'] . '(' . json_encode('AJAX FIN') . ')' ;
