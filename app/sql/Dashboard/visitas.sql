SELECT *
FROM (
	SELECT extract(year FROM fecha_login)::TEXT || lpad(extract(month FROM fecha_login)::TEXT, 2, '0') AS periodo
		,count(*) AS visitas
	FROM logs.log_logins
	GROUP BY 1
	ORDER BY 1 DESC limit 6
	) a
ORDER BY 1 ASC
