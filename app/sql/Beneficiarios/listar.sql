SELECT '<span clave_beneficiario="' || b.clave_beneficiario || '" class="row-details row-details-close"></span>' AS d
	,b.clave_beneficiario
	,nombre
	,apellido
	,numero_documento
	,fecha_nacimiento
	,id_provincia_alta
	,coalesce (activo , 'N') as activo
FROM beneficiarios.beneficiarios b
LEFT JOIN (
	SELECT *
	FROM beneficiarios.beneficiarios_periodos
	WHERE periodo = (
			SELECT Max(periodo)
			FROM beneficiarios.beneficiarios_periodos
			)
	) p ON b.clave_beneficiario = p.clave_beneficiario
WHERE id_provincia_alta IN (
		'01'
		,'24'
		)
ORDER BY 1 LIMIT ? offset ?
