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
		'$_SESSION[grupo]'
		, $_SESSION[id_usuario]
		, '$_POST[periodo]'
		, (	select count (*)
			from
				efectores.efectores e left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				integrante = 'S'
			and id_provincia = '$_SESSION[grupo]' )
		, (	select count (*)
			from
				efectores.efectores e left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				integrante = 'S'
			and compromiso_gestion = 'S'
			and id_provincia = '$_SESSION[grupo]' )
			, '$_POST[periodo_tablero]'
			, '$_POST[fecha_cuenta_capitas]'
			, '$_POST[periodo_cuenta_capitas]'
			, '$_POST[fecha_sirge]'
			, '$_POST[periodo_sirge]'
			, '$_POST[fecha_reporte_bimestral]'
			, $_POST[bimestre]
			, $_POST[anio_bimestre]
			, $version)