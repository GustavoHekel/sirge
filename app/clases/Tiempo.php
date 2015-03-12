<?php

class Tiempo 
{
	
	public static function MaxPeriodoSSS () {
		$dt = new DateTime('-3 months');
		return $dt->format('Ym');
	}
	
}

?>
