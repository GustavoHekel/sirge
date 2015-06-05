<?php

class Usuarios {

	private
	$_db;

	public function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public function login($usuario, $clave) {
		$params  = array($usuario, md5($clave));
		$usuario = $this->_db->findAll('sistema.usuarios', array('usuario = ? and password = ?', $params));
		if ($this->_db->getCount()) {
			$_SESSION['grupo']       = $usuario['id_entidad'];
			$_SESSION['id_menu']     = $usuario['id_menu'];
			$_SESSION['descripcion'] = $usuario['descripcion'];
			$_SESSION['id_usuario']  = $usuario['id_usuario'];

			$params = array($_SESSION['id_usuario'], $_SERVER['REMOTE_ADDR']);
			$sql    = "insert into logs.log_logins (id_usuario , ip) values (?,?)";
			$this->_db->query($sql, $params);

			echo json_encode(true);
		} else {
			echo json_encode(false);
		}
	}

	public static function getEmail($id_usuario) {
		return Bdd::getInstance()->find('email', 'sistema.usuarios', ['id_usuario = ?', [$id_usuario]]);
	}

	public function enviarSugerencia($texto) {

	}

	public function getDatosPerfil($id_usuario) {
		return $this->_db->fquery("datosUsuarioPerfil", [$id_usuario], false)->getResults()[0];
	}

	public function getConexionesEnElAnio($id_usuario) {
		return $this->_db->fquery("conexionesEnElAnio", [$id_usuario], false)->getResults();
	}

	public function guardarDatosPersonales($datos) {

		$id_usuario = $datos['id_usuario'];
		unset($datos['id_usuario']);

		$sql = " UPDATE sistema.usuarios
					SET ";
		$primero = true;

		foreach ($datos as $campo => $valor) {

			if (count($valor) == 0) {
				$valor = NULL;
			} else if (!is_numeric($valor)) {
				$valor = "'" . $valor . "'";
			}

			if ($primero) {
				$sql .= $campo . " = " . $valor;
				$primero = false;
			} else {
				$sql .= ", " . $campo . " = " . $valor;
			}
		}

		$sql .= " WHERE id_usuario = $id_usuario;";

		return $this->_db->query($sql)->getError();
	}

	public function guardarAvatar($datos) {

		$id_usuario = $datos['id_usuario'];
		unset($datos['id_usuario']);

		$datos['ruta_imagen'] = "public/img/users/" . $datos['ruta_imagen'];

		$sql = " UPDATE sistema.usuarios
					SET ";
		$primero = true;

		foreach ($datos as $campo => $valor) {

			if (count($valor) == 0) {
				$valor = NULL;
			} else {
				$valor = "'" . $valor . "'";
			}

			if ($primero) {
				$sql .= $campo . " = " . $valor;
				$primero = false;
			} else {
				$sql .= ", " . $campo . " = " . $valor;
			}
		}

		$sql .= " WHERE id_usuario = $id_usuario;";

		return $this->_db->query($sql)->getError();
	}

	public function guardarPassword($datos) {

		$id_usuario        = $datos['id_usuario'];
		$datos['password'] = md5($datos['password']);
		unset($datos['id_usuario']);

		$sql = " UPDATE sistema.usuarios
					SET ";
		$primero = true;

		foreach ($datos as $campo => $valor) {
			if (count($valor) == 0) {
				$valor = NULL;
			} else {
				$valor = "'" . $valor . "'";
			}

			if ($primero) {
				$sql .= $campo . " = " . $valor;
				$primero = false;
			} else {
				$sql .= ", " . $campo . " = " . $valor;
			}

		}
		$sql .= " WHERE id_usuario = $id_usuario;";

		return $this->_db->query($sql)->getError();
	}

	public function guardarRedesSociales($datos) {

		$id_usuario = $datos['id_usuario'];
		unset($datos['id_usuario']);

		$sql = " UPDATE sistema.usuarios
					SET ";
		$primero = true;

		foreach ($datos as $campo => $valor) {
			if (count($valor) == 0) {
				$valor = NULL;
			} else {
				$valor = "'" . $valor . "'";
			}

			if ($primero) {
				$sql .= $campo . " = " . $valor;
				$primero = false;
			} else {
				$sql .= ", " . $campo . " = " . $valor;
			}

		}
		$sql .= " WHERE id_usuario = $id_usuario;";

		return $this->_db->query($sql)->getError();
	}

}

?>
