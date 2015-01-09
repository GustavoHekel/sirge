<?php
class BDD {
	
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
	
	public static function GetInstance () {
		if (!isset(self::$_instance)){
			self::$_instance = new Bdd();
		}
		return self::$_instance;
	}
	
	public function Query ($sql , $params = array()) {
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
				$this->_results = null;
				$this->_error = $this->_pdo->errorInfo();
			}
		} else {
			$this->_error = $this->_pdo->errorInfo();
		}
		return $this;
	}
	
	public function GetError() {
		return $this->_error;
	}
	
	public function GetErrorInfo() {
		return $this->_error_info;
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
	
}
