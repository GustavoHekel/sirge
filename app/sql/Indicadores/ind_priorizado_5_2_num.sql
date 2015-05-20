UPDATE indicadores.indicadores_priorizados
			SET numerador = resultado1
			FROM
			(
				SELECT bp.efector_asignado, sum(case
														when p.codigo_prestacion = 'CTC009A97'
														AND p.fecha_prestacion BETWEEN ( :fecha_inicio_cuatri ::date) AND ( :fecha_fin_cuatri ::date) then 1
														else 0 end) as resultado1
				FROM efectores.efectores e
				,efectores.datos_geograficos eg
				,beneficiarios.beneficiarios_periodos_y_ceb_2015 bp
				,beneficiarios.beneficiarios_indigenas bi
				, :tabla_provincia p
				WHERE eg.id_efector = e.id_efector
				AND eg.id_provincia = :provincia
				AND e.cuie = bp.efector_asignado
				AND e.priorizado = 'S'
				AND bp.clave_beneficiario = bi.clave_beneficiario
				AND declara_indigena = 'S'
				AND p.efector = e.cuie
				AND p.clave_beneficiario = bp.clave_beneficiario
				AND bp.periodo = :periodo
				AND activo = 'S'

				group by 1
				order by 1
			) as resultados
						WHERE efector = efector_asignado
						AND indicador = :indicador
						AND periodo = :periodo
			;