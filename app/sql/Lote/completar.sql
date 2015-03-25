UPDATE sistema.lotes 
SET    registros_in = ?, 
       registros_out = ?, 
       registros_mod = ?, 
       fin = localtimestamp 
WHERE  lote = ? 