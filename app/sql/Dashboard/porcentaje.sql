SELECT Round(Count(*) / 24::NUMERIC * 100, 0) AS valor
FROM (
	SELECT l.id_provincia
	FROM sistema.lotes l
	LEFT JOIN sistema.subidas s ON l.id_subida = s.id_subida
	LEFT JOIN ddjj.sirge i ON array [l.lote] = i.lote
	WHERE l.id_estado = 1
		AND id_padron = ?
		AND Extract(month FROM fecha_impresion) = Extract(month FROM localtimestamp)
		AND Extract(year FROM fecha_impresion) = Extract(year FROM localtimestamp)
	GROUP BY l.id_provincia
	) p