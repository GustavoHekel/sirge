<?php
require 'conexion.php';

$sql = "
/*
 *
 * CREAMOS LA TABLA
 *
 */

	CREATE TABLE prestaciones.inconsistencias
	(
	  estado character(1) NOT NULL,
	  efector character varying(14) NOT NULL,
	  numero_comprobante character varying(50) NOT NULL,
	  tipo_comprobante character(2) NOT NULL,
	  codigo_prestacion character varying(11) NOT NULL,
	  subcodigo_prestacion character(3) NOT NULL,
	  precio_unitario numeric NOT NULL,
	  fecha_prestacion date NOT NULL,
	  clave_beneficiario character varying(16) NOT NULL,
	  tipo_documento character varying(3),
	  clase_documento character(1),
	  numero_documento character varying(14),
	  orden smallint NOT NULL,
	  lote integer,
	  tipo_inconsistencia integer
	)
	WITH (
	  OIDS=FALSE
	);
	ALTER TABLE prestaciones.inconsistencias
	  OWNER TO postgres;

/*
 *
 * COPIAMOS LOS REGISTROS CUYO CUIE ES FRUTA
 *
 */
	  
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_01 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_02 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_03 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_04 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_05 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_06 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_07 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_08 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_09 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_10 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_11 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_12 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_13 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_14 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_15 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_16 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_17 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_18 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_19 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_20 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_21 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_22 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_23 where efector not in (select cuie from efectores.efectores);
	insert into prestaciones.inconsistencias select * , '1' from prestaciones.p_24 where efector not in (select cuie from efectores.efectores);

/*
 *
 * ELIMINAMOS LOS REGISTROS CUYO CUIE ES FRUTA
 *
 */

	delete from prestaciones.p_01 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_02 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_03 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_04 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_05 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_06 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_07 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_08 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_09 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_10 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_11 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_12 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_13 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_14 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_15 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_16 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_17 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_18 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_19 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_20 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_21 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_22 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_23 where efector not in (select cuie from efectores.efectores);
	delete from prestaciones.p_24 where efector not in (select cuie from efectores.efectores);

/*
 *
 * COPIAMOS LOS REGISTROS CUYA CLAVE DE BENEFICIARIO NO ESTA EN NINGUN LADO
 *
 */
	
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_01 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_02 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_03 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_04 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_05 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_06 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_07 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_08 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_09 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_10 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_11 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_12 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_13 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_14 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_15 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_16 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_17 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_18 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_19 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_20 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_21 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_22 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_23 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;
	insert into prestaciones.inconsistencias select p.* , '2' from prestaciones.p_24 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null;

/*
 *
 * ELIMINAMOS LOS REGISTROS CUYA CLAVE DE BENEFICIARIO ES FRUTA
 *
 */
	
	delete from prestaciones.p_01 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_01 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_02 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_02 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_03 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_03 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_04 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_04 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_05 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_05 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_06 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_06 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_07 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_07 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_08 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_08 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_09 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_09 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_10 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_10 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_11 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_11 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_12 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_12 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_13 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_13 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_14 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_14 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_15 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_15 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_16 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_16 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_17 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_17 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_18 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_18 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_19 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_19 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_20 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_20 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_21 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_21 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_22 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_22 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_23 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_23 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	delete from prestaciones.p_24 where clave_beneficiario in (select p.clave_beneficiario from prestaciones.p_24 p left join beneficiarios.beneficiarios b on p.clave_beneficiario = b.clave_beneficiario where b.clave_beneficiario is null);
	
/*
 *
 * COPIAMOS LOS REGISTROS CUYO CODIGO DE PRESTACION NO EXISTE
 *
 */
	
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_01 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_02 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_03 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_04 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_05 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_06 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_07 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_08 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_09 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_10 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_11 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_12 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_13 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_14 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_15 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_16 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_17 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_18 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_19 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_20 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_21 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_22 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_23 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;
	insert into prestaciones.inconsistencias select p.* , '3' from prestaciones.p_24 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null;

/*
 *
 * ELIMINAMOS LOS REGISTROS CUYO CODIGO DE PRESTACION NO EXISTE
 *
 */	
		
	delete from prestaciones.p_01 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_01 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_02 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_02 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_03 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_03 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_04 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_04 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_05 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_05 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_06 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_06 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_07 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_07 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_08 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_08 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_09 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_09 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_10 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_10 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_11 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_11 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_12 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_12 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_13 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_13 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_14 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_14 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_15 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_15 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_16 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_16 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_17 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_17 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_18 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_18 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_19 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_19 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_20 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_20 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_21 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_21 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_22 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_22 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_23 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_23 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);
	delete from prestaciones.p_24 where codigo_prestacion in (select p.codigo_prestacion from prestaciones.p_24 p left join pss.codigos c on p.codigo_prestacion = c.codigo_prestacion where c.codigo_prestacion is null);

/*
 *
 * ELIMINAMOS CLAVES FORANEAS VIEJAS
 *
 */
	 
	ALTER TABLE prestaciones.p_01 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_02 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_03 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_04 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_05 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_06 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_07 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_08 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_09 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_10 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_11 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_12 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_13 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_14 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_15 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_16 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_17 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_18 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_19 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_20 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_21 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_22 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_23 DROP CONSTRAINT fkey_codigo_prestacion;
	ALTER TABLE prestaciones.p_24 DROP CONSTRAINT fkey_codigo_prestacion;
	
	
	ALTER TABLE prestaciones.p_01 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_02 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_04 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_05 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_08 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_09 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_12 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_14 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_16 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_17 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_18 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_19 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_21 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_22 DROP CONSTRAINT \"fkey_cuie -> efectores\";
	ALTER TABLE prestaciones.p_24 DROP CONSTRAINT \"fkey_cuie -> efectores\";

/*
 *
 * AGREGO LAS CLAVES FORANEAS NUEVAS
 *
 */

	ALTER TABLE prestaciones.p_01 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_02 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_03 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_04 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_05 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_06 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_07 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_08 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_09 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_10 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_11 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_12 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_13 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_14 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_15 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_16 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_17 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_18 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_19 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_20 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_21 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_22 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_23 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_24 ADD CONSTRAINT fkey_codigo_prestacion FOREIGN KEY (codigo_prestacion) REFERENCES pss.codigos (codigo_prestacion) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	
	
	ALTER TABLE prestaciones.p_01 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_02 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_03 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_04 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_05 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_06 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_07 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_08 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_09 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_10 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_11 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_12 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_13 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_14 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_15 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_16 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_17 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_18 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_19 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_20 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_21 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_22 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_23 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_24 ADD CONSTRAINT fkey_efector FOREIGN KEY (efector) REFERENCES efectores.efectores (cuie) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	
	
	ALTER TABLE prestaciones.p_01 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_02 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_03 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_04 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_05 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_06 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_07 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_08 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_09 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_10 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_11 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_12 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_13 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_14 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_15 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_16 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_17 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_18 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_19 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_20 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_21 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_22 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_23 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;
	ALTER TABLE prestaciones.p_24 ADD CONSTRAINT fkey_clave_beneficiario FOREIGN KEY (clave_beneficiario) REFERENCES beneficiarios.beneficiarios (clave_beneficiario) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;

	

";
