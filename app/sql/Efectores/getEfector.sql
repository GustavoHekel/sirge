select
  nombre
  , cuie
  , siisa
  , domicilio
  , codigo_postal
  , denominacion_legal
  , ppac
  , sumar
  , case
      when priorizado = 'N' then '<span class="label label-info">' || priorizado || '</span>'
      when priorizado = 'S' then '<span class="label label-success">' || priorizado || '</span>'
    end as priorizado_sumar
  , te.descripcion as tipo_efector
  , tc.descripcion as tipo_categorizacion
from
  efectores.efectores e left join
  efectores.tipo_efector te on e.id_tipo_efector = te.id_tipo_efector left join
  efectores.tipo_categorizacion tc on e.id_categorizacion = tc.id_categorizacion
where
  id_efector = ?