insert into ddjj.doiu9 (
		id_provincia
		, id_usuario
		, periodo_reportado
		, efectores_integrantes
		, efectores_convenio
		, periodo_tablero_control
		, fecha_cuenta_capitas
		, periodo_cuenta_capitas
		, fecha_sirge
		, periodo_sirge
		, fecha_reporte_bimestral
		, bimestre
		, anio_bimestre
		, version)
		values (
		:id_provincia
		, :id_usuario
		, :periodo
		, (	select count (*)
			from
				efectores.efectores e left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				integrante = 'S'
			and id_provincia = :id_provincia )
		, (	select count (*)
			from
				efectores.efectores e left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				integrante = 'S'
			and compromiso_gestion = 'S'
			and id_provincia = :id_provincia )
			, :periodo_tablero
			, :fecha_cuenta_capitas
			, :periodo_cuenta_capitas
			, :fecha_sirge
			, :periodo_sirge
			, :fecha_reporte_bimestral
			, :bimestre
			, :anio_bimestre
			, :version)