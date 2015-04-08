SELECT p.descripcion                     AS nombre, 
       To_char (Count (*), '99,999,999') AS total 
FROM   efectores.efectores e 
       LEFT JOIN efectores.datos_geograficos g 
              ON e.id_efector = g.id_efector 
       LEFT JOIN sistema.provincias p 
              ON g.id_provincia = p.id_provincia 
WHERE  id_estado = 1 
GROUP  BY 1 
ORDER  BY 1;
