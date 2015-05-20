UPDATE indicadores.indicadores_priorizados
				SET numerador = resultado1, denominador = resultado2
				FROM
				(
					SELECT bp.efector_asignado, sum(case ceb
															when 'S' then 1
															else 0 end) as resultado1, count(*) as resultado2
					FROM efectores.efectores e
					,efectores.datos_geograficos eg
					,beneficiarios.beneficiarios_periodos_y_ceb_2015 bp
					,beneficiarios.beneficiarios_indigenas bi
					WHERE e.cuie = bp.efector_asignado
					AND eg.id_efector = e.id_efector
					AND eg.id_provincia = :provincia
					AND e.priorizado = 'S'
					AND bp.periodo = :periodo
					AND bp.clave_beneficiario = bi.clave_beneficiario
					AND declara_indigena = 'S'
					AND activo = 'S'

					group by 1
					order by 1
				) as resultados
							WHERE efector = efector_asignado
							AND indicador = :indicador
							AND periodo = :periodo