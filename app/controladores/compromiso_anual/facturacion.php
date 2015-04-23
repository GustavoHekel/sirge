<?php
session_start();
$nivel = 2;
require '../../seguridad.php';
require $ruta . 'sistema/conectar_postgresql.php';

if (isset($_POST['id_provincia'])) {
	$sql = "
	select
		p.id_provincia
		, case
			when p.nombre = 'Ciudad Aut&oacute;noma de Buenos Aires' then 'CABA'
			else p.nombre
		end as nombre
		, c.primer_semestre
		, c.segundo_semestre
		, prestaciones_desc
		, prestaciones_fact
		, round (prestaciones_desc :: numeric / prestaciones_fact * 100 , 2) as fact_desc
	from (
		select
			provincia
			, count (*) as prestaciones_desc
		from (
			select '01' as provincia , * from prestaciones.p_01 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '02' as provincia , * from prestaciones.p_02 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '03' as provincia , * from prestaciones.p_03 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '04' as provincia , * from prestaciones.p_04 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '05' as provincia , * from prestaciones.p_05 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '06' as provincia , * from prestaciones.p_06 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '07' as provincia , * from prestaciones.p_07 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '08' as provincia , * from prestaciones.p_08 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '09' as provincia , * from prestaciones.p_09 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '10' as provincia , * from prestaciones.p_10 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '11' as provincia , * from prestaciones.p_11 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '12' as provincia , * from prestaciones.p_12 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '13' as provincia , * from prestaciones.p_13 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '14' as provincia , * from prestaciones.p_14 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '15' as provincia , * from prestaciones.p_15 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '16' as provincia , * from prestaciones.p_16 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '17' as provincia , * from prestaciones.p_17 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '18' as provincia , * from prestaciones.p_18 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '19' as provincia , * from prestaciones.p_19 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '20' as provincia , * from prestaciones.p_20 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '21' as provincia , * from prestaciones.p_21 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '22' as provincia , * from prestaciones.p_22 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '23' as provincia , * from prestaciones.p_23 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '24' as provincia , * from prestaciones.p_24 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 ) p
		where
			fecha_prestacion >= '2014-01-01'
			and efector in (
				select cuie
				from
					efectores.efectores e left join
					efectores.descentralizacion d on e.id_efector = d.id_efector
				where
					factura_descentralizada = 'S'
					and integrante = 'S'
					and compromiso_gestion = 'S'
			)
		group by provincia
	) a left join (
		select
			provincia
			, count (*) as prestaciones_fact
		from (
			select '01' as provincia , * from prestaciones.p_01 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '02' as provincia , * from prestaciones.p_02 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '03' as provincia , * from prestaciones.p_03 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '04' as provincia , * from prestaciones.p_04 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '05' as provincia , * from prestaciones.p_05 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '06' as provincia , * from prestaciones.p_06 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '07' as provincia , * from prestaciones.p_07 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '08' as provincia , * from prestaciones.p_08 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '09' as provincia , * from prestaciones.p_09 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '10' as provincia , * from prestaciones.p_10 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '11' as provincia , * from prestaciones.p_11 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '12' as provincia , * from prestaciones.p_12 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '13' as provincia , * from prestaciones.p_13 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '14' as provincia , * from prestaciones.p_14 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '15' as provincia , * from prestaciones.p_15 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '16' as provincia , * from prestaciones.p_16 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '17' as provincia , * from prestaciones.p_17 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '18' as provincia , * from prestaciones.p_18 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '19' as provincia , * from prestaciones.p_19 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '20' as provincia , * from prestaciones.p_20 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '21' as provincia , * from prestaciones.p_21 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '22' as provincia , * from prestaciones.p_22 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '23' as provincia , * from prestaciones.p_23 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 union all
			select '24' as provincia , * from prestaciones.p_24 p left join sistema.lotes l on p.lote = l.lote where id_estado = 1 ) p
		where
			fecha_prestacion >= '2014-01-01'
		group by provincia
	) b on a.provincia = b.provincia right join
	sistema.provincias p on a.provincia = p.id_provincia left join
	compromiso_anual_2014.metas_facturacion c on p.id_provincia = c.id_provincia";
	$sql .= $_SESSION['grupo'] == 25 ? '' : " where p.id_provincia = '" . $_SESSION['grupo'] . "'";
	$sql .= " order by p.id_provincia";
	$res = pg_query($sql);
	if (pg_num_rows($res)) {
		$i = 0;
		while ($reg = pg_fetch_assoc($res)) {
			for ($j = 0; $j < pg_num_fields($res); $j++) {
				$data[pg_field_name($res, $j)][$i] = html_entity_decode($reg[pg_field_name($res, $j)]);
			}
			$i++;
		}
	}
	die(json_encode($data, JSON_NUMERIC_CHECK));
}
?>
