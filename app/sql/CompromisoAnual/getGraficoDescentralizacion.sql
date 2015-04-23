select
		p.id_provincia
		, case
			when p.descripcion = 'CIUDAD AUTONOMA DE BUENOS AIRES' then 'CABA'
			else p.descripcion
		end as nombre
		, round (( efectores_decentralizados ::numeric / cantidad_efectores) * 100 , 2) as descentralizacion
		, ( SELECT 
				m.primer_semestre 				
				FROM 
					compromiso_anual.metas_descentralizacion m 
				WHERE
					p.id_provincia = m.id_provincia
				AND 
					m.year = :yearAnt
		) as primer_semestre_anio_ant 
		, ( SELECT 
				m.segundo_semestre
				FROM 
					compromiso_anual.metas_descentralizacion m 
				WHERE
					p.id_provincia = m.id_provincia
				AND 
					m.year = :yearAnt 
		) as segundo_semestre_anio_ant
		, ( SELECT 
				m2.primer_semestre
				FROM 
					compromiso_anual.metas_descentralizacion m2 
				WHERE
					p.id_provincia = m2.id_provincia
				AND 
					m2.year = :year
		) as primer_semestre_anio_actual

		, ( SELECT 
				m2.segundo_semestre
				FROM 
					compromiso_anual.metas_descentralizacion m2 
				WHERE
					p.id_provincia = m2.id_provincia
				AND 
					m2.year = :year
		) as segundo_semestre_anio_actual
	from (
			select
				id_provincia
				, count (*) as cantidad_efectores
			from
				efectores.efectores e 
			left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				integrante = 'S'
			and 
				compromiso_gestion = 'S'
			group by
				id_provincia
		  ) e 
	left join (
					select
						id_provincia
						, count (*) as efectores_decentralizados
					from
						efectores.descentralizacion e left join
						efectores.datos_geograficos g on e.id_efector = g.id_efector left join
						efectores.efectores ef on e.id_efector = ef.id_efector
					where
						factura_descentralizada = 'S'
					and 
						integrante = 'S'
					and 
						compromiso_gestion = 'S'
					group by	
						id_provincia
			   
			   ) d 	on 	e.id_provincia = d.id_provincia 
	right join
				sistema.provincias p on e.id_provincia = p.id_provincia
	where 
				e.id_provincia = :id_provincia 	
	order by 
				e.id_provincia