update ddjj.backup
		set motivo_reimpresion = ?
		where
		id_provincia = ?
		and periodo_reportado = ?
		and version = (
			select max (version)
				from ddjj.backup
				where
				id_provincia = ?
				and periodo_reportado = ? )