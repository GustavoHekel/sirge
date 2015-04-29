<?php

require_once 'conexion.php';

$sql = "

/*
 *
 * CREAMOS LOS NUEVOS ESQUEMAS
 *
 */

	CREATE SCHEMA consultas	AUTHORIZATION postgres;
	CREATE SCHEMA logs AUTHORIZATION postgres;
	CREATE SCHEMA ddjj AUTHORIZATION postgres;
    CREATE SCHEMA osp AUTHORIZATION postgres;
    CREATE SCHEMA profe AUTHORIZATION postgres;
    CREATE SCHEMA sss AUTHORIZATION postgres;

/*

/*
 *
 * RENOMBRO ESQUEMAS
 *
 */

	ALTER SCHEMA compromiso_anual_2014 name RENAME TO compromiso_anual
	ALTER TABLE compromiso_anual.metas_descentralizacion ADD COLUMN year integer;
    ALTER TABLE compromiso_anual.metas_facturacion ADD COLUMN year integer;
    ALTER TABLE compromiso_anual.metas_codigos_validos ADD COLUMN year integer;
	UPDATE compromiso_anual.metas_descentralizacion SET year = 2014;
    UPDATE compromiso_anual.metas_codigos_validos SET year = 2014;
    UPDATE compromiso_anual.metas_facturacion SET year = 2014;

    ALTER TABLE compromiso_anual.metas_descentralizacion ALTER COLUMN year SET NOT NULL;
    ALTER TABLE compromiso_anual.metas_facturacion ALTER COLUMN year SET NOT NULL;
    ALTER TABLE compromiso_anual.metas_codigos_validos ALTER COLUMN year SET NOT NULL;

    ALTER TABLE compromiso_anual.metas_descentralizacion DROP CONSTRAINT metas_descentralizacion_pkey;
	ALTER TABLE compromiso_anual.metas_descentralizacion ADD CONSTRAINT metas_descentralizacion_pkey PRIMARY KEY (id_provincia, year);
	ALTER TABLE compromiso_anual.metas_descentralizacion DROP CONSTRAINT metas_facturacion_pkey;
	ALTER TABLE compromiso_anual.metas_descentralizacion ADD CONSTRAINT metas_facturacion_pkey PRIMARY KEY (id_provincia, year);
	ALTER TABLE compromiso_anual.metas_descentralizacion DROP CONSTRAINT metas_codigos_validos_pkey;
	ALTER TABLE compromiso_anual.metas_descentralizacion ADD CONSTRAINT metas_codigos_validos_pkey PRIMARY KEY (id_provincia, year);

