<?php
class Consultas {

	private 
		$_db,
		$_ruta_sql = '/var/www/sirge2/app/sql/Consultas/Auto/',
		$_ruta_destino = '/var/www/sirge2/data/export/consultas/sistemas/',
		$_ruta_job = ' /var/www/sirge2/funciones/automata.php?id_consulta=';

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


	public function altaConsultaProgramada ($nombre , $job , $ruta , $emails){
		
		$params = [
			$nombre,
			$this->_ruta_sql . $ruta . '.sql',
			$this->_ruta_destino . $ruta . '.txt',
			$job,
			'{' . $emails . '}'
		];
		$sql = "insert into consultas.automaticas (nombre , ruta_sql , ruta_destino , cronjob , destinatarios) values (?,?,?,?,?)";
		$this->_db->query($sql , $params);

		$id = $this->_db->lastId('consultas.automaticas_id_consulta_seq');

		$params = [
			$job . $this->_ruta_job . $id,
			$id
		];

		$sql = "
			update consultas.automaticas 
			set
				cronjob = ?
			where
				id_consulta = ?";
		$this->_db->query($sql , $params);
		print_r($this->_db);
	}
}