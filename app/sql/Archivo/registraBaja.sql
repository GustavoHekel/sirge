WITH upd1 AS 
( 
          update sistema.subidas 
          SET       id_estado = 3 
          WHERE     id_subida = ? 
          returning id_subida ) 
INSERT INTO sistema.subidas_eliminadas 
            ( 
                        id_subida , 
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
            );
