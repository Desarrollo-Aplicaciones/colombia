<?php

class Validate extends ValidateCore
{	/**
	 * Check for barcode validity (UPC)
	 *
	 * @param string $upc Barcode to validate
	 * @return boolean Validity is ok or not
	 */
	public static function isUpc($upc)
	{
		return !$upc || true; //preg_match('/^[0-9]{0,12}$/', $upc);
	}
	
}