select
	p.descripcion as provincia
	, u.usuario
	, periodo_reportado
	, version
	, motivo_reimpresion
	, fecha_impresion :: date as fecha_impresion
	, '<a href="#" id_impresion="' || id_impresion || '" class="imprimir_ddjj"><i class="halflings-icon print"></i></a>' as imprimir_ddjj
	--, '<a href=\"#\"><img class=\"descargar_ddjj\" src=\"img/download.png\" title=\"Descargar DDJJ\" periodo=\"' || periodo_reportado || '\" id_provincia=\"' || p.id_provincia || '\" /></a>' as descargar_ddjj
	from
		ddjj.doiu9 i 
	left join
		sistema.usuarios u on i.id_usuario = u.id_usuario 
	left join
		sistema.provincias p on i.id_provincia = p.id_provincia
	where
		i.id_provincia = :id_provincia
	order by
		periodo_reportado, p.descripcion