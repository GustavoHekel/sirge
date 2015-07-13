<?php

class Geo {

	private $_db;

	public function __construct()
	{
		$this->_db = Bdd::getInstance();
	}

	public function dashboardMap()
	{
		$sql = "select geojson_provincia as \"hc-key\", p.id_provincia, habitantes as value-- || '</br>' || 'Inscriptos SUMAR: ' ||
              ,(select sum(i.habitantes_sumar)
                                                                                                  FROM indec.poblacion_departamentos i
                                                                                                  WHERE i.id_provincia = p.id_provincia) as habitantes_sumar
              from geo.geojson j
              left join indec.poblacion p on j.id_provincia = p.id_provincia";
		$data = $this->_db->query($sql)->getResults();
		echo json_encode($data);
	}

	public function selectProvincias($id_provincia)
	{
		$sql    = "select * from sistema.provincias";
		$data   = $this->_db->query($sql)->getResults();
		$select = '<option value="0">Seleccione una entidad</option>';
		foreach ($data as $index => $valor)
		{
			$select .= '<option value="'.$valor['id_provincia'].'" ';
			$select .= $id_provincia != '25' ? ($id_provincia == $valor['id_provincia'] ? '' : 'disabled="disabled"') : '';
			$select .= '>'.mb_convert_case($valor['descripcion'], MB_CASE_TITLE, 'UTF-8').'</option>';
		}
		$select .= '</select>';
		return $select;
	}

	public function selectDepartamentos($id_provincia)
	{
		$sql    = "select * from geo.departamentos where id_provincia                = ?";
		$data   = $this->_db->query($sql, [$id_provincia])->getResults();
		$select = '<option value="">Seleccione un departamento</option>';
		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_departamento']}'>{$value['nombre_departamento']}</option>";
		}
		echo $select;
	}

	public function selectLocalidad($id_provincia, $id_departamento)
	{
		$sql    = "select * from geo.localidades where id_provincia                = ? and id_departamento                = ?";
		$data   = $this->_db->query($sql, [$id_provincia, $id_departamento])->getResults();
		$select = '<option value="">Seleccione una localidad</option>';
		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_localidad']}'>{$value['nombre_localidad']}</option>";
		}
		echo $select;
	}

	public function getGraficoProvincia($id_provincia)
	{

		$sql = " SELECT id_dto, cantidad, habitantes, habitantes_sumar, (gep.latitud::text || ',' || gep.longitud::text) as ll, dto.nombre_departamento, round (100 * cantidad / (select count(*)
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
		INNER JOIN
		indec.poblacion_departamentos indec ON gep.id_provincia = indec.id_provincia AND indec.id_departamento = gep.id_departamento
		WHERE
		dto.id_provincia = '$id_provincia'
		ORDER BY id_dto, id_punto ";

		/*$sql = " SELECT dto.id_departamento as id_dto, (gep.latitud::text || ',' || gep.longitud::text) as ll, dto.nombre_departamento, 1 as cantidad, 1 as distribucion, 250 as rgb
		FROM
		geo.departamentos dto
		INNER JOIN
		geo.gep_departamentos gep ON gep.id_provincia = dto.id_provincia AND dto.id_departamento = gep.id_departamento
		WHERE
		dto.id_provincia = '01'
		ORDER BY id_dto, id_punto ";*/

		return $this->_db->query($sql)->getResults();
		//var_dump($this->_db->query($sql)->getResults());

	}

	public function getPosicionProvincia($grupo)
	{
		return $this->_db->fquery('getPosicionProvincia', [$grupo], false)->getResults();
	}
}