SELECT *
	,extract(year FROM age(localtimestamp, fecha_nacimiento)) AS edad
FROM beneficiarios.beneficiarios
WHERE numero_documento = ?