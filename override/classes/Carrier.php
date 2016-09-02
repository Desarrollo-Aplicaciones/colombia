<?php

class Carrier extends CarrierCore
{
	public static function getCarrierList(){
		$query="select id_reference,`name` 
				from ps_carrier 
				where shipping_handling = 0
				GROUP BY id_reference;";
		return Db::getInstance()->executeS($query);
	}

	
	}
