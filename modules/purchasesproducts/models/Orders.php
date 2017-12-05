<?php

class Orders {

	/*
	* Se obtiene la información de todas las ordenes y sus productos faltantes por compra.
	*/
	public function getOrdersToProducts() {
		$ordersSql = 'SELECT  o.id_order AS id, od.product_name AS producto, p.reference AS ean,
	        m.`name` AS laboratorio, date_format (o.delivery_date, "%Y-%m-%d") AS fecha, 
	        a.city AS ciudad, od.product_quantity AS solicitados, rp.quantity_reserve AS warehouse_quantity, 
                rp.missing_quantity AS faltantes 
	        FROM ' . _DB_PREFIX_ . 'orders AS  o 
	        INNER JOIN ' . _DB_PREFIX_ . 'address AS a ON ( o.id_address_delivery = a.id_address )
	        INNER JOIN ' . _DB_PREFIX_ . 'order_detail AS od  ON ( o.id_order = od.id_order)
	        INNER JOIN ' . _DB_PREFIX_ . 'product AS p ON ( p.id_product = od.product_id)
	        INNER JOIN ' . _DB_PREFIX_ . 'manufacturer AS m ON ( m.id_manufacturer = p.id_manufacturer)
	        INNER JOIN ' . _DB_PREFIX_ . 'stock_available_mv AS sam ON ( sam.id_product = p.id_product )
                INNER JOIN ' . _DB_PREFIX_ . 'reserve_product AS rp ON (rp.id_order = o.id_order AND rp.id_product = p.id_product)
	        WHERE  current_state = 9 AND rp.missing_quantity > 0
	        ORDER BY o.id_order ASC';

        return $orders = Db::getInstance()->ExecuteS($ordersSql);
	}

	/**
	* Se obtienen los productos para realizar la compra.
	*/
	public function getOrdersToBuy() {
		$ordersSql = 'SELECT  p.reference AS referencia, od.product_name AS producto, 
			SUM(rp.missing_quantity) AS unitToBuy,
                        (SELECT SUM(quantity_expected)
                        FROM ps_supply_order_detail s
                        INNER JOIN ps_supply_order so ON so.id_supply_order = s.id_supply_order
                        WHERE so.id_supply_order_state = 4 AND s.reference = p.reference
                        GROUP BY s.reference) AS unitExpected,
                        (SELECT SUM(quantity_received)
                        FROM ps_supply_order_detail s
                        INNER JOIN ps_supply_order so ON so.id_supply_order = s.id_supply_order
                        WHERE so.id_supply_order_state = 4 AND s.reference = p.reference
                        GROUP BY s.reference) AS unitReceived,
                        (SELECT CONCAT("$",FORMAT(MIN(ps.product_supplier_price_te), 0))
                        FROM ps_product_supplier ps
                        INNER JOIN ps_supplier s ON (s.id_supplier = ps.id_supplier)
                        LEFT JOIN ps_inventarios_proveedor ip ON (ip.id_proveedor = s.id_supplier)
                        WHERE ps.id_product = p.id_product AND  ip.ean = p.reference AND s.active = 1) AS unitPrice,
                        (SELECT s.name
                        FROM ps_product_supplier ps
                        INNER JOIN ps_supplier s ON (s.id_supplier = ps.id_supplier)
                        LEFT JOIN ps_inventarios_proveedor ip ON (ip.id_proveedor = s.id_supplier)
                        WHERE ps.id_product = p.id_product AND  ip.ean = p.reference AND s.active = 1 
                        ORDER BY product_supplier_price_te ASC LIMIT 0,1) AS supplier
	        FROM ' . _DB_PREFIX_ . 'orders AS  o 
	        INNER JOIN ' . _DB_PREFIX_ . 'address AS a ON ( o.id_address_delivery = a.id_address )
	        INNER JOIN ' . _DB_PREFIX_ . 'order_detail AS od  ON ( o.id_order = od.id_order)
	        INNER JOIN ' . _DB_PREFIX_ . 'product AS p ON ( p.id_product = od.product_id)
	        INNER JOIN ' . _DB_PREFIX_ . 'manufacturer AS m ON ( m.id_manufacturer = p.id_manufacturer)
	        INNER JOIN ' . _DB_PREFIX_ . 'stock_available_mv AS sam ON ( sam.id_product = p.id_product )
                INNER JOIN ' . _DB_PREFIX_ . 'reserve_product AS rp ON (rp.id_order = o.id_order AND rp.id_product = p.id_product)
	        WHERE  current_state = 9 AND rp.missing_quantity > 0
	        GROUP BY p.reference
			ORDER BY od.product_name ASC';

        return $orders = Db::getInstance()->ExecuteS($ordersSql);
	}

