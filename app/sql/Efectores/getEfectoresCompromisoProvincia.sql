select
  to_char (count (*) , '999,999') as c
from	
  efectores.efectores e left join
  efectores.datos_geograficos d on e.id_efector = d.id_efector
where
  id_provincia = ?
  and id_estado = 1
  and integrante = 'S'
  and compromiso_gestion = 'S'
