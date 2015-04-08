SELECT 
        s.periodo
	,coalesce(a.cantidad, 0) AS cantidad
FROM (
	SELECT (extract(year FROM generate_series)::TEXT || lpad(extract(month FROM generate_series)::TEXT, 2, '0'))::INT AS periodo
	FROM generate_series('2004-01-01'::TIMESTAMP, (extract(year FROM localtimestamp)::TEXT || '-12-31')::TIMESTAMP, '1 month')
	) s
LEFT JOIN (
	SELECT p.periodo
		,cantidad
	FROM (
		SELECT p.*
		FROM beneficiarios.beneficiarios b
		LEFT JOIN beneficiarios.beneficiarios_periodos p ON b.clave_beneficiario = p.clave_beneficiario
		WHERE numero_documento = ?
		) p
	LEFT JOIN (
		SELECT clave_beneficiario
			,extract(year FROM fecha_prestacion)::TEXT || lpad(extract(month FROM fecha_prestacion)::TEXT, 2, '0') AS periodo
			,count(*) AS cantidad
		FROM prestaciones.prestaciones
		WHERE clave_beneficiario = (
				SELECT clave_beneficiario
				FROM beneficiarios.beneficiarios
				WHERE numero_documento = ?
				)
		GROUP BY 1
			,2
		) b ON p.clave_beneficiario || p.periodo = b.clave_beneficiario || b.periodo
	) a ON s.periodo = a.periodo
ORDER BY 1