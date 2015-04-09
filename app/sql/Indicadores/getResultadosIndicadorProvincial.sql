SELECT * 
	FROM indicadores.indicadores_medica 
	WHERE periodo = ? 
	AND id_provincia = ? 
	AND codigo_indicador LIKE ? 