UPDATE indicadores.indicadores_priorizados
			SET denominador = resultado
			FROM (
				SELECT bp.efector_asignado, count(*) as resultado
				FROM efectores.efectores e
				,efectores.datos_geograficos eg
				,beneficiarios.beneficiarios_periodos_y_ceb_2015 bp
				,beneficiarios.beneficiarios b
				WHERE eg.id_efector = e.id_efector
				AND eg.id_provincia = :provincia
				AND e.cuie = bp.efector_asignado
				AND e.priorizado = 'S'
				AND bp.clave_beneficiario = b.clave_beneficiario
				AND id_provincia_alta = :provincia
				AND extract (year from (age ( :fecha ::date , b.fecha_nacimiento))) between 10 and 19
				AND bp.periodo = :periodo
				AND activo = 'S'
				AND substring(b.clave_beneficiario,0,3) <> '00'
				group by 1
				order by 1
			) as denominadores_por_efector
			WHERE efector = efector_asignado
			AND indicador = :indicador
			AND periodo = :periodo ;