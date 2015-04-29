UPDATE ddjj.doiu9
			SET motivo_reimpresion = :motivo_reimpresion
			where
				id_provincia = :id_provincia
				and periodo_reportado = :periodo
				and version = (
					select max (version)
					from ddjj.doiu9
					where
						id_provincia = :id_provincia
						and periodo_reportado = :periodo
						)