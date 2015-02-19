<?php

require_once '../init.php';

if (isset ($_REQUEST) && count ($_REQUEST)) {
	
	$_REQUEST = array_values($_REQUEST);
	
	$params = [];
	$clase 	= array_shift ($_REQUEST);
	$metodo = array_shift ($_REQUEST);
	
	if (isset ($_REQUEST[0])) {
		parse_str($_REQUEST[0], $params);
	}
	
	$instancia = new $clase;
	
	call_user_func_array ([$instancia , $metodo], $params);
}

?>
