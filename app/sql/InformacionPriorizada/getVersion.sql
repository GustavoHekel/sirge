SELECT
		version
		FROM ddjj.doiu9
		WHERE
			id_provincia = :id_provincia
		AND 
			periodo_reportado = :periodo
		ORDER BY version DESC