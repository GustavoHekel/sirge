<?php

class Geo {
    
    private $_db;
  
    public function __construct() {
      $this->_db = Bdd::getInstance();
    }
  
	public function dashboardMap () {
		$sql = "select geojson_provincia as \"hc-key\", habitantes as value from geo.geojson j left join geo.poblacion p on j.id_provincia = p.id_provincia";
		$data = $this->_db->query($sql)->getResults();
		echo json_encode ($data);
	}
    
    public function selectProvincias ($id_provincia){
      $sql 	= "select * from sistema.provincias";
      $data = $this->_db->query($sql)->getResults();
      $select = '<option value="0">Seleccione una entidad</option>';
      foreach ($data as $index => $valor) {
          $select .= '<option value="' . $valor['id_provincia'] . '" ';
          $select .= $id_provincia != '25' ? ($id_provincia == $valor['id_provincia'] ? 'selected="selected"' : 'disabled="disabled"') : '';
          $select .= '>' . mb_convert_case ($valor['descripcion'] , MB_CASE_TITLE , 'UTF-8') . '</option>';
      }
      $select .= '</select>';
      return $select;
    }
    
    public function selectDepartamentos($id_provincia){
      $sql = "select * from geo.departamentos where id_provincia = ?";
      $data = $this->_db->query($sql , [$id_provincia])->getResults();
      $select = '<option value="0">Seleccione un departamento</option>';
      foreach ($data as $key => $value){
        $select .= "<option value='{$value['id_departamento']}'>{$value['nombre_departamento']}</option>";
      }
      echo $select;
    }
    
    public function selectLocalidad($id_provincia , $id_departamento){
      $sql = "select * from geo.localidades where id_provincia = ? and id_departamento = ?";
      $data = $this->_db->query($sql , [$id_provincia , $id_departamento])->getResults();
      $select = '';
      foreach ($data as $key => $value){
        $select .= "<option value='{$value['id_localidad']}'>{$value['nombre_localidad']}</option>";
      }
      echo $select;
    }
    
    

}
