<?php

class Usuarios {
	
	public function Login ($usuario , $clave) {
		
		$params = array(
			$usuario ,
			md5 ($clave)
		);
		
		$sql = "
			select 
				* 
			from
				sistema.usuarios
			where
				usuario = ?
				and password = ?";
			
		if (BDD::GetInstance()->Query($sql , $params)->GetCount()) {
			
			$usuario = BDD::GetInstance()->Query($sql , $params)->GetRow();
			
			$_SESSION['grupo'] 			= $usuario['id_entidad'];
			$_SESSION['id_menu']		= $usuario['id_menu'];
			$_SESSION['descripcion'] 	= $usuario['descripcion'];
			$_SESSION['id_usuario'] 	= $usuario['id_usuario'];
			
			$sql = "
				insert 
					into logs.log_logins (id_usuario , ip)
				values 
					(?,?)";
					
			$params = array (
				$_SESSION['id_usuario'] 
				, $_SERVER['REMOTE_ADDR']
			);
			
			BDD::GetInstance()->Query($sql , $params);
			
			echo json_encode(true);
		} else {
			echo json_encode(false);;
		}
	}
	
}

?>
