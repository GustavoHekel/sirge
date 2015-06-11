<?php
class Consultas {

	private $_db;

	public function __construct (){
		$this->_db = Bdd::getInstance();
	}

	public function listarProgramadas(){

		$periocidad = [
			"MIN",
			"HR",
			"DIA MES",
			"MES",
			"DIA SEM",
			"COMANDO"
		];
		$json = [];
		$sirge = new Sirge();
		$sql = "
			select
				nombre
				, cronjob
				, '<a href=\"#\" id_consulta=\"' || id_consulta || '\"><i class=\"halflings-icon edit\"></i></a>' as editar
				, '<a href=\"#\" id_consulta=\"' || id_consulta || '\"><i class=\"halflings-icon remove\"></i></a>' as eliminar
			from 
				consultas.automaticas";

		$data = $this->_db->query($sql)->getResults();
		foreach ($data as $key => $value) {
			
			$fin['NOMBRE'] = $value['nombre'];

			$cron = array_combine($periocidad , explode(" " , $value['cronjob']));
			array_pop($cron);
			
			foreach ($cron as $campo => $valor){
				$fin[$campo] = $valor;
			}
			$fin['EDITAR'] = $value['editar'];
			$fin['ELIMINAR'] = $value['eliminar'];
			$json[] = $fin;
		}
		
		echo $sirge->jsonDT($json);
	}

}