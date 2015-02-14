<?php

require_once '../init.php';

if (isset ($_POST) && count ($_POST)) {
	
	$_POST = array_values($_POST);
	
	$params = [];
	$clase 	= array_shift ($_POST);
	$metodo = array_shift ($_POST);
	
	if (isset ($_POST[0])) {
		parse_str($_POST[0], $params);
	}
	
	$instancia = new $clase;
	
	call_user_func_array ([$instancia , $metodo], $params);
}

?>
