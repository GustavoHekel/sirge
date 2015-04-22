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
