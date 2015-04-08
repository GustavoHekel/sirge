SELECT    '<span lote="' 
                    || lote 
                    ||'" id_estado="' 
                    || l.id_estado 
                    ||'" class="row-details row-details-close"></span>' AS _ , 
          lote , 
          inicio :: date AS fecha , 
          CASE 
                    WHEN e.id_estado = 3 THEN '<span class="label label-info">' 
                                        || e.descripcion 
                                        ||'</span>' 
                    WHEN e.id_estado = 2 THEN '<span class="label label-warning">' 
                                        || e.descripcion 
                                        ||'</span>' 
                    WHEN e.id_estado = 1 THEN '<span class="label label-success">' 
                                        || e.descripcion 
                                        ||'</span>' 
          END AS estado , 
          CASE 
                    WHEN EXISTS 
                              ( 
                                     SELECT 1 
                                     FROM   ddjj.sirge i 
                                     WHERE  i.lote @> array[l.lote]) THEN '<span class="label label-success">IMPRESA</span>' 
                    ELSE 
                              CASE 
                                        WHEN e.id_estado = 3 THEN '<span class="label label-info">ELIMINADO</span>' 
                                        ELSE '<span class="label label-warning">PENDIENTE</span>' 
                              END 
          END AS "DDJJ" 
FROM      sistema.lotes l 
LEFT JOIN sistema.subidas s 
ON        l.id_subida = s.id_subida 
LEFT JOIN sistema.estados e 
ON        l.id_estado = e.id_estado 
WHERE     id_padron = ? 
AND       id_provincia = ? 
ORDER BY  2 DESC