/*


 *
 * MODIFICO los EFECTORES PRIORIZADOS
 *
 */

 UPDATE efectores.efectores SET priorizado = 'N'
	WHERE cuie NOT IN ('A03219','A03221','A03223','A03228','A03231','A03593','A03594','A09043','A09079','A09080','A09784','A09785','A09787','A09788','A09792','A09795','A09796','A09798','A09804','A09807','A09809','A09811','A10289','A96901','A96902','B00007','B00013','B00018','B00070','B00071','B00135','B00137','B00200','B00233','B00235','B00469','B00471','B00473','B00533','B00535','B00574','B00575','B00576','B00589','B00681','B00696','B00730','B00734','B00830','B01056','B01060','B01082','B02001','B02005','B02006','B02009','B02011','B02014','B02017','B02027','B02034','B02037','B02040','B02043','B02045','B02049','B02057','B02072','B02073','B02074','B02077','B02113','B02114','B02135','B02136','B02169','B02213','B02215','B02219','B02226','B02248','B02252','B02255','B02273','B02286','B02305','B02309','B02312','B02321','B02326','B02329','B02334','B02338','B02342','B02344','B02347','B02350','B02356','B02359','B02376','B02377','B02384','B02390','B02391','B02398','B02400','B02404','B02424','B02438','B02440','B02444','B02447','B02448','B02470','B02479','B02481','B02486','B02487','B02579','B02592','B02596','B02597','B02600','B02601','B02603','B02635','B03673','B03725','B03761','B07743','B08069','B08118','B08165','B08167','B08173','B08216','B08231','B08240','B08326','B08327','B08328','B08342','B08351','B08352','B08354','B08356','B08358','B08364','B08831','B08832','B08833','B08868','B08872','B08874','B08881','B08890','B08892','B08925','B08951','B08963','B09002','B09328','B09331','B09352','B09355','B10381','B12006','B12023','B12024','B12026','B12049','B12051','B12076','B12078','B12084','B12085','B12127','B12160','B12163','B12167','B12197','B12199','B12210','B12221','B12269','B12274','B90734','B90776','B90824','C02644','C02646','C02653','C02655','C02657','C02680','C02684','C86003','C86015','D03252','D03306','D05071','D05111','D05113','D05127','D05128','D05142','D05148','D05150','D05151','D12007','E00050','E00051','E00059','E00156','E00310','E00311','E00322','E00349','E00362','E01450','E02915','E02919','E02933','E02944','E02946','E02965','E02971','E06492','E06495','E06500','E06510','E06528','E06545','E06557','E06607','E06610','E06621','E06622','E06633','E06639','E09374','E09376','E12017','E12042','E12044','E93401','E93404','E93415','E93430','E93435','E93441','F00127','F00128','F00129','F00173','F00319','F00320','F00338','F00447','F00448','F00451','F00462','F00463','F00749','F00751','F00867','F00868','F00869','F00870','F00871','F00872','F03010','F03021','F03749','F06264','F06265','F06268','F06276','F06283','F06304','F06312','F06365','F06390','F06392','F06393','F06402','G03568','G03569','G03571','G03575','G03577','G03579','G03580','G03583','G03591','G03592','G07073','G07080','G07087','G07092','G07094','G07099','G07104','G07107','G07111','G07113','G07114','G07652','G09995','G10436','G12020','G12022','G12028','G12140','G12146','G12147','G12151','G12162','G98911','G98930','G98932','G98935','H00424','H00425','H00606','H00608','H00613','H00614','H00877','H00892','H00895','H00897','H02857','H02860','H02866','H02872','H04375','H04405','H04407','H04409','H04425','H04435','H04452','H04471','H04472','H04477','H04487','H04489','H04490','H04498','H04501','H04519','H04572','H04575','H04603','H14085','H20000','J00247','J00277','J00313','J00382','J00403','J00571','J00611','J00920','J00931','J00933','J00936','J01523','J01524','J01525','J02724','J02735','J03047','J03056','J03069','J03070','J03079','J03081','J03084','J03087','J03092','J03096','J04156','J04182','J04187','J04214','J04216','J04244','J04265','J04290','J04318','J20004','J20006','J20012','J20014','J20022','J20046','K02687','K02689','K02690','K02691','K02696','K02697','K02716','K02717','K02722','K04611','K04612','K04615','K04620','K04621','K04642','K04681','K04693','K04707','K04727','K04738','K04746','K04777','K04797','K04820','K04848','K04879','K04890','K20003','K20005','K20015','K90844','L00677','L00678','L00684','L00692','L00726','L00951','L00952','L00953','L00954','L00957','L01515','L03040','L03578','L03857','L03858','L03874','L03875','L03877','L03887','L03888','L03897','L03911','L03918','L03920','L03921','L03923','L03926','L03929','L03930','L03944','L03949','L03960','L03963','L03970','L03997','L04003','L04014','L04025','L04035','L04050','L04081','L04346','L10060','M00079','M00175','M00970','M01095','M01096','M01097','M01101','M01112','M01115','M01128','M03141','M03142','M03770','M07135','M07142','M07165','M07237','M07306','M07328','M07338','M95302','M95327','M95348','M95365','M95366','N00205','N00297','N00329','N00419','N00538','N01138','N01145','N03156','N03159','N03163','N03168','N03169','N03172','N03173','N03586','N05170','N05175','N05182','N05296','N05312','N05381','N05405','N05412','N05413','N05434','N05437','N05445','N10483','N10769','N20012','N20049','P01159','P01172','P03025','P03029','P03031','P03034','P03797','P03809','P03812','P06447','P06451','P06453','P06465','P06467','P06479','P06483','Q03111','Q03112','Q03119','Q06391','Q06392','Q06397','Q06410','Q06417','Q06424','Q06427','Q06430','Q06432','Q06435','Q06436','Q06445','R00309','R00335','R00422','R00527','R00644','R00646','R00647','R00676','R01195','R01197','R01198','R01199','R01200','R01201','R01202','R01216','R01218','R01220','R01535','R03175','R03176','R03178','R03202','R03217','R03819','R03821','R03828','R03835','R03837','R03839','R03841','R03843','R03852','R04079','R04083','R04089','R04097','R04098','R04100','R04102','R04103','R04104','R04105','R04107','R04113','R04115','R04122','R04140','R04146','R12001','R12002','R12007','R12015','R12021','S00061','S00066','S00067','S00162','S00628','S01231','S01244','S01276','S01277','S01294','S03323','S03324','S03333','S03422','S03436','S03448','S06691','S06701','S06726','S06732','S06751','S06907','S09115','S09138','S09139','S09195','S10306','S98644','S98669','S98686','S98786','S98791','S98806','S98809','S98820','T00023','T00027','T00095','T00108','T00388','T00390','T00391','T00392','T01326','T03643','T03653','T03654','T06024','T06031','T06112','T06118','T06139','T06145','T06155','T06163','T06191','T06203','T06214','T06243','T06253','T06255','T06256','T10311','T10313','T10348','T10350','T20029','T20030','U00397','U00508','U00514','U01331','U01341','U01349','U02881','U02904','U02905','U02906','U02907','U02911','U04350','U07353','U07355','U10524','U77004','V03627','V03629','V03631','V03632','V07035','V07036','V07037','V12001','V99212','W00352','W00489','W00504','W01373','W01497','W01504','W06980','W06984','W06990','W06992','W06999','W07010','W10016','W10017','X00477','X02779','X02784','X02800','X02810','X02812','X02816','X02818','X02819','X02820','X02826','X02835','X02836','X02839','X05487','X05516','X05538','X05540','X05574','X05605','X05613','X05640','X05646','X05649','X05650','X05659','X05677','X05690','X05691','X05700','X05712','X05730','X05743','X05776','X05778','X05809','X05811','X05813','X05825','X05837','X05865','X05870','X05883','X05894','X05899','X05949','X05954','X05986','X06006','X06013','X08224','X08226','X08837','X11006','X11036','X11053','X11099','X20020','X20054','X20062','X20068','X20092','Y03274','Y03275','Y03276','Y03278','Y03285','Y03301','Y04913','Y04914','Y04930','Y04947','Y04948','Y04990','Y04994','Y20007','Y20008','Z03550','Z03557','Z07021','Z07022','Z07025','Z07026','Z07042','Z11944','Z11946');

 UPDATE efectores.efectores SET priorizado = 'S'
	WHERE cuie IN ('A03219','A03221','A03223','A03228','A03231','A03593','A03594','A09043','A09079','A09080','A09784','A09785','A09787','A09788','A09792','A09795','A09796','A09798','A09804','A09807','A09809','A09811','A10289','A96901','A96902','B00007','B00013','B00018','B00070','B00071','B00135','B00137','B00200','B00233','B00235','B00469','B00471','B00473','B00533','B00535','B00574','B00575','B00576','B00589','B00681','B00696','B00730','B00734','B00830','B01056','B01060','B01082','B02001','B02005','B02006','B02009','B02011','B02014','B02017','B02027','B02034','B02037','B02040','B02043','B02045','B02049','B02057','B02072','B02073','B02074','B02077','B02113','B02114','B02135','B02136','B02169','B02213','B02215','B02219','B02226','B02248','B02252','B02255','B02273','B02286','B02305','B02309','B02312','B02321','B02326','B02329','B02334','B02338','B02342','B02344','B02347','B02350','B02356','B02359','B02376','B02377','B02384','B02390','B02391','B02398','B02400','B02404','B02424','B02438','B02440','B02444','B02447','B02448','B02470','B02479','B02481','B02486','B02487','B02579','B02592','B02596','B02597','B02600','B02601','B02603','B02635','B03673','B03725','B03761','B07743','B08069','B08118','B08165','B08167','B08173','B08216','B08231','B08240','B08326','B08327','B08328','B08342','B08351','B08352','B08354','B08356','B08358','B08364','B08831','B08832','B08833','B08868','B08872','B08874','B08881','B08890','B08892','B08925','B08951','B08963','B09002','B09328','B09331','B09352','B09355','B10381','B12006','B12023','B12024','B12026','B12049','B12051','B12076','B12078','B12084','B12085','B12127','B12160','B12163','B12167','B12197','B12199','B12210','B12221','B12269','B12274','B90734','B90776','B90824','C02644','C02646','C02653','C02655','C02657','C02680','C02684','C86003','C86015','D03252','D03306','D05071','D05111','D05113','D05127','D05128','D05142','D05148','D05150','D05151','D12007','E00050','E00051','E00059','E00156','E00310','E00311','E00322','E00349','E00362','E01450','E02915','E02919','E02933','E02944','E02946','E02965','E02971','E06492','E06495','E06500','E06510','E06528','E06545','E06557','E06607','E06610','E06621','E06622','E06633','E06639','E09374','E09376','E12017','E12042','E12044','E93401','E93404','E93415','E93430','E93435','E93441','F00127','F00128','F00129','F00173','F00319','F00320','F00338','F00447','F00448','F00451','F00462','F00463','F00749','F00751','F00867','F00868','F00869','F00870','F00871','F00872','F03010','F03021','F03749','F06264','F06265','F06268','F06276','F06283','F06304','F06312','F06365','F06390','F06392','F06393','F06402','G03568','G03569','G03571','G03575','G03577','G03579','G03580','G03583','G03591','G03592','G07073','G07080','G07087','G07092','G07094','G07099','G07104','G07107','G07111','G07113','G07114','G07652','G09995','G10436','G12020','G12022','G12028','G12140','G12146','G12147','G12151','G12162','G98911','G98930','G98932','G98935','H00424','H00425','H00606','H00608','H00613','H00614','H00877','H00892','H00895','H00897','H02857','H02860','H02866','H02872','H04375','H04405','H04407','H04409','H04425','H04435','H04452','H04471','H04472','H04477','H04487','H04489','H04490','H04498','H04501','H04519','H04572','H04575','H04603','H14085','H20000','J00247','J00277','J00313','J00382','J00403','J00571','J00611','J00920','J00931','J00933','J00936','J01523','J01524','J01525','J02724','J02735','J03047','J03056','J03069','J03070','J03079','J03081','J03084','J03087','J03092','J03096','J04156','J04182','J04187','J04214','J04216','J04244','J04265','J04290','J04318','J20004','J20006','J20012','J20014','J20022','J20046','K02687','K02689','K02690','K02691','K02696','K02697','K02716','K02717','K02722','K04611','K04612','K04615','K04620','K04621','K04642','K04681','K04693','K04707','K04727','K04738','K04746','K04777','K04797','K04820','K04848','K04879','K04890','K20003','K20005','K20015','K90844','L00677','L00678','L00684','L00692','L00726','L00951','L00952','L00953','L00954','L00957','L01515','L03040','L03578','L03857','L03858','L03874','L03875','L03877','L03887','L03888','L03897','L03911','L03918','L03920','L03921','L03923','L03926','L03929','L03930','L03944','L03949','L03960','L03963','L03970','L03997','L04003','L04014','L04025','L04035','L04050','L04081','L04346','L10060','M00079','M00175','M00970','M01095','M01096','M01097','M01101','M01112','M01115','M01128','M03141','M03142','M03770','M07135','M07142','M07165','M07237','M07306','M07328','M07338','M95302','M95327','M95348','M95365','M95366','N00205','N00297','N00329','N00419','N00538','N01138','N01145','N03156','N03159','N03163','N03168','N03169','N03172','N03173','N03586','N05170','N05175','N05182','N05296','N05312','N05381','N05405','N05412','N05413','N05434','N05437','N05445','N10483','N10769','N20012','N20049','P01159','P01172','P03025','P03029','P03031','P03034','P03797','P03809','P03812','P06447','P06451','P06453','P06465','P06467','P06479','P06483','Q03111','Q03112','Q03119','Q06391','Q06392','Q06397','Q06410','Q06417','Q06424','Q06427','Q06430','Q06432','Q06435','Q06436','Q06445','R00309','R00335','R00422','R00527','R00644','R00646','R00647','R00676','R01195','R01197','R01198','R01199','R01200','R01201','R01202','R01216','R01218','R01220','R01535','R03175','R03176','R03178','R03202','R03217','R03819','R03821','R03828','R03835','R03837','R03839','R03841','R03843','R03852','R04079','R04083','R04089','R04097','R04098','R04100','R04102','R04103','R04104','R04105','R04107','R04113','R04115','R04122','R04140','R04146','R12001','R12002','R12007','R12015','R12021','S00061','S00066','S00067','S00162','S00628','S01231','S01244','S01276','S01277','S01294','S03323','S03324','S03333','S03422','S03436','S03448','S06691','S06701','S06726','S06732','S06751','S06907','S09115','S09138','S09139','S09195','S10306','S98644','S98669','S98686','S98786','S98791','S98806','S98809','S98820','T00023','T00027','T00095','T00108','T00388','T00390','T00391','T00392','T01326','T03643','T03653','T03654','T06024','T06031','T06112','T06118','T06139','T06145','T06155','T06163','T06191','T06203','T06214','T06243','T06253','T06255','T06256','T10311','T10313','T10348','T10350','T20029','T20030','U00397','U00508','U00514','U01331','U01341','U01349','U02881','U02904','U02905','U02906','U02907','U02911','U04350','U07353','U07355','U10524','U77004','V03627','V03629','V03631','V03632','V07035','V07036','V07037','V12001','V99212','W00352','W00489','W00504','W01373','W01497','W01504','W06980','W06984','W06990','W06992','W06999','W07010','W10016','W10017','X00477','X02779','X02784','X02800','X02810','X02812','X02816','X02818','X02819','X02820','X02826','X02835','X02836','X02839','X05487','X05516','X05538','X05540','X05574','X05605','X05613','X05640','X05646','X05649','X05650','X05659','X05677','X05690','X05691','X05700','X05712','X05730','X05743','X05776','X05778','X05809','X05811','X05813','X05825','X05837','X05865','X05870','X05883','X05894','X05899','X05949','X05954','X05986','X06006','X06013','X08224','X08226','X08837','X11006','X11036','X11053','X11099','X20020','X20054','X20062','X20068','X20092','Y03274','Y03275','Y03276','Y03278','Y03285','Y03301','Y04913','Y04914','Y04930','Y04947','Y04948','Y04990','Y04994','Y20007','Y20008','Z03550','Z03557','Z07021','Z07022','Z07025','Z07026','Z07042','Z11944','Z11946');


