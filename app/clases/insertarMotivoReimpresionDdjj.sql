update sistema.impresiones_ddjj_backup
		set motivo_reimpresion = ?
		where
		id_provincia = ?
		and periodo_reportado = ?
		and version = (
			select max (version)
				from sistema.impresiones_ddjj_backup
				where
				id_provincia = ?
				and periodo_reportado = ? )