<?php

require_once 'init.php';

$_db = Bdd::getInstance();

$_db->insert(array('sigla','descripcion') , 'sistema.sexos' , array('P','PUTOS'));

?>
