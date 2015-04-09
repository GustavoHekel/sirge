SELECT * 
	FROM indicadores.indicadores_medica_rangos 
	WHERE codigo_indicador = ? 
	AND id_provincia = ? 
	AND substring((periodo::character varying),0,5) = ?