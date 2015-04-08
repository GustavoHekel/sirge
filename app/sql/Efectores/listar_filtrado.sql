SELECT '<span id_efector="' || id_efector || '" class="row-details row-details-close"></span>' AS d
  ,cuie
	,siisa as codigo_siisa
	,nombre as nombre_efector
  ,sumar
  ,priorizado
	,CASE 
		WHEN e.id_estado = 1
			THEN '<span class="label label-success">' || te.descripcion || '</span>'
		WHEN e.id_estado = 4
			THEN '<span class="label label-warning">' || te.descripcion || '</span>'
		END AS estado
FROM efectores.efectores e
LEFT JOIN efectores.tipo_estado te ON e.id_estado = te.id_estado
WHERE e.id_estado IN (
		1
		,4
		)
    AND (
      cuie LIKE ?
      OR siisa LIKE ?
      OR nombre LIKE ?
    )
ORDER BY 2
LIMIT ?
OFFSET ?
