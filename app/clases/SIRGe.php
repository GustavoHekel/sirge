<?php

abstract class SIRGe {
	
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
				, fecha
				, extract (day from fecha) || '/' || lpad (extract (month from fecha) :: text , 2 , '0') || '/' || extract (year from fecha) || ' - ' || extract (hour from fecha) || ':' || lpad (extract (minutes from fecha) :: text , 2 , '0') as fecha_comentario
			from 
				sistema.comentarios c left join
				sistema.usuarios u on c.id_usuario = u.id_usuario 
			order by fecha desc";
		
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
					extract (year from timestamp) :: text || lpad (extract (month from timestamp) ::text , 2 , '0') as periodo
					, count (*) as visitas
				from sistema.log_inicio_sesion
				group by
					1
				order by
					1 desc
				limit 8 ) a
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
				from sistema.procesos_obras_sociales
				where periodo = (extract (year from now()) :: text || lpad (extract (month from now()) :: text , 2 , '0')) :: int";
				
		} else {
		
			$sql = "
				select round (count (*) / 24 :: numeric * 100 , 0) as valor
				from (
				select id_provincia
				from 
					sistema.lotes l left join
					sistema.impresiones_ddjj i on l.lote = i.lote
				where 
					id_estado = 1
					and id_padron = " . $id_padron . "
					and extract (month from fecha_impresion_ddjj) = " . date('m') . "
					and extract (year from fecha_impresion_ddjj) = " . date('Y') . "
				group by id_provincia ) p";
		}
		$data = $this->_db->Query($sql)->GetResults();
		return $data[0]['valor'];
	}

	public function DetalleFuenteDatosDB ($id_padron) {
		$sql = "
			select 
				nombre
				, lote
				, registros_insertados
				, registros_rechazados
				, case when lote is not null 
					then '<i class=\"halflings-icon ok\"></i>'
					else '<i class=\"halflings-icon remove\"></i>'
				end as ok
			from 
				sistema.provincias pro left join (
					select
						id_provincia
						, l.lote
						, registros_insertados
						, registros_rechazados
					from 
						sistema.lotes l left join
						sistema.impresiones_ddjj i on l.lote = i.lote
					where
						id_padron = " . $id_padron[0] . "
						and id_estado = 1
						and extract (month from fecha_impresion_ddjj) = " . date('m') . "
						and extract (year from fecha_impresion_ddjj) = " . date('Y') . "
				) lot on pro.id_provincia = lot.id_provincia
			order by
				pro.id_provincia";
		
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
				from sistema.procesos_obras_sociales
				where periodo = (extract (year from localtimestamp) :: text || lpad ((extract (month from localtimestamp) :: text) , 2 , '0')) :: int --". date ('Ym') ." 
			) a on o.grupo_os = a.codigo_os left join (
				select * 
				from sistema.procesos_obras_sociales
				where periodo = (extract (year from (localtimestamp - interval '1 month')) :: text || lpad (extract (month from (localtimestamp - interval '1 month')) :: text , 2 , '0')) :: int
			) b on a.codigo_os = b.codigo_os";
		
		return $this->JSONDT($this->_db->Query($sql)->GetResults() , true);
		
	}
	
	public function InsertarComentarioDB ($comentario) {
		$sql = "
			insert into sistema.comentarios (id_usuario , comentario)
			values (" . $_SESSION['id_usuario'] . " , '" . $comentario[0] . "')";
		$this->_db->Query($sql);
		
		return $this->ListarComentariosDB(true);
	}
	
	public function DetalleTotalesDB ($id_total) {
		switch ($id_total[0]) {
			case 1 : 
				$sql = "
					select
						nombre
						, to_char (sum (registros_insertados) , '99,999,999') as total
					from 
						sistema.lotes l left join
						sistema.provincias p on l.id_provincia = p.id_provincia
					where 
						id_padron = 1
						and id_estado = 1
					group by p.nombre
					order by p.nombre";
			break;
			
			case 2 :
				$sql = "
					select
						p.nombre
						, to_char (count (*)  , '99,999,999') as total
					from
						efectores.efectores e left join
						efectores.datos_geograficos g on e.id_efector = g.id_efector left join
						sistema.provincias p on g.id_provincia = p.id_provincia
					where id_estado = 1
					group by p.nombre
					order by p.nombre";
			break;
			
			case 3 :
				$sql = "
					select
						nombre
						, to_char (total , '999,999,999') as total
					from 
						(
						select provincia , sum (precio_unitario) as total from prestaciones.prestaciones where estado = 'L' L' 
						) a left join
						sistema.provincias p on p.id_provincia = a.id_provincia";
			break;
			
			case 4 :
				$sql = "
					select
						nombre
						, count (*) as total
					from (
						select 
							u.* , coalesce (p.nombre , e.nombre) as nombre
						from 
							sistema.usuarios u left join
							sistema.provincias p on u.id_entidad = p.id_provincia left join
							sistema.entidades_administrativas e on u.id_entidad = e.id_entidad_administrativa ) a
					group by nombre
					order by nombre";
			break;
		}
		
		echo $this->JSONDT($this->_db->Query($sql)->GetResults());
		
	}
	
	
	

}

?>
