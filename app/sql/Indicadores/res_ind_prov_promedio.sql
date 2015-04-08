SELECT  ROUND(AVG(resultado))
				FROM indicadores.indicadores_medica
				WHERE (to_date(periodo::varchar, 'yyyymm') + interval '1 mon') - interval '2 days' BETWEEN (( ? ::date) - interval '12 mons') AND ( ? ::date)
				AND id_provincia = ?
				AND codigo_indicador = ?
				AND resultado is not null