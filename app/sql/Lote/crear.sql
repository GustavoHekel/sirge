WITH ins1 AS 
( 
            insert INTO sistema.lotes 
                        ( 
                                    id_subida , 
                                    id_usuario , 
                                    id_provincia 
                        ) 
                        VALUES 
                        ( 
                                    ? , 
                                    ? , 
                                    ? 
                        ) 
            returning   lote ) 
SELECT * 
FROM   ins1;