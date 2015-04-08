SELECT descripcion AS nombre
	,To_char(Sum(registros_in), '99,999,999') AS total
FROM sistema.lotes l
LEFT JOIN sistema.subidas s ON l.id_subida = s.id_subida
LEFT JOIN sistema.provincias p ON l.id_provincia = p.id_provincia
WHERE id_padron = 1
	AND l.id_estado = 1
GROUP BY 1
ORDER BY 1;