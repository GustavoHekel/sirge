UPDATE indicadores.indicadores_priorizados
					SET numerador = resultado1, denominador = resultado2
					FROM
					(
					SELECT bp.efector_asignado, sum(case
												when p.codigo_prestacion IN ('APA002A98','APA002X80','APA002X75') then 1
												else 0 end ) as resultado1
															, sum(case
																when p.codigo_prestacion IN ('APA001X86','APA001X75') then 1
																else 0 end ) as resultado2
					FROM efectores.efectores e
					,efectores.datos_geograficos eg
					,beneficiarios.beneficiarios_periodos_y_ceb_2015 bp
					,beneficiarios.beneficiarios b
					, :tabla_provincia p
					WHERE eg.id_efector = e.id_efector
					AND eg.id_provincia = :provincia
					AND e.cuie = bp.efector_asignado
					AND e.priorizado = 'S'
					AND bp.clave_beneficiario = b.clave_beneficiario
					AND id_provincia_alta = :provincia
					AND extract (year from (age ( :fecha ::date , b.fecha_nacimiento))) between 25 and 64
					AND p.efector = e.cuie
					AND p.clave_beneficiario = b.clave_beneficiario
					AND bp.periodo = :periodo
					AND p.fecha_prestacion > (( :fecha ::date) - interval '1 years')
					AND activo = 'S'
					AND substring(b.clave_beneficiario,0,3) <> '00'
					group by 1
					order by 1
				) as resultados
								WHERE efector = efector_asignado
								AND indicador = :indicador
								AND periodo = :periodo
					;