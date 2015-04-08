SELECT efe.cuie
	,efe.siisa
	,efe.nombre AS nombre_efector
	,tefe.sigla AS sigla_tipo_efector
	,tefe.descripcion AS descripcion_tipo_efector
	,geo.id_provincia
	,pro.descripcion AS nombre_provincia
	,geo.id_departamento
	,dto.nombre_departamento
	,geo.id_localidad
	,loc.nombre_localidad
	,geo.ciudad
	,efe.codigo_postal
	,efe.domicilio
	,efe.rural
	,efe.cics
	,tcat.sigla AS sigla_tipo_categorizacion
	,tcat.descripcion AS descripcion_tipo_categorizacion
	,dep.sigla AS sigla_dependencia_administrativa
	,dep.descripcion AS descripcion_dependencia_administrativa
	,efe.codigo_provincial_efector
	,efe.dependencia_sanitaria
	,integrante
	,compromiso_gestion
	--, sumar
	,cge.numero_compromiso
	,cge.firmante AS firmante_compromiso
	,cge.fecha_suscripcion AS fecha_suscripcion_compromiso
	,cge.fecha_inicio AS fecha_inicio_compromiso
	,cge.fecha_fin AS fecha_fin_compromiso
	,cge.pago_indirecto
	,cad.numero_compromiso AS numero_convenio
	,cad.firmante AS firmante_convenio
	,cad.nombre_tercer_administrador
	,cad.codigo_tercer_administrador
	,cad.fecha_suscripcion AS fecha_suscripcion_convenio
	,cad.fecha_inicio AS fecha_inicio_convenio
	,cad.fecha_fin AS fecha_fin_convenio
	,priorizado
	,ppac
	,epp.perinatal_ac
	,epp.addenda_perinatal
	,epp.fecha_addenda_perinatal
	,cpp1.categoria AS categoria_obstretico
	,cpp2.categoria AS categoria_neonatal
	,ref.nombre AS nombre_referente
	,des.internet
	,des.factura_descentralizada
	,des.factura_on_line
	,test.id_estado
	,test.descripcion
FROM efectores.efectores efe
LEFT JOIN efectores.tipo_efector tefe ON efe.id_tipo_efector = tefe.id_tipo_efector
LEFT JOIN efectores.tipo_categorizacion tcat ON efe.id_categorizacion = tcat.id_categorizacion
LEFT JOIN efectores.tipo_dependencia_administrativa dep ON efe.id_dependencia_administrativa = dep.id_dependencia_administrativa
LEFT JOIN efectores.compromiso_gestion cge ON efe.id_efector = cge.id_efector
LEFT JOIN efectores.convenio_administrativo cad ON efe.id_efector = cad.id_efector
LEFT JOIN efectores.referentes ref ON efe.id_efector = ref.id_efector
LEFT JOIN efectores.datos_geograficos geo ON efe.id_efector = geo.id_efector
LEFT JOIN sistema.provincias pro ON geo.id_provincia = pro.id_provincia
LEFT JOIN efectores.departamentos dto ON geo.id_provincia || geo.id_departamento = dto.id_provincia || dto.id_departamento
LEFT JOIN efectores.localidades loc ON geo.id_provincia || geo.id_departamento || geo.id_localidad = loc.id_provincia || loc.id_departamento || loc.id_localidad
LEFT JOIN efectores.efectores_ppac epp ON efe.id_efector = epp.id_efector
LEFT JOIN efectores.efectores_obstetricos obs ON efe.siisa = obs.siisa
LEFT JOIN efectores.categorias_ppac cpp1 ON obs.id_categoria = cpp1.id_categoria
LEFT JOIN efectores.efectores_neonatales neo ON efe.siisa = neo.siisa
LEFT JOIN efectores.categorias_ppac cpp2 ON neo.id_categoria = cpp2.id_categoria
LEFT JOIN efectores.descentralizacion des ON efe.id_efector = des.id_efector
LEFT JOIN efectores.tipo_estado test ON efe.id_estado = test.id_estado
LIMIT 10
