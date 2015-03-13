<?php

class Dashboard
{
	
	private 
		$_db,
		$_sirge;
	
	public function __construct() {
		$this->_db = Bdd::getInstance();
		$this->_sirge = new Sirge();
	}
	
	public function cantidadPrestaciones() {
		$sql = "select to_char (sum (registros_in) , '99,999,999') as cantidad from sistema.lotes l left join sistema.subidas s on l.id_subida = s.id_subida where id_padron = 1 and l.id_estado = 1";
		$this->_db->query($sql)->get()['cantidad'];
	}
	
	public function cantidadEfectores() {
		$sql = "select count (*) as cantidad from efectores.efectores";
		return $this->_db->query($sql)->get()['cantidad'];
	}
	
	public function cantidadUsuarios() {
		$sql = "select count (*) as cantidad from sistema.usuarios";
		return $this->_db->query($sql)->get()['cantidad'];
	}
	
	public function cantidadBeneficiarios() {
		$sql = "select to_char (count (*) , '99,999,999') as cantidad from beneficiarios.beneficiarios";
		return $this->_db->query($sql)->get()['cantidad'];
	}
	
	public function detalleTotales($id_total) {
		switch ($id_total) {
			case 1 : 
				$sql = "
					select
						descripcion as nombre
						, to_char (sum (registros_in) , '99,999,999') as total
					from 
						sistema.lotes l left join
						sistema.subidas s on l.id_subida = s.id_subida left join
						sistema.provincias p on l.id_provincia = p.id_provincia
					where 
						id_padron = 1
						and l.id_estado = 1
					group by 1
					order by 1";
			break;
			case 2 :
				$sql = "
					select
						p.descripcion as nombre
						, to_char (count (*)  , '99,999,999') as total
					from
						efectores.efectores e left join
						efectores.datos_geograficos g on e.id_efector = g.id_efector left join
						sistema.provincias p on g.id_provincia = p.id_provincia
					where id_estado = 1
					group by 1
					order by 1";
			break;
			case 3 :
				$sql = "
					select
						p.descripcion as nombre
						, to_char (count(*) , '99,999,999') as total
					from
						beneficiarios.beneficiarios b left join
						sistema.provincias p on b.id_provincia_alta = p.id_provincia
					group by 1
					order by 1";
			break;
			case 4 :
				$sql = "
					select
						e.descripcion
						, to_char (count (*) , '999,999') as total
					from
						sistema.usuarios u left join
						sistema.entidades e on u.id_entidad = e.id_entidad
					group by 1
					order by 1";
			break;
		}
		return $this->_sirge->jsonDT($this->_db->query($sql)->getResults() , true);
	}
	
	public function detallePUCO () {
		$sql = "
			select
				o.nombre_grupo as \"nombre entidad / OSP\"
				, a.codigo_os as \"codigo_OSP\"
				, a.periodo as \"periodo (PUCO)\"
				, a.registros_insertados as aceptados
				, a.registros_rechazados as rechazados
				, a.registros_totales as totales
				, round (((a.registros_insertados :: numeric / b.registros_insertados) -1 ) * 100 , 2) :: text || '%' as variacion
			from 
				puco.grupos_obras_sociales o left join (
					select * 
					from puco.procesos_obras_sociales
					where periodo = (extract (year from localtimestamp) :: text || lpad ((extract (month from localtimestamp) :: text) , 2 , '0')) :: int --". date ('Ym') ." 
				) a on o.grupo_os = a.codigo_os left join (
					select * 
					from puco.procesos_obras_sociales
					where periodo = (extract (year from (localtimestamp - interval '1 month')) :: text || lpad (extract (month from (localtimestamp - interval '1 month')) :: text , 2 , '0')) :: int
				) b on a.codigo_os = b.codigo_os";
		return $this->_sirge->jsonDT($this->_db->query($sql)->getResults() , true);
	}
	
	public function insertarComentario ($comentario) {
		$sql = "insert into sistema.comentarios (id_usuario , comentario) values (?,?)";
		$params = array( $_SESSION['id_usuario'] , $comentario );
		$this->_db->query($sql , $params);
		return $this->listadoComentarios(true);
	}
	