/*


 *
 * CREAMOS LA vista PROVINCIAS
 *
 */

	DROP TABLE sistema.provincias CASCADE;

	CREATE OR REPLACE VIEW sistema.provincias AS
	 SELECT entidades.id_entidad as id_provincia, entidades.id_tipo_entidad, entidades.descripcion, entidades.id_region
	   FROM sistema.entidades
	  WHERE entidades.id_entidad < '25'::bpchar;

/*
 *
 * RENOMBRAMOS TABLAS Y CAMPOS
 *
 */

	ALTER TABLE sistema.impresiones_ddjj RENAME TO impresiones_ddjj_sirge;
	ALTER TABLE sistema.impresiones_ddjj_doiu RENAME TO impresiones_ddjj_doiu9;
	ALTER TABLE sistema.tipo_estado RENAME TO estados;
	ALTER TABLE sistema.tipo_padron RENAME TO padrones;
	ALTER TABLE sistema.tipo_region RENAME TO regiones;
	ALTER TABLE sistema.log_queries_din RENAME TO log_queries_dinamicos;

	ALTER TABLE sistema.comentarios RENAME COLUMN fecha TO fecha_comentario;
	ALTER TABLE sistema.log_queries_dinamicos RENAME COLUMN id_consulta TO id_query_dinamico;

	ALTER TABLE sistema.entidades ADD COLUMN id_region integer;
	ALTER TABLE sistema.entidades ADD COLUMN descripcion character varying(50);

	UPDATE sistema.entidades SET id_region = 1, descripcion = 'CIUDAD AUTONOMA DE BUENOS AIRES' where id_entidad = '01';
	UPDATE sistema.entidades SET id_region = 1, descripcion = 'BUENOS AIRES' where id_entidad = '02';
	UPDATE sistema.entidades SET id_region = 3, descripcion = 'CATAMARCA' where id_entidad = '03';
	UPDATE sistema.entidades SET id_region = 1, descripcion = 'CÓRDOBA' where id_entidad = '04';
	UPDATE sistema.entidades SET id_region = 2, descripcion = 'CORRIENTES' where id_entidad = '05';
	UPDATE sistema.entidades SET id_region = 2, descripcion = 'ENTRE RIOS' where id_entidad = '06';
	UPDATE sistema.entidades SET id_region = 3, descripcion = 'JUJUY' where id_entidad = '07';
	UPDATE sistema.entidades SET id_region = 4, descripcion = 'LA RIOJA' where id_entidad = '08';
	UPDATE sistema.entidades SET id_region = 4, descripcion = 'MENDOZA' where id_entidad = '09';
	UPDATE sistema.entidades SET id_region = 3, descripcion = 'SALTA' where id_entidad = '10';
	UPDATE sistema.entidades SET id_region = 4, descripcion = 'SAN JUAN' where id_entidad = '11';
	UPDATE sistema.entidades SET id_region = 4, descripcion = 'SAN LUIS' where id_entidad = '12';
	UPDATE sistema.entidades SET id_region = 1, descripcion = 'SANTA FE' where id_entidad = '13';
	UPDATE sistema.entidades SET id_region = 3, descripcion = 'SANTIAGO DEL ESTERO' where id_entidad = '14';
	UPDATE sistema.entidades SET id_region = 3, descripcion = 'TUCUMÁN' where id_entidad = '15';
	UPDATE sistema.entidades SET id_region = 2, descripcion = 'CHACO' where id_entidad = '16';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'CHUBUT' where id_entidad = '17';
	UPDATE sistema.entidades SET id_region = 2, descripcion = 'FORMOSA' where id_entidad = '18';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'LA PAMPA' where id_entidad = '19';
	UPDATE sistema.entidades SET id_region = 2, descripcion = 'MISIONES' where id_entidad = '20';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'NEUQUÉN' where id_entidad = '21';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'RIO NEGRO' where id_entidad = '22';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'SANTA CRUZ' where id_entidad = '23';
	UPDATE sistema.entidades SET id_region = 5, descripcion = 'TIERRA DEL FUEGO' where id_entidad = '24';
	UPDATE sistema.entidades SET id_region = 0, descripcion = 'UNIDAD EJECUTORA CENTRAL' where id_entidad = '25';
	UPDATE sistema.entidades SET id_region = 0, descripcion = 'PROGRAMA FEDERAL DE SALUD' where id_entidad = '26';
	UPDATE sistema.entidades SET id_region = 0, descripcion = 'SUPERINTENDENCIA DE SERVICIOS DE SALUD' where id_entidad = '27';

	ALTER TABLE sistema.procesos_obras_sociales SET SCHEMA puco;
	ALTER TABLE sistema.queries_estandar SET SCHEMA consultas;
	ALTER TABLE sistema.queries_automaticas SET SCHEMA consultas;
	ALTER TABLE sistema.queries_automaticas_destinatarios SET SCHEMA consultas;
	ALTER TABLE sistema.log_logins SET SCHEMA logs;
	ALTER TABLE sistema.log_queries_dinamicos SET SCHEMA logs;
	ALTER TABLE sistema.impresiones_ddjj_backup SET SCHEMA ddjj;
	ALTER TABLE sistema.impresiones_ddjj_sirge SET SCHEMA ddjj;
	ALTER TABLE sistema.impresiones_ddjj_doiu9 SET SCHEMA ddjj;
