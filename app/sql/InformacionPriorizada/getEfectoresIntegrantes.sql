SELECT COUNT (*)
		FROM
			efectores.efectores e 
		LEFT JOIN
				efectores.datos_geograficos g ON e.id_efector = g.id_efector
		WHERE
			id_provincia = :id_provincia
		AND 
			integrante = 'S'