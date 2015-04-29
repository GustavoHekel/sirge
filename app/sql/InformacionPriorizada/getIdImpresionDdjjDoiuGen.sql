SELECT id_impresion
			FROM ddjj.doiu9
				
			WHERE
				id_provincia = :id_provincia
			AND
				id_usuario = :id_usuario
			AND
				periodo_tablero_control = :periodo_tablero
			AND
				fecha_cuenta_capitas = :fecha_cuenta_capitas
			AND
				periodo_cuenta_capitas = :periodo_cuenta_capitas
			AND
				fecha_sirge	= :fecha_sirge
			AND
				periodo_sirge = :periodo_sirge
			AND
				fecha_reporte_bimestral = :fecha_reporte_bimestral
			AND
				bimestre = :bimestre
			AND
				anio_bimestre = :anio_bimestre