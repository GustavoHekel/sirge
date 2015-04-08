SELECT descripcion AS nombre
	,comentario
	,fecha_comentario
	,extract(day FROM fecha_comentario) || '/' || lpad(extract(month FROM fecha_comentario)::TEXT, 2, '0') || '/' || extract(year FROM fecha_comentario) || ' - ' || extract(hour FROM fecha_comentario) || ':' || lpad(extract(minutes FROM fecha_comentario)::TEXT, 2, '0') AS fecha_comentario
FROM sistema.comentarios c
LEFT JOIN sistema.usuarios u ON c.id_usuario = u.id_usuario
ORDER BY 3 DESC