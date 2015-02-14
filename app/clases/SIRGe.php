<?php

class SIRGe {
	
	private 
		$_db ,
		$_comentarios ;
	
	public function __construct () {
		$this->_db = BDD::GetInstance();
	}
	
	
	
	public function JSONDT ($data = array() , $ajax = false) {
		
		$i = 0;
		
		if (count ($data)) {
			foreach ($data[0] as $key => $value) {
				$json['aoColumns'][$i]['mData'] = $key;
				$json['aoColumns'][$i]['sTitle'] = str_replace ('_' , ' ' , ucwords ($key));
				$i ++;
			}
		} else {
			$json['aoColumns'][$i]['mData'] = 'no_data';
			$json['aoColumns'][$i]['sTitle'] = 'Atenci&oacute;n!';
		}
		
		if (count ($data)) {
			foreach ($data as $key => $value) {
				$json['data'][$key] = $value;
			}
		} else {
			$json['data'] = null;
		}
		
		$json['recordsTotal'] 		= count ($data);
		$json['recordsFiltered'] 	= count ($data);
		$json['draw'] 				= 1;
		
		if ($ajax) {
			echo (json_encode ($json , JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		} else {
			return (json_encode ($json , JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		}
		
	}
	
	public function MesATexto () {
		
		switch (date('m')) {
			case 1	: $mes = 'Enero'; 		break;
			case 2	: $mes = 'Febrero'; 	break;
			case 3	: $mes = 'Marzo'; 		break;
			case 4	: $mes = 'Abril'; 		break;
			case 5	: $mes = 'Mayo'; 		break;
			case 6	: $mes = 'Junio'; 		break;
			case 7	: $mes = 'Julio'; 		break;
			case 8	: $mes = 'Agosto'; 		break;
			case 9	: $mes = 'Septiembre'; 	break;
			case 10	: $mes = 'Octubre'; 	break;
			case 11	: $mes = 'Noviembre'; 	break;
			case 12	: $mes = 'Diciembre'; 	break;
			default: break;
		}
		
		return $mes;
		
	}
	
	public function ListarComentariosDB ($ajax = false) {
		
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

		foreach ($this->_db->Query($sql)->GetResults() as $key) {
			$this->_comentario .= '<div  class="comentario">';
			$this->_comentario .= '<span class="nombre">' . $key['nombre'] . '</span>,' . $key['fecha_comentario'];
			$this->_comentario .= '<div class="body">' . $key['comentario'] . '</div>';
			$this->_comentario .= '</div>';
		}
		
		if ($ajax) {
			echo $this->_comentario;
		} else {
			return $this->_comentario;
		}
		
	}
	
	public function VisitasDB () {
		
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
		
		$data = $this->_db->Query($sql)->GetResults();
		
		foreach ($data as $visitas) {
			$hits[] = $visitas['visitas'];
		}
		
		return implode (',' , $hits);
		
	}
	
	public function PorcentajeDB ($id_padron) {
		
		if ($id_padron == 4) {
			
			$sql = "
				select round (count (*) / 27 :: numeric * 100 , 0) as valor
				from puco.procesos_obras_sociales
				where periodo = (extract (year from now()) :: text || lpad (extract (month from now()) :: text , 2 , '0')) :: int";
				
		} else {
		
			$sql = "
				select round (count (*) / 24 :: numeric * 100 , 0) as valor
				from (
				select id_provincia
				from 
					sistema.lotes l left join
					declaraciones_juradas.impresiones_ddjj_sirge i on l.lote = i.lote
				where 
					id_estado = 1
					and id_padron = " . $id_padron . "
					and extract (month from fecha_impresion_ddjj) = " . date('m') . "
					and extract (year from fecha_impresion_ddjj) = " . date('Y') . "
				group by id_provincia ) p";
		}
		return $this->_db->Query($sql)->GetRow()['valor'];
	}

	public function DetalleFuenteDatosDB ($id_padron) {
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
						id_provincia
						, l.lote
						, registros_in
						, registros_out
					from 
						sistema.lotes l left join
						declaraciones_juradas.impresiones_ddjj_sirge i on l.lote = i.lote
					where
						id_padron = " . $id_padron . "
						and id_estado = 1
						and extract (month from fecha_impresion_ddjj) = " . date('m') . "
						and extract (year from fecha_impresion_ddjj) = " . date('Y') . "
				) lot on pro.id_provincia = lot.id_provincia
			order by
				pro.id_provincia";
		
		return $this->JSONDT($this->_db->Query($sql)->GetResults() , true);
	}
	
	public function DetalleVisitasDB () {
		
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
		
		return $this->JSONDT($this->_db->Query($sql)->GetResults() , true);
	}
	
	public function DetallePUCODB () {
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
		
		return $this->JSONDT($this->_db->Query($sql)->GetResults() , true);
		
	}
	
	public function InsertarComentarioDB ($comentario) {
		
		$sql = "
			insert into sistema.comentarios (id_usuario , comentario)
			values (?,?)";
		
		$params = array(
			$_SESSION['id_usuario'] ,
			$comentario
		);
		
		$this->_db->Query($sql , $params);
		
		return $this->ListarComentariosDB(true);
	}
	
	public function DetalleTotalesDB ($id_total) {
		
		switch ($id_total) {
			case 1 : 
				$sql = "
					select
						descripcion as nombre
						, to_char (sum (registros_in) , '99,999,999') as total
					from 
						sistema.lotes l left join
						sistema.provincias p on l.id_provincia = p.id_provincia
					where 
						id_padron = 1
						and id_estado = 1
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
		
		echo $this->JSONDT($this->_db->Query($sql)->GetResults());
	}
	
	public function CantidadPrestaciones () {
		
		$sql = "
			select 
				to_char (sum (registros_in) , '99,999,999') as cantidad 
			from 
				sistema.lotes 
			where 
				id_padron = 1 
				and id_estado = 1";
		
		return $this->_db->Query($sql)->GetRow()['cantidad'];
	}
	
	public function CantidadEfectores () {
		
		$sql = "
			select
				count (*) as cantidad
			from
				efectores.efectores";
		
		return $this->_db->Query($sql)->GetRow()['cantidad'];
	}
	
	public function CantidadUsuarios () {
		
		$sql = "
			select
				count (*) as cantidad
			from
				sistema.usuarios";
		
		return $this->_db->Query($sql)->GetRow()['cantidad'];
	}
	
	public function CantidadBeneficiarios () {
		
		$sql = "
			select
				to_char (count (*) , '99,999,999') as cantidad
			from
				beneficiarios.beneficiarios";
		
		return $this->_db->Query($sql)->GetRow()['cantidad'];
	}
	
	public static function RetornaIdProvincia ($provincia) {
		
		$id_provincia;
		
		switch ($provincia) {
			case 'CIUDAD DE BUENOS AIRES' : $id_provincia = '01'; break;
			case 'BUENOS AIRES' : $id_provincia = '02'; break;
			case 'CATAMARCA' : $id_provincia = '03'; break;
			case 'CÓRDOBA' : $id_provincia = '04'; break;
			case 'CORRIENTES' : $id_provincia = '05'; break;
			case 'ENTRE RIOS' : $id_provincia = '06'; break;
			case 'JUJUY' : $id_provincia = '07'; break;
			case 'LA RIOJA' : $id_provincia = '08'; break;
			case 'MENDOZA' : $id_provincia = '09'; break;
			case 'SALTA' : $id_provincia = '10'; break;
			case 'SAN JUAN' : $id_provincia = '11'; break;
			case 'SAN LUIS' : $id_provincia = '12'; break;
			case 'SANTA FE' : $id_provincia = '13'; break;
			case 'SANTIAGO DEL ESTERO' : $id_provincia = '14'; break;
			case 'TUCUMÁN' : $id_provincia = '15'; break;
			case 'CHACO' : $id_provincia = '16'; break;
			case 'CHUBUT' : $id_provincia = '17'; break;
			case 'FORMOSA' : $id_provincia = '18'; break;
			case 'LA PAMPA' : $id_provincia = '19'; break;
			case 'MISIONES' : $id_provincia = '20'; break;
			case 'NEUQUÉN' : $id_provincia = '21'; break;
			case 'RÍO NEGRO' : $id_provincia = '22'; break;
			case 'SANTA CRUZ' : $id_provincia = '23'; break;
			case 'TIERRA DEL FUEGO' : $id_provincia = '24'; break;
		}
		
		return $id_provincia;
	}
	
	

}

?>