	/**
	* Se obtienen los productos para realizar la compra dependiendo del proveedor seleccionado.
	*/
	public function getOrdersToBuyToProvider($id_proveedor) {
		$ordersSql = 'SELECT  p.reference AS referencia, od.product_name AS producto, 
                SUM(rp.missing_quantity) AS unitToBuy, 
                CONCAT("$", FORMAT(IF(ps.product_supplier_price_te != "null", ps.product_supplier_price_te, 0), 0)) AS valor, 
                CONCAT("$",FORMAT(
                (SELECT MIN(pod.product_price) 
                FROM ps_order_detail AS pod 
                INNER JOIN ps_orders AS o ON (o.id_order = pod.id_order)
                WHERE pod.product_id = p.id_product AND o.current_state IN (3, 9, 20))
                ,0)) AS valor_venta, 
                ip.unidades_proveedor AS unitsProvider, 
                CONCAT("<input type=\'text\' name=\'quantity_", p.reference,"\' id=\'quantity_", p.reference,"\' />") AS quantityBuy,
                ip.codigo_proveedor AS codigo_proveedor
	        FROM ' . _DB_PREFIX_ . 'orders AS  o 
	        INNER JOIN ' . _DB_PREFIX_ . 'address AS a ON ( o.id_address_delivery = a.id_address )
	        INNER JOIN ' . _DB_PREFIX_ . 'order_detail AS od  ON ( o.id_order = od.id_order)
	        INNER JOIN ' . _DB_PREFIX_ . 'product AS p ON ( p.id_product = od.product_id)
	        INNER JOIN ' . _DB_PREFIX_ . 'manufacturer AS m ON ( m.id_manufacturer = p.id_manufacturer)
	        INNER JOIN ' . _DB_PREFIX_ . 'stock_available_mv AS sam ON ( sam.id_product = p.id_product )
	        INNER JOIN ' . _DB_PREFIX_ . 'inventarios_proveedor AS ip ON (ip.ean = p.reference)
                INNER JOIN ' . _DB_PREFIX_ . 'reserve_product AS rp ON (rp.id_order = o.id_order AND rp.id_product = p.id_product)
                LEFT JOIN ps_product_supplier AS ps ON (ps.id_supplier = ip.id_proveedor AND ps.id_product = p.id_product)
	        WHERE  current_state = 9 AND rp.missing_quantity > 0
	        AND ip.id_proveedor = '.$id_proveedor.'
	        GROUP BY p.reference
			ORDER BY od.product_name ASC';

        return $orders = Db::getInstance()->ExecuteS($ordersSql);
	}

	/**
	* Se obtiene el inventario de los proveedores.
	*/
	public function getInventory() {
		$inventorySql = 'SELECT  *
	        FROM ' . _DB_PREFIX_ . 'inventarios_proveedor';

        return $orders = Db::getInstance()->ExecuteS($inventorySql);
	}

	/*
	* Se realiza insert del inventario de proveedores
	*/

	public function insertInventory($data) {
		$inventorySql = 'INSERT INTO ' . _DB_PREFIX_ . 'inventarios_proveedor (ean, codigo_proveedor, descripcion, valor_proveedor, unidades_proveedor, proveedor, id_proveedor)
						VALUES("'.$data[0].'","'.$data[1].'","'.$data[2].'","'.$data[3].'","'.$data[4].'",
						"'.$data[5].'","'.$data[6].'")';

        if(Db::getInstance()->Execute($inventorySql)) {
        	return true;
        } else {
        	return false;
        }
	}

	/*
	* Se el vaceado de la tabla para volver a cargar el inventario
	*/

	public function truncateInventory() {
		$inventorySql = 'TRUNCATE ' . _DB_PREFIX_ . 'inventarios_proveedor';

        if(Db::getInstance()->Execute($inventorySql)) {
        	return true;
        } else {
        	return false;
        }
	}

	public function getNameProvider() {
            $nameProvider = 'SELECT  DISTINCT proveedor, id_proveedor FROM ' . _DB_PREFIX_ . 'inventarios_proveedor';

            return $proveedor = Db::getInstance()->ExecuteS($nameProvider);
	}
}