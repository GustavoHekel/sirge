select
  p.descripcion as provincia
  , d.nombre_departamento as departamento
  , l.nombre_localidad as localidad
from 
  efectores.datos_geograficos g left join
  sistema.provincias p on g.id_provincia = p.id_provincia left join
  efectores.departamentos d on g.id_provincia || g.id_departamento = d.id_provincia || d.id_departamento left join
  efectores.localidades l on g.id_provincia || g.id_departamento || g.id_localidad = l.id_provincia || l.id_departamento || l.id_localidad
where id_efector = ?
