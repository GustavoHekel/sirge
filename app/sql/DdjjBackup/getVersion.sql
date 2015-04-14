select
	version
	from ddjj.backup
	where
	id_provincia = ?
	and periodo_reportado = ?
	order by version desc