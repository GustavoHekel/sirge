SELECT c.nombre_original 
       AS 
       nombre, 
       c.fecha_subida 
       AS fecha_subida, 
       Round (( size / 1024 ) :: numeric, 2) 
       || ' MB' 
       AS  "tamaño", 
       '<a file="' 
       || id_subida 
       || '" href="#" class="procesar"><i class="halflings-icon hdd"></i></a>' 
       AS 
       procesar, 
       '<a file="' 
       || id_subida 
       || '" href="#" class="eliminar"><i class="halflings-icon trash"></a></i>' 
       AS 
       eliminar 
FROM   sistema.subidas c 
       LEFT JOIN sistema.usuarios u 
              ON c.id_usuario = u.id_usuario 
WHERE  id_padron = ? 
       AND id_estado = ? 
       AND id_entidad = ?;
