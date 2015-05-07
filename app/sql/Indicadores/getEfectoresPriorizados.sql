SELECT * 
	FROM 
		indicadores.indicadores_priorizados 
	WHERE 
		id_provincia = :id_provincia 
	AND 
		indicador = :indicador ;