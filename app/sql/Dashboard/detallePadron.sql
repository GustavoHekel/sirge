SELECT descripcion AS nombre, 
       lote, 
       registros_in, 
       registros_out, 
       CASE 
         WHEN lote IS NOT NULL THEN '<i class="halflings-icon ok"></i>' 
         ELSE '<i class="halflings-icon remove"></i>' 
       END         AS ok 
FROM   sistema.provincias pro 
       left join (SELECT l.id_provincia, l.lote, registros_in, registros_out 
                  FROM   sistema.lotes l left join sistema.subidas s ON 
                 l.id_subida = 
                                               s.id_subida 
                 left join ddjj.sirge i ON array[l.lote] = i.lote 
                  WHERE  id_padron = ? AND l.id_estado = 1 AND Extract (month 
                 FROM 
                                               fecha_impresion) = Extract (month 
                 FROM 
                                               localtimestamp) AND Extract (year 
                 FROM 
                                               fecha_impresion) = Extract (year 
                 FROM 
                                               localtimestamp)) lot 
              ON pro.id_provincia = lot.id_provincia 
ORDER  BY pro.id_provincia 
