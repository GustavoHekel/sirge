<?php

class Geo {

	public function DashboardMap () {
		
		$sql = "
			select
				geojson_provincia as \"hc-key\"
				, habitantes as value
			from
				geo.geojson j left join
				geo.poblacion p on j.id_provincia = p.id_provincia";
		$data = BDD::GetInstance()->Query($sql)->GetResults();
		
		echo json_encode ($data);
		
	}

}

?>
