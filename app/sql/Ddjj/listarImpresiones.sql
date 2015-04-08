SELECT id_impresion, 
       fecha_impresion, 
       s.lote                                          AS "Lote(s)", 
       '<a class="imprimir" id_impresion="' 
       || id_impresion 
       || '"><i class="halflings-icon print"></i></a>' AS reimprimir 
FROM   ddjj.sirge s 
       LEFT JOIN sistema.lotes l 
              ON l.lote = ANY ( s.lote ) 
       LEFT JOIN sistema.subidas su 
              ON l.id_subida = su.id_subida 
WHERE  s.id_provincia = ? 
       AND id_padron = ? 
       AND l.id_estado = 1 
GROUP  BY 1, 
          2, 
          3 
ORDER  BY 1 DESC, 
          2, 
          3 