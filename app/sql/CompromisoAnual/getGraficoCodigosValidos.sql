select
		p.id_provincia
		, case
			when p.descripcion = 'CIUDAD AUTONOMA DE BUENOS AIRES' then 'CABA'
			else p.descripcion
		end as nombre
		, case
			when registros_insertados = 0 then 0
			else 100 - round (coalesce (rechazos_codigo , 0) :: numeric / registros_insertados , 4)
		end as porcentaje
		, ca.primer_semestre
		, ca.segundo_semestre
		, ca.year
	from (
			select
				r.id_provincia
				, count (*) as rechazos_codigo
			from (
				select regexp_split_to_array (registro_rechazado , E';') as registro_rechazado_array , *
				from prestaciones.rechazados
				where (motivos like '%Prestacion%' or motivos like '%fkey_codigo_prestacion%') ) r left join
				sistema.lotes l on r.lote = l.lote
			where
				id_estado = 1
				and fin >= '2014-01-01'
				and registro_rechazado_array[7] >= '2014-01-01'
			group by 1 ) a 
	right join
			sistema.provincias p on a.id_provincia = p.id_provincia 
	left join (

			select p2.id_provincia, count (*) as registros_insertados	
			from prestaciones.prestaciones p2 
			left join sistema.lotes l on p2.lote = l.lote 
			where fecha_prestacion >= '2014-01-01' 
			and estado = 'L' 
			and id_estado = 1
			group by 1 

		) b on p.id_provincia = b.id_provincia 
	left join
			compromiso_anual.metas_codigos_validos ca on p.id_provincia = ca.id_provincia
	where 				
			p.id_provincia = :id_provincia 
	and	
			ca.year in (:year::int, :yearAnt::int)
order by 1