<<<<<<< HEAD

=======
    ALTER TALBE efectores.departametos SET SCHEMA geo;
    ALTER TABLE efectores.localidades SET SCHEMA geo;
    alter table efectores.entidades set schema geo

>>>>>>> 95d862982f4c71e4f06c28f4d5f3b602f3d3d1c8
	ALTER TABLE ddjj.impresiones_ddjj_backup RENAME TO backup;
	ALTER TABLE ddjj.impresiones_ddjj_sirge RENAME TO sirge;
	ALTER TABLE ddjj.impresiones_ddjj_doiu9 RENAME TO doiu9;

	CREATE TABLE ddjj.sirge2
	(
	  id_impresion integer NOT NULL DEFAULT nextval('ddjj.impresiones_ddjj_sirge2_id_impresion_seq'::regclass),
	  fecha_impresion timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
	  lote integer[],
	  CONSTRAINT impresiones_ddjj_sirge2_pkey PRIMARY KEY (id_impresion)
	)
	WITH (
	  OIDS=FALSE
	);
	ALTER TABLE ddjj.sirge2
	  OWNER TO postgres;

	insert into ddjj.sirge2
	select
		row_number() over()
		, date_trunc ('second' , fecha_impresion_ddjj )
		, array_agg(lote)
		, id_provincia
	from
		ddjj.sirge group by 2;

	DROP TABLE ddjj.sirge;
	ALTER TABLE ddjj.sirge2 RENAME TO sirge;

	DROP TABLE sistema.obras_sociales;
	DROP TABLE sistema.consultas;
	DROP TABLE sistema.consultas_automaticas;
	DROP TABLE sistema.entidades_administrativas;
	DROP TABLE sistema.entidades_sanitarias;
	DROP TABLE sistema.nomenclador_unico CASCADE;

    alter table puco.osp_01 set schema osp;
    alter table puco.osp_02 set schema osp;
    alter table puco.osp_03 set schema osp;
    alter table puco.osp_04 set schema osp;
    alter table puco.osp_05 set schema osp;
    alter table puco.osp_06 set schema osp;
    alter table puco.osp_07 set schema osp;
    alter table puco.osp_08 set schema osp;
    alter table puco.osp_09 set schema osp;
    alter table puco.osp_10 set schema osp;
    alter table puco.osp_11 set schema osp;
    alter table puco.osp_12 set schema osp;
    alter table puco.osp_13 set schema osp;
    alter table puco.osp_14 set schema osp;
    alter table puco.osp_15 set schema osp;
    alter table puco.osp_16 set schema osp;
    alter table puco.osp_17 set schema osp;
    alter table puco.osp_18 set schema osp;
    alter table puco.osp_19 set schema osp;
    alter table puco.osp_20 set schema osp;
    alter table puco.osp_21 set schema osp;
    alter table puco.osp_22 set schema osp;
    alter table puco.osp_23 set schema osp;
    alter table puco.osp_24 set schema osp;


CREATE TABLE osp.rechazados
(
  id_provincia character(2),
  fecha_rechazo timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  motivos character varying(300),
  registro_rechazado character varying(300),
  lote integer
);

CREATE TABLE profe.rechazados
(
  id_provincia character(2),
  fecha_rechazo timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  motivos character varying(300),
  registro_rechazado character varying(300),
  lote integer
);

CREATE TABLE sss.rechazados
(
  id_provincia character(2),
  fecha_rechazo timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  motivos character varying(300),
  registro_rechazado character varying(300),
  lote integer
);

CREATE TABLE profe.beneficiarios
(
  tipo_documento character(3),
  numero_documento bigint,
  nombre_apellido character varying(60),
  sexo character(1),
  fecha_nacimiento date,
  fecha_alta date,
  id_beneficiario_profe bigint,
  id_parentezco smallint,
  ley_aplicada character(2),
  fecha_desde date,
  fecha_hasta date,
  id_provincia character(2),
  codigo_os integer DEFAULT 997001,
  lote integer
);

";
