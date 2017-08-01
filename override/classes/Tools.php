<?php
class Tools extends ToolsCore {

	/**
	* Permite validar que no existan mas de dos clientes con un mismo documento de identidad.
	**/
	public static function validateIdentification($id_customer) {
                $sqlCustomer = 'SELECT id_customer FROM '._DB_PREFIX_.'customer WHERE id_customer = %d HAVING COUNT(id_customer) = 1';
                $total_customer = Db::getInstance()->getValue(sprintf($sqlCustomer, $id_customer));
                if(count($total_customer)>0){
                    $id_customer = $total_customer;
                }
                
		$sql = 'SELECT identification, id_customer FROM '._DB_PREFIX_.'customer WHERE identification = %d HAVING COUNT(identification) = 1';
		if ($total_customer = Db::getInstance()->executeS(sprintf($sql, $_POST['identification'])))
		{
                    if($total_customer[0]['id_customer'] != $id_customer) {
                        return false;
                    } else {
                        return true;
                    }
		} else {
			return true;
		}
	}
}