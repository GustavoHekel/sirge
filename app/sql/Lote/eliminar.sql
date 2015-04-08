WITH upd1 AS 
( 
          update sistema.lotes 
          SET       id_estado = 3 
          WHERE     lote = ? 
          returning lote ), ins1 AS 
( 
            INSERT INTO sistema.lotes_eliminados 
                        ( 
                                    lote , 
                                    id_usuario 
                        ) 
                        VALUES 
                        ( 
                        ( 
                               SELECT * 
                               FROM   upd1 
                        ) 
                        , 
                        ? 
                        ) 
            returning   lote ) 
DELETE 
FROM   aplicacion_fondos.rechazados 
WHERE  lote = 
       ( 
              SELECT * 
              FROM   ins1)