SELECT
	p.descripcion AS provincia
	, u.usuario
	, periodo_reportado
	, fecha_impresion :: date AS fecha_impresion
	, motivo_reimpresion
	, version
	, '<a href="#" id_impresion="' || id_impresion || '" class="imprimir_ddjj"><i class="halflings-icon print"></i></a>' AS imprimir_ddjj
	--, '<a href=\"#\"><img class=\"descargar_ddjj\" src=\"img/download.png\" title=\"Descargar DDJJ\" periodo=\"' || periodo_reportado || '\" id_provincia=\"' || p.id_provincia || '\" /></a>' as descargar_ddjj
	FROM
		ddjj.backup i LEFT JOIN
		sistema.usuarios u ON i.id_usuario = u.id_usuario LEFT JOIN
		sistema.provincias p ON i.id_provincia = p.id_provincia
	WHERE
		i.id_provincia = ?
	ORDER BY periodo_reportado, p.descripcion