<?php
class Bddtest {

	private static $_instance = null;
	private $_pdo,
	$_query,
	$_error      = false,
	$_error_info = null,
	$_results,
	$_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('pgsql:host=192.6.0.119;dbname=sirge;user=' . Configuracion::Get('postgresql/usuario') . ';password=' . Configuracion::Get('postgresql/password'));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new Bddtest();
		}
		return self::$_instance;
	}

	public function query($sql, $params = array()) {
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$i = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($i, $param);
					$i++;
				}
			}
			if ($this->_query->execute()) {
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_results    = null;
				$this->_error      = true;
				$this->_error_info = $this->_pdo->errorInfo();
			}
		} else {
			$this->_error      = true;
			$this->_error_info = $this->_pdo->errorInfo();
		}
		return $this;
	}

	public function fquery($file, $params = array(), $ajax = TRUE) {
		$clase = debug_backtrace()[1]['class'];

		if ($ajax) {
			$ruta = "../app/sql/{$clase}/" . $file . '.sql';
		} else {
			$ruta = "../../../app/sql/{$clase}/" . $file . '.sql';
		}
		$sql = file_get_contents($ruta);
		return $this->query($sql, $params);
	}

	public function getError() {
		return $this->_error;
	}

	public function getErrorInfo() {
		return $this->_error_info[2];
	}

	public function getCount() {
		return $this->_count;
	}

	public function getResults() {
		return $this->_query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getList() {
		return $this->_query->fetchAll(PDO::FETCH_COLUMN);
	}

	private function accion($accion, $tabla, $where = array()) {
		if (count($where)) {
			$campo  = $where[0];
			$params = $where[1];
			$sql    = "{$accion} from {$tabla} where {$campo}";
		} else {
			$params = array();
			$sql    = "{$accion} from {$tabla}";
		}

		if (!$this->query($sql, $params)->getError()) {
			return $this;
		} else {
			print_r($this->getErrorInfo());
		}
	}

	public function get() {
		return $this->_query->fetch(PDO::FETCH_ASSOC);
	}

	public function findSum($campo, $tabla, $where = array()) {
		return $this->accion("select sum({$campo}) as sum", $tabla, $where)->get()['sum'];
	}

	public function findCount($tabla, $where = array()) {
		return $this->accion("select count(*) as count ", $tabla, $where)->get()['count'];
	}

	public function findAll($tabla, $where = array()) {
		return $this->accion("select * ", $tabla, $where)->_query->fetch(PDO::FETCH_ASSOC);
	}

	public function find($campo, $tabla, $where = array()) {
		return $this->accion("select {$campo}", $tabla, $where)->_query->fetch(PDO::FETCH_ASSOC)[$campo];
	}

	public function insert($tabla, $campos = array(), $data = array()) {
		$placeholder = '(' . implode(',', array_fill(0, count($campos), '?')) . ')';
		$sql         = "insert into {$tabla} (" . implode(',', $campos) . ") values {$placeholder}";
		if (!$this->query($sql, $data)->getError()) {
			return true;
		} else {
			return false;
		}
	}

	public function delete($tabla, $where) {

	}

	public function lastId($sequencia) {
		return $this->_pdo->lastInsertId($sequencia);
	}
}
