<?php
class Bdd {
	
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_error_info = null,
			$_results,
			$_count = 0;
			
	private function __construct () {
		try {
			$this->_pdo = new PDO('pgsql:host=' . Configuracion::Get('postgresql/host') . ';dbname=' . Configuracion::Get('postgresql/db') . ';user=' . Configuracion::Get('postgresql/usuario') . ';password=' . Configuracion::Get('postgresql/password'));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public static function getInstance () {
		if (!isset(self::$_instance)){
			self::$_instance = new Bdd();
		}
		return self::$_instance;
	}
	
	public function query ($sql , $params = array()) {
		$this->_error = false;
		
		if ($this->_query = $this->_pdo->prepare($sql)){
			$i = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($i , $param );
					$i++;
				}
			}
			
			if ($this->_query->execute()){
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_results 	= null;
				$this->_error 		= true ;
				$this->_error_info 	= $this->_pdo->errorInfo();
			}
		} else {
			$this->_error 		= true ;
			$this->_error_info 	= $this->_pdo->errorInfo();
		}
		return $this;
	}
	
	public function getError() {
		return $this->_error;
	}
	
	public function GetErrorInfo() {
		return $this->_error_info[2];
	}
	
	public function GetCount() {
		return $this->_count;
	}
	
	public function GetRow() {
		return $this->_query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function GetResults() {
		return $this->_query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function GetList() {
		return $this->_query->fetchAll(PDO::FETCH_COLUMN);
	}
	
	private function Accion ($accion , $tabla , $where = array()) {
		if (count ($where)) {
			
			$operadores = array ('=' , '<' , '>' , '<=' , '>=');
			$campo 		= $where[0];
			$operador 	= $where[1];
			$valor 		= $where[2];
			
			if (in_array ($operador , $operadores)) {
				$sql = "{$accion} from {$tabla} where {$campo} {$operador} ?";
				
				if (!$this->query($sql , array ($valor))->GetError()) {
					return $this;
				}
			}
		}
		return false;
	}
	
	public function select ($tabla , $where = array()) {
		return $this->Accion("select * " , $tabla , $where)->GetRow();
	}
	
	public function Delete ($tabla , $where) {}
	
	public function lastId ($sequencia) {
		return $this->_pdo->lastInsertId($sequencia);
	}
}

