select count (*) as c
from beneficiarios.beneficiarios_ceb
where periodo = (select max(periodo) from beneficiarios.beneficiarios_periodos)
and efector = (select cuie from efectores.efectores where id_efector = ?)
