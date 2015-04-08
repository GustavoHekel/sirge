<?php
require 'conexion.php';

$sql = "

	CREATE TABLE aplicacion_fondos.inconsistencias
	(
	  id_gasto serial NOT NULL,
	  id_provincia character(2),
	  efector character varying(14),
	  fecha_gasto date,
	  periodo character(7),
	  numero_comprobante character varying(50),
	  codigo_gasto integer,
	  subcodigo_gasto integer,
	  efector_cesion character varying(14),
	  monto numeric,
	  concepto character varying(200),
	  lote integer,
	  tipo_inconsistencia integer,
	  CONSTRAINT inconsistencias_pkey PRIMARY KEY (id_gasto)
	)
	WITH (
	  OIDS=FALSE
	);
	ALTER TABLE aplicacion_fondos.inconsistencias
	  OWNER TO postgres;

	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '01' , * , '1'
	from aplicacion_fondos.a_01
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '02' , * , '1'
	from aplicacion_fondos.a_02
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '03' , * , '1'
	from aplicacion_fondos.a_03
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '04' , * , '1'
	from aplicacion_fondos.a_04
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '05' , * , '1'
	from aplicacion_fondos.a_05
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '06' , * , '1'
	from aplicacion_fondos.a_06
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '07' , * , '1'
	from aplicacion_fondos.a_07
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '08' , * , '1'
	from aplicacion_fondos.a_08
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '09' , * , '1'
	from aplicacion_fondos.a_09
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '10' , * , '1'
	from aplicacion_fondos.a_10
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '11' , * , '1'
	from aplicacion_fondos.a_11
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '12' , * , '1'
	from aplicacion_fondos.a_12
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '13' , * , '1'
	from aplicacion_fondos.a_13
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '14' , * , '1'
	from aplicacion_fondos.a_14
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '15' , * , '1'
	from aplicacion_fondos.a_15
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '16' , * , '1'
	from aplicacion_fondos.a_16
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '17' , * , '1'
	from aplicacion_fondos.a_17
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '18' , * , '1'
	from aplicacion_fondos.a_18
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '19' , * , '1'
	from aplicacion_fondos.a_19
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '20' , * , '1'
	from aplicacion_fondos.a_20
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '21' , * , '1'
	from aplicacion_fondos.a_21
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '22' , * , '1'
	from aplicacion_fondos.a_22
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '23' , * , '1'
	from aplicacion_fondos.a_23
	where efector not in (select cuie from efectores.efectores);
	
	insert into aplicacion_fondos.inconsistencias (id_provincia , efector , fecha_gasto , periodo , numero_comprobante , codigo_gasto , subcodigo_gasto , efector_cesion , monto , concepto , lote , tipo_inconsistencia)
	select '24' , * , '1'
	from aplicacion_fondos.a_24
	where efector not in (select cuie from efectores.efectores);
	
	delete from aplicacion_fondos.a_01 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_02 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_03 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_04 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_05 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_06 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_07 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_08 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_09 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_10 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_11 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_12 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_13 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_14 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_15 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_16 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_17 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_18 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_19 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_20 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_21 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_22 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_23 where efector not in (select cuie from efectores.efectores);
	delete from aplicacion_fondos.a_24 where efector not in (select cuie from efectores.efectores);
	
	  ALTER TABLE aplicacion_fondos.a_01
  ADD CONSTRAINT fkey_a01_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_02
  ADD CONSTRAINT fkey_a02_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
	
	ALTER TABLE aplicacion_fondos.a_03
  ADD CONSTRAINT fkey_a03_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_04
  ADD CONSTRAINT fkey_a04_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_05
  ADD CONSTRAINT fkey_a05_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_06
  ADD CONSTRAINT fkey_a06_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_07
  ADD CONSTRAINT fkey_a07_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_08
  ADD CONSTRAINT fkey_a08_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_09
  ADD CONSTRAINT fkey_a09_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_10
  ADD CONSTRAINT fkey_a10_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_11
  ADD CONSTRAINT fkey_a11_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_12
  ADD CONSTRAINT fkey_a12_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_13
  ADD CONSTRAINT fkey_a13_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_14
  ADD CONSTRAINT fkey_a14_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_15
  ADD CONSTRAINT fkey_a15_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_16
  ADD CONSTRAINT fkey_a16_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_17
  ADD CONSTRAINT fkey_a17_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
	  ALTER TABLE aplicacion_fondos.a_18
  ADD CONSTRAINT fkey_a18_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_19
  ADD CONSTRAINT fkey_a19_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_20
  ADD CONSTRAINT fkey_a20_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_21
  ADD CONSTRAINT fkey_a21_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_22
  ADD CONSTRAINT fkey_a22_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_23
  ADD CONSTRAINT fkey_a23_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
      ALTER TABLE aplicacion_fondos.a_24
  ADD CONSTRAINT fkey_a24_efector FOREIGN KEY (efector)
      REFERENCES efectores.efectores (cuie) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
      
";


?>
