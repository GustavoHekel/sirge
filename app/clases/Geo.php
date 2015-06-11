<?php

class Geo {

	private $_db;

	public function __construct() {
		$this->_db = Bdd::getInstance();
	}

	public function dashboardMap() {
		$sql  = "select geojson_provincia as \"hc-key\", habitantes as value from geo.geojson j left join geo.poblacion p on j.id_provincia = p.id_provincia";
		$data = $this->_db->query($sql)->getResults();
		echo json_encode($data);
	}

	public function selectProvincias($id_provincia) {
		$sql    = "select * from sistema.provincias";
		$data   = $this->_db->query($sql)->getResults();
		$select = '<option value="0">Seleccione una entidad</option>';
		foreach ($data as $index => $valor) {
			$select .= '<option value="' . $valor['id_provincia'] . '" ';
			$select .= $id_provincia != '25' ? ($id_provincia == $valor['id_provincia'] ? 'selected="selected"' : 'disabled="disabled"') : '';
			$select .= '>' . mb_convert_case($valor['descripcion'], MB_CASE_TITLE, 'UTF-8') . '</option>';
		}
		$select .= '</select>';
		return $select;
	}

	public function selectDepartamentos($id_provincia) {
		$sql    = "select * from geo.departamentos where id_provincia = ?";
		$data   = $this->_db->query($sql, [$id_provincia])->getResults();
		$select = '<option value="0">Seleccione un departamento</option>';
		foreach ($data as $key => $value) {
			$select .= "<option value='{$value['id_departamento']}'>{$value['nombre_departamento']}</option>";
		}
		echo $select;
	}

	public function selectLocalidad($id_provincia, $id_departamento) {
		$sql    = "select * from geo.localidades where id_provincia = ? and id_departamento = ?";
		$data   = $this->_db->query($sql, [$id_provincia, $id_departamento])->getResults();
		$select = '';
		foreach ($data as $key => $value) {
			$select .= "<option value='{$value['id_localidad']}'>{$value['nombre_localidad']}</option>";
		}
		echo $select;
	}

	public function getGraficoProvincia($id_provincia) {

		/*	$sql = "
		select
		 *
		, 255 - (distribucion * 255 / (
		select
		max (distribucion) as distribucion
		from
		geo.departamentos d left join (
		select
		 *
		, coalesce (round (100 * cantidad / sum (cantidad) over () , 2) , 0) as distribucion
		from (
		select
		a.id_departamento
		, a.nombre_departamento
		, sum (cantidad) as cantidad
		from (
		select
		e.cuie
		, dto.id_departamento
		, dto.nombre_departamento
		from
		efectores.efectores e left join
		efectores.datos_geograficos d on e.id_efector = d.id_efector left join
		efectores.departamentos dto on d.id_provincia || d.id_departamento = dto.id_provincia || dto.id_departamento
		where d.id_provincia = '" . $id_provincia . "'
		) a left join (
		select efector , count (*) as cantidad
		from prestaciones.p_" . $id_provincia . "
		--where codigo_prestacion = 'IMV013A98'
		group by 1
		order by 1
		) p on a.cuie = p.efector
		group by 1,2
		order by 1,2 ) b )
		a on d.id_departamento = a.id_departamento
		where id_provincia = '" . $id_provincia . "')) :: int as rgb
		from (
		select
		latitud :: text || ',' || longitud :: text as ll
		, d.id_departamento
		, nombre_departamento
		, coalesce (cantidad,0) as cantidad
		, coalesce (a.distribucion,0) as distribucion
		, coalesce (round (255 * (1 - (distribucion / 100)) , 0),0) as r
		from
		geo.departamentos d left join (
		select
		 *
		, coalesce (round (100 * cantidad / sum (cantidad) over () , 2) , 0) as distribucion
		from (
		select
		a.id_departamento
		, a.nombre_departamento
		, sum (cantidad) as cantidad
		from (
		select
		e.cuie
		, dto.id_departamento
		, dto.nombre_departamento
		from
		efectores.efectores e left join
		efectores.datos_geograficos d on e.id_efector = d.id_efector left join
		efectores.departamentos dto on d.id_provincia || d.id_departamento = dto.id_provincia || dto.id_departamento
		where d.id_provincia = '" . $id_provincia . "'
		) a left join (
		select efector , count (*) as cantidad
		from prestaciones.p_" . $id_provincia . "
		--where codigo_prestacion = 'IMV013A98'
		group by 1
		order by 1
		) p on a.cuie = p.efector
		group by 1,2
		order by 1,2 ) b )
		a on d.id_departamento = a.id_departamento
		where id_provincia = '" . $id_provincia . "'
		order by id_punto
		) a";*/

		$sql = " SELECT id_dto, cantidad, (gep.latitud::text || ',' || gep.longitud::text) as ll, dto.nombre_departamento, round (100 * cantidad / (select count(*)
                                                      FROM efectores.efectores e
                                                      INNER JOIN efectores.datos_geograficos d ON e.id_efector = d.id_efector
                                                      INNER JOIN prestaciones.p_$id_provincia p ON e.cuie = p.efector
                                                      WHERE id_provincia = '$id_provincia') :: numeric, 2) as distribucion
                FROM
                (
                  SELECT d.id_departamento as id_dto, count(e.cuie) as cantidad
                    FROM efectores.efectores e
                    INNER JOIN efectores.datos_geograficos d ON e.id_efector = d.id_efector
                    INNER JOIN prestaciones.p_$id_provincia p ON e.cuie = p.efector
                    WHERE id_provincia = '$id_provincia'
                    GROUP BY 1
                ) as prestaciones_por_dto
                INNER JOIN
                  geo.departamentos dto ON dto.id_departamento = id_dto
                INNER JOIN
                  geo.gep_departamentos gep ON gep.id_provincia = dto.id_provincia AND dto.id_departamento = gep.id_departamento
                WHERE
                  dto.id_provincia = '$id_provincia'
                ORDER BY id_dto, id_punto ";

		return $this->_db->query($sql)->getResults();

	}
}