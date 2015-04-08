SELECT e.descripcion, 
       To_char (Count (*), '999,999') AS total 
FROM   sistema.usuarios u 
       LEFT JOIN sistema.entidades e 
              ON u.id_entidad = e.id_entidad 
GROUP  BY 1 
ORDER  BY 1;