<?php
class Tools extends ToolsCore {

	/**
	* Permite validar que no existan mas de dos clientes con un mismo documento de identidad.
	**/
	public static function validateIdentification() {
		$sql = 'SELECT identification FROM '._DB_PREFIX_.'customer WHERE identification = %d HAVING COUNT(identification) = 1';
		if ($total_customer = Db::getInstance()->getValue(sprintf($sql, $_POST['identification'])))
		{
			return false;
		} else {
			return true;
		}
	}
}