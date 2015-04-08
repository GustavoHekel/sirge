SELECT p.descripcion AS nombre, 
       Count (*)     AS cantidad 
FROM   logs.log_logins l 
       LEFT JOIN sistema.usuarios u 
              ON l.id_usuario = u.id_usuario 
       LEFT JOIN sistema.entidades p 
              ON u.id_entidad = p.id_entidad 
WHERE  Extract (year FROM fecha_login) = extract (year from localtimestamp)
       AND Extract (month FROM fecha_login) = extract (month from localtimestamp)
GROUP  BY 1 
ORDER  BY 1 