	public function detalleVisitas () {
		$sql = "
			select
				p.descripcion as nombre
				, count (*) as cantidad
			from
				logs.log_logins l left join
				sistema.usuarios u on l.id_usuario = u.id_usuario left join
				sistema.entidades p on u.id_entidad = p.id_entidad
			where
				extract (year from fecha_login) = " . date ('Y') . "
				and extract (month from fecha_login) = " . date ('m') . "
			group by 1
			order by 1";
		return $this->_sirge->jsonDT($this->_db->query($sql)->getResults() , true);
	}
	
	public function detallePadron ($id_padron) {
		$sql = "
			select 
				descripcion as nombre
				, lote
				, registros_in
				, registros_out
				, case when lote is not null 
					then '<i class=\"halflings-icon ok\"></i>'
					else '<i class=\"halflings-icon remove\"></i>'
				end as ok
			from 
				sistema.provincias pro left join (
					select
						l.id_provincia
						, l.lote
						, registros_in
						, registros_out
					from 
						sistema.lotes l left join
						sistema.subidas s on l.id_subida = s.id_subida left join
						ddjj.sirge i on array[l.lote] = i.lote
					where
						id_padron = " . $id_padron . "
						and l.id_estado = 1
						and extract (month from fecha_impresion) = " . date('m') . "
						and extract (year from fecha_impresion) = " . date('Y') . "
				) lot on pro.id_provincia = lot.id_provincia
			order by
				pro.id_provincia";
		return $this->_sirge->jsonDT($this->_db->query($sql)->getResults() , true);
	}
	
	public function porcentaje ($id_padron) {
		if ($id_padron == 4) {
			$sql = "
				select round (count (*) / 27 :: numeric * 100 , 0) as valor
				from puco.procesos_obras_sociales
				where periodo = (extract (year from now()) :: text || lpad (extract (month from now()) :: text , 2 , '0')) :: int";
		} else {
			$sql = "
				select round (count (*) / 24 :: numeric * 100 , 0) as valor
				from (
				select l.id_provincia
				from 
					sistema.lotes l left join
					sistema.subidas s on l.id_subida = s.id_subida left join
					ddjj.sirge i on array[l.lote] = i.lote
				where 
					l.id_estado = 1
					and id_padron = " . $id_padron . "
					and extract (month from fecha_impresion) = " . date('m') . "
					and extract (year from fecha_impresion) = " . date('Y') . "
				group by l.id_provincia ) p";
		}
		return $this->_db->query($sql)->get()['valor'];
	}
	
	public function visitas () {
		$sql = 
			"select * 
			from (
				select
					extract (year from fecha_login) :: text || lpad (extract (month from fecha_login) ::text , 2 , '0') as periodo
					, count (*) as visitas
				from logs.log_logins
				group by
					1
				order by
					1 desc
				limit 6 ) a
			order by 1 asc";
		$data = $this->_db->query($sql)->getResults();
		foreach ($data as $visitas) {
			$hits[] = $visitas['visitas'];
		}
		return implode (',' , $hits);
	}
	
	public function listadoComentarios ($ajax = false) {
		$sql = 
			"select	
				descripcion as nombre
				, comentario
				, fecha_comentario
				, extract (day from fecha_comentario) || '/' || lpad (extract (month from fecha_comentario) :: text , 2 , '0') || '/' || extract (year from fecha_comentario) || ' - ' || extract (hour from fecha_comentario) || ':' || lpad (extract (minutes from fecha_comentario) :: text , 2 , '0') as fecha_comentario
			from 
				sistema.comentarios c left join
				sistema.usuarios u on c.id_usuario = u.id_usuario 
			order by 3 desc";
		$this->_comentario = '';
		foreach ($this->_db->query($sql)->getResults() as $key) {
			$this->_comentario .= '<div  class="comentario">';
			$this->_comentario .= '<span class="nombre">' . $key['nombre'] . '</span>,' . $key['fecha_comentario'];
			$this->_comentario .= '<div class="body">' . htmlentities ($key['comentario']) . '</div>';
			$this->_comentario .= '</div>';
		}
		if ($ajax) 
			echo $this->_comentario;
		else 
			return $this->_comentario;
	}
}

?>
