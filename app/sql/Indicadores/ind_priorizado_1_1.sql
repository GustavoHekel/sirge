UPDATE indicadores.indicadores_priorizados
			SET numerador = resultado1, denominador = resultado2
			FROM
			(
				SELECT bpyc.efector_asignado, sum(case ceb
										when 'S' then 1
										else 0 end) as resultado1, count(*) as resultado2
					FROM efectores.efectores e
					INNER JOIN efectores.datos_geograficos eg ON eg.id_efector = e.id_efector
					INNER JOIN beneficiarios.beneficiarios_periodos_y_ceb_2015 bpyc ON e.cuie = bpyc.efector_asignado
					WHERE activo = 'S'
					AND eg.id_provincia = :provincia
					AND bpyc.periodo = :periodo
					AND e.priorizado = 'S'

					group by 1
					order by 1
			) as resultados
			WHERE efector = efector_asignado
			AND indicador = :indicador
			AND periodo = :periodo