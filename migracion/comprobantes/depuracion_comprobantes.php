<?php
require 'conexion.php';

$sql = "
	CREATE TABLE comprobantes.inconsistencias
	(
	  efector character(14) NOT NULL,
	  numero_comprobante character varying(50) NOT NULL,
	  tipo_comprobante character(2) NOT NULL,
	  fecha_comprobante date,
	  fecha_recepcion date,
	  fecha_notificacion date,
	  fecha_liquidacion date,
	  fecha_debito_bancario date,
	  importe numeric,
	  importe_pagado numeric,
	  factura_debitada character varying(50),
	  concepto character varying(200),
	  lote integer,
	  tipo_inconsistencia integer
	)
	WITH (
	  OIDS=FALSE
	);
	ALTER TABLE comprobantes.inconsistencias
	  OWNER TO postgres;
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_01 
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_02 
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_03
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_04
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_05
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_06
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_07
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_08
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_09
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_10
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_11
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_12
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_13
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_14
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_15
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_16
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_17
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_18
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_19
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_20
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_21
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_22
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_23
	where efector not in (select cuie from efectores.efectores);
	
	insert into comprobantes.inconsistencias
	select * , '1'
	from comprobantes.c_24
	where efector not in (select cuie from efectores.efectores);
	
	delete from comprobantes.c_01 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_02 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_03 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_04 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_05 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_06 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_07 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_08 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_09 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_10 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_11 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_12 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_13 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_14 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_15 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_16 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_17 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_18 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_19 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_20 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_21 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_22 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_23 where efector not in (select cuie from efectores.efectores);
	delete from comprobantes.c_24 where efector not in (select cuie from efectores.efectores);
	
	
ALTER TABLE comprobantes.c_01
  ADD CONSTRAINT fkey_c_01_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_02
  ADD CONSTRAINT fkey_c_02_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_03
  ADD CONSTRAINT fkey_c_03_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_04
  ADD CONSTRAINT fkey_c_04_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_05
  ADD CONSTRAINT fkey_c_05_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_06
  ADD CONSTRAINT fkey_c_06_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_07
  ADD CONSTRAINT fkey_c_07_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_08
  ADD CONSTRAINT fkey_c_08_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_09
  ADD CONSTRAINT fkey_c_09_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      
ALTER TABLE comprobantes.c_10
  ADD CONSTRAINT fkey_c_10_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_11
  ADD CONSTRAINT fkey_c_11_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_12
  ADD CONSTRAINT fkey_c_12_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_13
  ADD CONSTRAINT fkey_c_13_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_14
  ADD CONSTRAINT fkey_c_14_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_15
  ADD CONSTRAINT fkey_c_15_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_16
  ADD CONSTRAINT fkey_c_16_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_17
  ADD CONSTRAINT fkey_c_17_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_18
  ADD CONSTRAINT fkey_c_18_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_19
  ADD CONSTRAINT fkey_c_19_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_20
  ADD CONSTRAINT fkey_c_20_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_21
  ADD CONSTRAINT fkey_c_21_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_22
  ADD CONSTRAINT fkey_c_22_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_23
  ADD CONSTRAINT fkey_c_23_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE comprobantes.c_24
  ADD CONSTRAINT fkey_c_24_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
	
";


?>
