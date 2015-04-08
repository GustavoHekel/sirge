SELECT lote, 
       inicio :: DATE AS fecha, 
       registros_in   AS insertados, 
       registros_out  AS rechazados, 
       registros_mod  AS modificados 
FROM   sistema.lotes l 
       left join sistema.subidas s 
              ON l.id_subida = s.id_subida 
WHERE  lote NOT IN (SELECT Unnest (lote) 
                    FROM   ddjj.sirge) 
       AND l.id_estado = 1 
       AND id_padron = ? 
       AND id_provincia = ? 
