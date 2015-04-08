WITH upd1 AS 
( 
          update sistema.lotes 
          SET       id_estado = 1 
          WHERE     lote = ? 
          returning lote ) 
INSERT INTO sistema.lotes_aceptados 
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
