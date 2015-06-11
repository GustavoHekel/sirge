<?php

class Efector {
	
	private $_db;
	
	public function __construct(){
		$this->_db = Bdd::getInstance();
	}
	
	public function getEfectorAsignado ($id){
		$sql = "
			select
				e.nombre
			from
				mobile.usuarios u left join
				beneficiarios.beneficiarios b on u.sexo || u.tipo_documento || u.numero_documento = b.sexo || b.tipo_documento || b.numero_documento left join
				(
					select
						clave_beneficiario
						, efector_asignado
					from
						beneficiarios.beneficiarios_periodos
					where
						periodo = (select max (periodo) from beneficiarios.beneficiarios_periodos)
				) p on b.clave_beneficiario = p.clave_beneficiario left join
				efectores.efectores e on p.efector_asignado = e.cuie
			where
				u.id_usuario = ?";
		$efector = $this->_db->query($sql , [$id])->get()['nombre'];
		
		return strlen ($efector) ? $efector : '<b>Ud. no posee un efector asignado</b>';
	}
	
	public function getListadoCercano ($lat , $lon){
		$sql = "
			select
				nombre
				, round (earth_distance(ll_to_earth({$lat},{$lon}), ll_to_earth(g.latitud, g.longitud)) :: numeric / 1000 , 2) as distancia
				, latitud 
				, longitud
			from
				efectores.efectores e left join
				efectores.datos_geograficos g on e.id_efector = g.id_efector
			where
				earth_distance(ll_to_earth({$lat},{$lon}), ll_to_earth(g.latitud, g.longitud)) / 1000 <= 20
			order by 2";
			
		$data = $this->_db->query($sql)->getResults();
		
		$li = '';
		
		foreach ($data as $index => $info){
			$li .= "
				<li class='ui-li-has-count'>
					<a href='mapa.html?lat={$info['latitud']}&long={$info['longitud']}' class='ui-btn ui-btn-icon-right ui-icon-carat-r'>{$info['nombre']}
						<span class='ui-li-count ui-body-b'>{$info['distancia']} Km.</span>
					</a>
				</li>";
		}
		return $li;
	}
	
}
