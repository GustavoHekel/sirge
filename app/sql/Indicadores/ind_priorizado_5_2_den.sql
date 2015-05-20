UPDATE indicadores.indicadores_priorizados
				SET denominador = resultado
				FROM (
				SELECT bbp.efector_asignado, count(*) as resultado
					FROM efectores.efectores e
					,efectores.datos_geograficos eg
					,beneficiarios.beneficiarios_periodos_y_ceb_2015 bbp
					,beneficiarios.beneficiarios_indigenas bbi
						WHERE eg.id_efector = e.id_efector
						AND eg.id_provincia = :provincia
						AND e.cuie = bbp.efector_asignado
						AND e.priorizado = 'S'
						AND bbp.clave_beneficiario = bbi.clave_beneficiario
						AND declara_indigena = 'S'
						AND bbp.activo = 'S'
						AND bbp.periodo = :periodo

						group by bbp.efector_asignado
				) as denominadores_por_efector
							WHERE efector = efector_asignado
							AND indicador = :indicador
							AND periodo = :periodo