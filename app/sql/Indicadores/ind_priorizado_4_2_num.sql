UPDATE indicadores.indicadores_priorizados
					SET numerador = resultado
					FROM (
					SELECT bp.efector_asignado, count(*) as resultado
							FROM efectores.efectores e
							,efectores.datos_geograficos eg
							,beneficiarios.beneficiarios_periodos_y_ceb_2015 bp
							,beneficiarios.beneficiarios b
							, :tabla_provincia p
							WHERE eg.id_efector = e.id_efector
							AND eg.id_provincia = :provincia
							AND e.cuie = bp.efector_habitual
							AND e.priorizado = 'S'
							AND bp.clave_beneficiario = b.clave_beneficiario
							AND id_provincia_alta = :provincia
							AND extract (year from (age ( :fecha ::date , b.fecha_nacimiento))) between 25 and 64
							AND p.efector = e.cuie
							AND p.clave_beneficiario = b.clave_beneficiario
							AND p.codigo_prestacion IN ('APA001A98','APA001X86','APA001X75')
							AND bp.periodo = :periodo
							AND p.fecha_prestacion > (( :fecha ::date) - interval '2 years')
							AND activo = 'S'
							AND substring(b.clave_beneficiario,0,3) <> '00'
							group by 1
							order by 1
					) as resultados
									WHERE efector = efector_asignado
									AND indicador = :indicador
									AND periodo = :periodo
					; 