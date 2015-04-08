WITH upd1 AS 
( 
          update sistema.subidas 
          SET       id_estado = 2 
          WHERE     id_subida = ? 
          returning id_subida ) 
INSERT INTO sistema.subidas_aceptadas 
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
