SELECT
	p.descripcion AS Provincia
	, u.usuario
	, periodo_reportado AS Periodo
	, fecha_impresion :: date AS Fecha_impresion
	, version AS Version
	--, '<a href="#" id_impresion="' || id_impresion || '" class="imprimir_ddjj"><i class="halflings-icon print"></i></a>' AS imprimir_ddjj
	--, '<a href=\"#\"><img class=\"descargar_ddjj\" src=\"img/download.png\" title=\"Descargar DDJJ\" periodo=\"' || periodo_reportado || '\" id_provincia=\"' || p.id_provincia || '\" /></a>' as descargar_ddjj
	FROM
		ddjj.backup i 
		,sistema.usuarios u 
		,sistema.provincias p
		, ( SELECT periodo_reportado as periodoRepor, max(fecha_impresion) as maxFecha
			FROM
				ddjj.backup
			WHERE 
				id_provincia = :id_provincia
			GROUP BY 1
			) as tabla
						
	WHERE	
		i.id_usuario = u.id_usuario 
	AND
		i.id_provincia = p.id_provincia
	AND
		i.id_provincia = :id_provincia
	AND 
		substring(periodo_reportado,0,5) = :year
	AND
		periodo_reportado = periodoRepor
	AND
		i.fecha_impresion = tabla.maxFecha

	ORDER BY 1,2,3,5,4