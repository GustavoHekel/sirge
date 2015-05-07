with ins1 as (
  insert into efectores.efectores (
    cuie , siisa , nombre , domicilio , codigo_postal , id_tipo_efector , rural , cics ,
    id_categorizacion , id_dependencia_administrativa , dependencia_sanitaria , integrante ,
    compromiso_gestion , priorizado)
  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
  returning id_efector
), ins2 as (
  insert into efectores.datos_geograficos (id_efector , id_provincia , id_departamento , id_localidad , ciudad)
  values ((select * from ins1) ,?,?,?,?)
  returning id_efector
) , ins3 as (
  insert into efectores.referentes (id_efector , nombre)
  values ((select * from ins2) ,?)
  returning id_efector
) , ins4 as (
  insert into efectores.telefonos (id_efector , numero_telefono , id_tipo_telefono , observaciones)
  values ((select * from ins3),?,?,?)
  returning id_efector
)
  insert into efectores.email (id_efector , email , observaciones)
  values ((select * from ins4),?,?)