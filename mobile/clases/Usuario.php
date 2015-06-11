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
		$sql = "select id_usuario from mobile.usuarios where email = ? and pass = ? and validado = 'S'";
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
	
	public function alta ($callback , $data){
		
		$e = new Email();
		$uniqueid = md5(uniqid());
		
		$data = json_decode($data , true);
		$return = ['alta' => 0];
		$params = [
			$data['nombre'],
			$data['apellido'],
			$data['sexo'],
			$data['tipo_documento'],
			$data['ndoc'],
			$data['fnac'],
			$data['email'],
			md5($data['pass']),
			$data['telefono'],
			$data['provincia'],
			'N',
			$uniqueid
		];
		
		$sql = "
			insert into mobile.usuarios (nombre , apellido , sexo , tipo_documento , numero_documento , fecha_nacimiento , email , pass , numero , id_provincia , validado , uniqueid)
			values (?,?,?,?,?,?,?,?,?,?,?,?)";
		$return['alta'] = $this->_db->query($sql , $params)->getCount();
		
		if ($return['alta']){
			if ($this->existe($data)){
				$e->enviarValidacion($data['email'] , 'Valide sus datos' , 'vistas/email/validacion.html' , $uniqueid);
			} else {
				$e->enviarValidacion($data['email'] , 'Importante' , 'vistas/email/no_encontrado.html' , $uniqueid);
			}
		}
		
		echo $callback . '(' . json_encode($return) . ')';
	}
	
	private function existe ($data){
		
		$params = [
			$data['tipo_documento'],
			$data['sexo'],
			$data['ndoc'],
			$data['fnac']
		];
		
		$sql = "
			select count(*) as c 
			from beneficiarios.beneficiarios 
			where
				tipo_documento = ?
				and sexo = ?
				and numero_documento = ?
				and fecha_nacimiento = ?";
		return $this->_db->query($sql , $params)->get()['c'];
	}
	
	public function validar ($callback , $email , $uniqueid){
		$params = [$email , $uniqueid];
		$sql = "
			update mobile.usuarios
			set 
				validado = 'S'
				and fecha_validado = localtimestamp
			where
				email = ?
				and uniqueid = ?";
		$validado = $this->_db->query($sql , $params)->getCount();
		if ($validado){
			echo 'Gracias por validar su email, ya puede ingresar a la aplicaci&oacute;n';
		} else {
			echo 'Error';
		}
	}

	public function informarProblema ($callback , $problema , $texto , $user){
		$e = new Email();
		$mail = $this->_db->find('email' , 'mobile.usuarios' , ['id_usuario = ?', [$user]]);
		$html = "
			<div>
				<h1>{$mail}</h1>
				<h2>{$problema}</h2>
				<h3>{$texto}</h3>
			</div>";

		$sql = "insert into mobile.reportes_problemas (id_usuario , tipo_problema , descripcion) values (?,?,?)";
		$params = [$user , $problema , $texto];

		if ($this->_db->query($sql , $params)->getCount()){
			$e->enviarProblema($html);
			$data['enviado'] = 1;
		} else {
			$data['enviado'] = 0;
		}

		echo $callback . '(' . json_encode($data) . ')';
		
	}

}

