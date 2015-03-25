select * , extract (year from age (localtimestamp , fecha_nacimiento)) as edad
from beneficiarios.beneficiarios
where numero_documento = ?
