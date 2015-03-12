<?php

class Geo {

	public function dashboardMap () {
		$sql = "select geojson_provincia as \"hc-key\", habitantes as value from geo.geojson j left join geo.poblacion p on j.id_provincia = p.id_provincia";
		$data = Bdd::getInstance()->query($sql)->getResults();
		echo json_encode ($data);
	}

}

?>
