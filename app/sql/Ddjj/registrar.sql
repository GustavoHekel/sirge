WITH ins1 AS 
( 
            insert INTO ddjj.sirge 
                        ( 
                                    lote , 
                                    id_provincia 
                        ) 
                        VALUES 
                        ( 
                                    ?, 
                                    ? 
                        ) 
                        returning id_impresion ) 
SELECT * 
FROM   ins1
