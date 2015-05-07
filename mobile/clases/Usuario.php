<?php

class Usuario {
	
	private 
		$_db ,
		$_user = [
			'id_usuario' => 0
		];
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function login ($callback , $email , $pass){
		$sql = "select id_usuario from mobile.usuarios where email = ? and pass = ?";
		$params = [$email , md5($pass)];
		$this->_user['id_usuario'] = $this->_db->query($sql , $params)->get()['id_usuario'];
		echo $callback . '(' . json_encode($this->_user) . ')';
	}

	public function getUserData ($id){
		$sql = "
			select 
				apellido || ', ' || nombre as n
				, extract(years from age(LOCALTIMESTAMP , fecha_nacimiento)) as edad
				, tipo_documento || ' : ' || numero_documento as dni
				, domicilio
				, p.descripcion as provincia
				, nombre
				, apellido
				, fecha_nacimiento
				, email
				, numero_documento
			from
				mobile.usuarios u 
				left join sistema.provincias p
					on u.id_provincia = p.id_provincia
			where
				id_usuario = ?";
		return $this->_db->query($sql , [$id])->get();
	}
	
	private function getProvincia ($id){
		return $this->_db->find('id_provincia' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
	}
	
	public function getSelectProvincia ($id){
		$id_provincia = $this->getProvincia ($id);
		$provincias = $this->_db->query('select * from sistema.provincias order by descripcion')->getResults();
		$select = "<select name='id_provincia'>";
		foreach ($provincias as $id => $data){
			if ($data['id_provincia'] == $id_provincia) {
				$select .= "<option value='{$data['id_provincia']}' selected='selected'>{$data['descripcion']}</option>";
			} else {
				$select .= "<option value='{$data['id_provincia']}'>{$data['descripcion']}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	private function getTipoDocumento ($id){
		return $this->_db->find('tipo_documento' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
	}
	
	public function getSelectTipoDocumento ($id){
		$tipo_doc = $this->getTipoDocumento($id);
		$tipos_doc = [
			'DNI' => 'Documento Nacional de Identidad',
			'CI' => 'Cédula de identidad',
			'LC' => 'Libreta Cívica',
			'LE' => 'Libreta de Enrolamiento',
			'CM' => 'Cédula migratoria'
		];
		
		$select = '<select name="tipo_documento">';
		
		foreach ($tipos_doc as $tipo => $desc) {
			if ($tipo == $tipo_doc) {
				$select .= "<option value='{$tipo}' selected='selected'>{$desc}</option>";
			} else {
				$select .= "<option value='{$tipo}'>{$desc}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	public function getSelectSexo ($id){
		$s = $this->_db->find('sexo' , 'mobile.usuarios' , ['id_usuario = ?' , [$id]]);
		$sexos = [
			'F' => 'Femenino',
			'M' => 'Masculino'
		];
		$select = '<select>';
		foreach ($sexos as $sigla => $desc) {
			if ($s == $sigla) {
				$select .= "<option value='{$sigla}' selected='selected'>{$desc}</option>";
			} else {
				$select .= "<option value='{$sigla}'>{$desc}</option>";
			}
		}
		$select .= '</select>';
		return $select;
	}
	
	public function getEstado ($id){
		$sql = "
			select
				coalesce (p.activo , 'P') as estado
			from
				mobile.usuarios u left join
				beneficiarios.beneficiarios b on u.sexo || u.tipo_documento || u.numero_documento = b.sexo || b.tipo_documento || b.numero_documento left join
				(
					select
						clave_beneficiario
						, activo
					from
						beneficiarios.beneficiarios_periodos
					where
						periodo = (select max (periodo) from beneficiarios.beneficiarios_periodos)
				) p on b.clave_beneficiario = p.clave_beneficiario
			where
				u.id_usuario = ?";
		$estado = $this->_db->query($sql , [$id])->get()['estado'];
		
		switch ($estado){
			case 'S': 
				$array['ESTADO'] = 'Activo';
				$array['CSS_ESTADO'] = 'success';
				$array['ICONO_ESTADO'] = 'check';
				break;
			case 'N': 
				$array['ESTADO'] = 'No activo';
				$array['CSS_ESTADO'] = 'warning';
				$array['ICONO_ESTADO'] = 'warning';
				break;
			case 'P': 
				$array['ESTADO'] = 'No inscripto';
				$array['CSS_ESTADO'] = 'warning';
				$array['ICONO_ESTADO'] = 'exclamation';
				break;
		}
		return $array;
	}
	
	public function getPracticasUltimoAnio ($id){
		$sql = "
			select
				count(*) as c
			from
				mobile.usuarios u left join
				beneficiarios.beneficiarios b on u.sexo || u.tipo_documento || u.numero_documento = b.sexo || b.tipo_documento || b.numero_documento left join
				prestaciones.prestaciones p on b.clave_beneficiario = p.clave_beneficiario
			where
				u.id_usuario = ?
				and fecha_prestacion is not null";
				
		return $this->_db->query($sql , [$id])->get()['c'];
	}
	
	public function getListadoPracticas ($id){
		$sql ="
			select
				a.*
				, e.nombre
			from 
				(
				select
					p.fecha_prestacion
					, h.*
					, cg.descripcion
					, tp.descripcion as tipo
					, p.efector
				from
					mobile.usuarios u left join
					beneficiarios.beneficiarios b on u.sexo || u.tipo_documento || u.numero_documento = b.sexo || b.tipo_documento || b.numero_documento left join
					prestaciones.prestaciones p on b.clave_beneficiario = p.clave_beneficiario left join
					pss.codigos c on p.codigo_prestacion = c.codigo_prestacion left join
					mobile.tipo_hc h on c.tipo = h.tipo left join
					pss.codigos_grupos cg on p.codigo_prestacion = cg.codigo_prestacion left join
					pss.grupos_etarios ge on cg.id_grupo_etario = ge.id_grupo_etario left join
					pss.tipos_prestacion tp on c.tipo = tp.tipo_prestacion
				where
					u.id_usuario = ?
					and fecha_prestacion is not null
					and age(fecha_prestacion , u.fecha_nacimiento) between edad_min :: interval and edad_max :: interval 
				) a left join
				efectores.efectores e on a.efector = e.cuie";
		$data = $this->_db->query($sql , [$id])->getResults();
		$linea = '';
		foreach ($data as $index => $row) {
			$linea .= "
			<li>
			  <div class='timeline-badge {$row['clase']}'><i class='fa fa-stethoscope'></i></div>
			  <div class='timeline-panel'>
				<div class='timeline-heading'>
				  <h4 class='timeline-title'>{$row['tipo']}</h4>
				  <p><small class='text-muted'><i class='fa fa-clock-o'></i> {$row['fecha_prestacion']}</small></p>
				</div>
				<div class='timeline-body'>
				  <p>
					  {$row['nombre']} <br/>
					  {$row['descripcion']}
				  </p>
				</div>
			  </div>
			</li>";
			
		}
		return $linea;
	}
	
	
	
	
}

