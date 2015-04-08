SELECT p.descripcion                    AS nombre, 
       To_char (Count(*), '99,999,999') AS total 
FROM   beneficiarios.beneficiarios b 
       LEFT JOIN sistema.provincias p 
              ON b.id_provincia_alta = p.id_provincia 
GROUP  BY 1 
ORDER  BY 1;