SELECT    o.nombre_grupo         AS "nombre entidad / OSP" , 
          a.codigo_os            AS "codigo_OSP" , 
          a.periodo              AS "periodo (PUCO)" , 
          a.registros_insertados AS aceptados , 
          a.registros_rechazados AS rechazados , 
          a.registros_totales    AS totales , 
          Round (((a.registros_insertados :: numeric / b.registros_insertados) -1 ) * 100 , 2) :: text
                    || '%' AS variacion 
FROM      puco.grupos_obras_sociales o 
LEFT JOIN 
          ( 
                 SELECT * 
                 FROM   puco.procesos_obras_sociales 
                 WHERE  periodo = (extract (year FROM localtimestamp) :: text 
                               || lpad ((extract (month FROM localtimestamp) :: text) , 2 , '0')) :: int --". date ('Ym') ." 
          ) a 
ON        o.grupo_os = a.codigo_os 
LEFT JOIN 
          ( 
                 SELECT * 
                 FROM   puco.procesos_obras_sociales 
                 WHERE  periodo = (extract (year FROM (localtimestamp - interval '1 month')) :: text
                               || lpad (extract (month FROM (localtimestamp - interval '1 month')) :: text , 2 , '0')) :: int ) b
ON        a.codigo_os = b.codigo_os