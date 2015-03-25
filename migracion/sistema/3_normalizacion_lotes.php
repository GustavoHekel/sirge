<?php
require_once '../conexion.php';

//$t = "truncate sistema.lotes_new cascade";
//pg_query ($t);

function GetIDSubida ($lote) {
	$IDsql = "select id_carga from sistema.cargas_archivos where lote = $lote";
	$IDres = pg_query ($IDsql);
	
	if (pg_num_rows ($IDres)) {
		return pg_fetch_row ($IDres , 0)[0];
	}
}

$crear  = "

CREATE TABLE sistema.lotes_new
(
  lote serial NOT NULL,
  id_subida integer,
  id_usuario integer,
  id_provincia character(2) NOT NULL,
  id_estado integer DEFAULT 0,
  registros_in integer,
  registros_out integer,
  registros_mod integer,
  inicio timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  fin timestamp without time zone,
  CONSTRAINT lotes_new_pkey PRIMARY KEY (lote),
  CONSTRAINT fkey_lotes_id_estado FOREIGN KEY (id_estado)
      REFERENCES sistema.estados (id_estado) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_id_padron FOREIGN KEY (id_padron)
      REFERENCES sistema.padrones (id_padron) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_id_subida FOREIGN KEY (id_subida)
      REFERENCES sistema.subidas (id_subida) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_id_usuario FOREIGN KEY (id_usuario)
      REFERENCES sistema.usuarios (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_id_provincia FOREIGN KEY (id_provincia)
	  REFERENCES sistema.entidades (id_entidad) MATCH SIMPLE
	  ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE sistema.lotes_new
  OWNER TO postgres;

-- Index: sistema.fki_fkey_lotes_id_estado

-- DROP INDEX sistema.fki_fkey_lotes_id_estado;

CREATE INDEX fki_fkey_lotes_id_estado
  ON sistema.lotes_new
  USING btree
  (id_estado);

-- Index: sistema.fki_fkey_lotes_id_subida

-- DROP INDEX sistema.fki_fkey_lotes_id_subida;

CREATE INDEX fki_fkey_lotes_id_subida
  ON sistema.lotes_new
  USING btree
  (id_subida);

-- Index: sistema.fki_fkey_lotes_id_usuario

-- DROP INDEX sistema.fki_fkey_lotes_id_usuario;

CREATE INDEX fki_fkey_lotes_id_usuario
  ON sistema.lotes_new
  USING btree
  (id_usuario);
  
SELECT setval('sistema.lotes_new_lote_seq', 4999, true);

CREATE TABLE sistema.lotes_aceptados
(
  lote integer NOT NULL,
  id_usuario integer,
  fecha_aceptado timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  CONSTRAINT lotes_aceptados_pkey PRIMARY KEY (lote),
  CONSTRAINT fkey_lotes_aceptados_id_usuario FOREIGN KEY (id_usuario)
      REFERENCES sistema.usuarios (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_aceptados_lote FOREIGN KEY (lote)
      REFERENCES sistema.lotes_new (lote) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE sistema.lotes_aceptados
  OWNER TO postgres;
  
CREATE TABLE sistema.lotes_eliminados
(
  lote integer NOT NULL,
  id_usuario integer,
  fecha_eliminado timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
  CONSTRAINT lotes_eliminados_pkey PRIMARY KEY (lote),
  CONSTRAINT fkey_lotes_eliminados_id_usuario FOREIGN KEY (id_usuario)
      REFERENCES sistema.usuarios (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fkey_lotes_eliminados_lote FOREIGN KEY (lote)
      REFERENCES sistema.lotes_new (lote) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE sistema.lotes_eliminados
  OWNER TO postgres;";

pg_query ($crear);

$lotes = array(
	'lote' => '',
	'id_subida' => '',
	'id_usuario' => '',
	'id_provincia' => '',
	'id_estado' => '',
	'registros_in' => '',
	'registros_out' => '',
	'registros_mod' => 0,
	'inicio' => '',
	'fin' => ''
	
);

$lotes_aceptados = array(
	'lote' => '',
	'id_usuario' => '',
	'fecha' => ''
);

$lotes_eliminados = array(
	'lote' => '',
	'id_usuario' => '',
	'fecha' => ''
);

$sql = "select * from sistema.lotes";
$res = pg_query ($sql);

while ($reg = pg_fetch_assoc ($res)) {
	
	$lotes['lote'] = $reg['lote'];
	$lotes['id_subida'] = GetIDSubida ($reg['lote']);
	$lotes['id_usuario'] = $reg['id_usuario_proceso'];
	$lotes['id_provincia'] = strlen ($reg['id_provincia']) == 2 ? $reg['id_provincia'] : '0' . $reg['id_provincia'];
	$lotes['id_estado'] = $reg['id_estado'];
	$lotes['registros_in'] = $reg['registros_insertados'];
	$lotes['registros_out'] = $reg['registros_rechazados'];
	$lotes['inicio'] = $reg['inicio'];
	$lotes['fin'] = $reg['fin'];
	
	$sql_lotes = "insert into sistema.lotes_new values ('" . implode ("','" , $lotes) . "')";
	//echo $sql_lotes; die();
	pg_query ($sql_lotes);
	
	if ($reg['id_estado'] == 1) {
		
		$lotes_aceptados['lote'] = $reg['lote'];
		$lotes_aceptados['id_usuario'] = $reg['id_usuario_cierre_lote'];
		$lotes_aceptados['fecha'] = $reg['fecha_cierre_lote'];
		
		$sql_lotes_aceptados = "insert into sistema.lotes_aceptados values ('" . implode ("','" , $lotes_aceptados) . "')";
		pg_query ($sql_lotes_aceptados);
		
	} else if ($reg['id_estado'] == 3) {
		
		$lotes_eliminados['lote'] = $reg['lote'];
		$lotes_eliminados['id_usuario'] = $reg['id_usuario_baja_lote'];
		$lotes_eliminados['fecha'] = $reg['fecha_baja_lote'];
		
		$sql_lotes_eliminados = "insert into sistema.lotes_eliminados values ('" . implode ("','" , $lotes_eliminados) . "')";
		pg_query ($sql_lotes_eliminados);
		
	}
	
}

$sql = "

DROP TABLE sistema.lotes CASCADE;

ALTER TABLE prestaciones.lotes_eliminados
  ADD CONSTRAINT fkey_lotes_eliminados FOREIGN KEY (lote)
      REFERENCES sistema.lotes_new (lote) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE prestaciones.rechazados
  ADD CONSTRAINT rechazados_lote FOREIGN KEY (lote)
      REFERENCES sistema.lotes_new (lote) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE sistema.lotes_new RENAME TO lotes;";
pg_query ($sql);
