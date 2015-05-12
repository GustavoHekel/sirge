SELECT nombre, r.efector, case
			when abril > 0 then abril
			else (case
				when marzo > 0 then marzo
				else (case
					when febrero > 0 then febrero
					else (case
						when enero > 0 then enero
						else 0 end) end) end) end as c1, case
										when agosto > 0 then agosto
										else (case
											when julio > 0 then julio
											else (case
												when junio > 0 then junio
												else (case
													when mayo > 0 then mayo
													else 0 end) end) end) end as c2, case
																	when diciembre > 0 then diciembre
																	else (case
																		when noviembre > 0 then noviembre
																		else (case
																			when octubre > 0 then octubre
																			else (case
																				when septiembre > 0 then septiembre
																				else 0 end) end) end) end as c3, i.c1 as meta_c1, i.c2 as meta_c2, i.c3 as meta_c3

	FROM indicadores.resumen_anio_indicadores_priorizados(:periodo,:id_provincia,:indicador) r
	LEFT JOIN indicadores.metas_efectores_priorizados i ON r.efector = i.efector
	WHERE 
		i.indicador = :indicador ;