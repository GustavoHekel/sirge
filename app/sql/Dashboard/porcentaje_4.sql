SELECT Round(Count(*) / 27::NUMERIC * 100, 0) AS valor
FROM puco.procesos_obras_sociales
WHERE periodo = (Extract(year FROM Now())::TEXT || Lpad(Extract(month FROM Now())::TEXT, 2, '0'))::INT