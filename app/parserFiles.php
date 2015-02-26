<?php

require_once '../init.php';

if (isset ($_FILES) && count ($_REQUEST)) {
	
	$_REQUEST = array_values($_REQUEST);
	
	$url_params = [];
	$clase 		= array_shift ($_REQUEST);
	$metodo 	= array_shift ($_REQUEST);
	
	$size = count ($_REQUEST);
	
	if ($size) {
		parse_str(implode ('&' , $_REQUEST), $url_params);
	}
	
	$params = array (
		$url_params
		, $_FILES
	);
	
	$instancia = new $clase;
	
	call_user_func_array ([$instancia , $metodo], $params);
}

?>
