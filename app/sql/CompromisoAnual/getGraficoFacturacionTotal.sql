select
			p.id_provincia
			, case
				when p.descripcion = 'CIUDAD AUTONOMA DE BUENOS AIRES' then 'CABA'
				else p.descripcion
			end as nombre
			, c.primer_semestre
			, c.segundo_semestre
			, c.year
			, prestaciones_desc
			, prestaciones_fact
			, round (prestaciones_desc :: numeric / prestaciones_fact * 100 , 2) as fact_desc
		from (
			select
				provincia
				, count (*) as prestaciones_desc
			from (
				select p.id_provincia as provincia, * from prestaciones.prestaciones p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 
				
				and fecha_prestacion >= '2014-01-01'  
				) prov
				where efector in (
					select cuie
					from
						efectores.efectores e left join
						efectores.descentralizacion d on e.id_efector = d.id_efector
					where
						factura_descentralizada = 'S'
						and integrante = 'S'
						and compromiso_gestion = 'S'
				)
			group by provincia
		) a left join (
			select
				provincia
				, count (*) as prestaciones_fact
			from (
				select p.id_provincia as provincia from prestaciones.prestaciones p left join sistema.lotes l on p.lote = l.lote where id_estado = 1
						
				and fecha_prestacion >= '2014-01-01' 
				 ) provi
			
			group by provincia
		) b on a.provincia = b.provincia 
			right join
				sistema.provincias p on a.provincia = p.id_provincia 
			left join
				compromiso_anual.metas_facturacion c on p.id_provincia = c.id_provincia

		where 					
			c.year in (:year::int, :yearAnt::int)
		
		order by p.id_provincia,c.year