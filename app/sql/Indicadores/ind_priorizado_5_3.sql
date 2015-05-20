UPDATE indicadores.indicadores_priorizados
				SET denominador = resultado
				FROM (
					SELECT bp.efector_asignado, count(*) as resultado
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
							AND p.codigo_prestacion = 'ROX002A98'
							AND bp.periodo = :periodo
							AND p.fecha_prestacion BETWEEN ( :fecha_inicio_cuatri ::date) AND ( :fecha_fin_cuatri ::date)
							AND activo = 'S'

							group by 1
							order by 1
				) as resultados
							WHERE efector = efector_asignado
							AND indicador = :indicador
							AND periodo = :periodo
				;