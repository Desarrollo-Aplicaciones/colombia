<?php

class AdminHomeController extends AdminHomeControllerCore
{
            
	public function getMonthlyStatistics()
	{
                $valid_state_sale = Configuration::get('ESTADOS_VALIDOS_VENTA_REPORTE'); 
            
		$currency = Tools::setCurrency($this->context->cookie);
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT IFNULL(SUM(`total_paid_real` / conversion_rate), "0") as total_sales, COUNT(*) as total_orders
			FROM `'._DB_PREFIX_.'orders`
			WHERE valid = 1
				AND `invoice_date` BETWEEN \''.date('Y-m').'-01 00:00:00\' AND \''.date('Y-m').'-31 23:59:59\'
				'.Shop::addSqlRestriction(Shop::SHARE_ORDER).' AND o.current_state IN ('.$valid_state_sale.')
		');

		$result2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT COUNT(`id_customer`) AS total_registrations
			FROM `'._DB_PREFIX_.'customer` c
			WHERE c.`date_add` BETWEEN \''.date('Y-m').'-01 00:00:00\' AND \''.date('Y-m').'-31 23:59:59\'
				'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER).'
		');

		$result3 = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT SUM(pv.`counter`) AS total_viewed
			FROM `'._DB_PREFIX_.'page_viewed` pv
			LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range`
			LEFT JOIN `'._DB_PREFIX_.'page` p ON pv.`id_page` = p.`id_page`
			LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = p.`id_page_type`
			WHERE pt.`name` = \'product\'
				AND dr.`time_start` BETWEEN \''.date('Y-m').'-01 00:00:00\' AND \''.date('Y-m').'-31 23:59:59\'
				AND dr.`time_end` BETWEEN \''.date('Y-m').'-01 00:00:00\' AND \''.date('Y-m').'-31 23:59:59\'
				'.Shop::addSqlRestriction().'
		');

		$results = array_merge($result, array_merge($result2, $result3));

		$content = '<div class="table_info">
			<h5><a href="index.php?tab=AdminStats&token='.Tools::getAdminTokenLite('AdminStats').'">'.$this->l('View more').'</a> '.$this->l('This month\'s activity').' </h5>
			<table class="table_info_details" style="width:100%;">
					<colgroup>
						<col width="">
						<col width="80px">
					</colgroup>
				<tr class="tr_odd">
					<td class="td_align_left">
					'.$this->l('Sales').'
					</td>
					<td>
						'
						.Tools::displayPrice($results['total_sales'], $currency)
						.'
					</td>
				</tr>
				<tr>
					<td class="td_align_left">
						'.$this->l('Total registrations').'
					</td>
					<td>
						'.(int)($results['total_registrations']).'
					</td>
				</tr>
				<tr class="tr_odd">
					<td class="td_align_left">
						'.$this->l('Total orders').'
					</td>
					<td>
						'.(int)($results['total_orders']).'
					</td>
				</tr>
				<tr>
					<td class="td_align_left">
						'.$this->l('Product pages viewed').'
					</td>
					<td>
						'.(int)($results['total_viewed']).'
					</td>
				</tr>
			</table>
		</div>';
		return $content;
	}

	public function getStatsSales()
	{
                $valid_state_sale = Configuration::get('ESTADOS_VALIDOS_VENTA_REPORTE'); 
                
		$content = '<div id="table_info_large">
				<h5><a href="index.php?tab=AdminStats&token='.Tools::getAdminTokenLite('AdminStats').'">'.$this->l('View more').'</a> <strong>'.$this->l('Statistics').'</strong> / '.$this->l('This week\'s sales').'</h5>
				<div id="stat_google">';

		$chart = new Chart();
		$chart->getCurve(1)->setType('bars');
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT SUM(total_paid / conversion_rate) as total_converted, left(invoice_date, 10) as invoice_date
			FROM '._DB_PREFIX_.'orders o
			WHERE valid = 1
			AND total_paid > 0
			AND invoice_date BETWEEN \''.date('Y-m-d', strtotime('-7 DAYS', time())).' 00:00:00\' AND \''.date('Y-m-d H:i:s').'\'
			'.Shop::addSqlRestriction(Shop::SHARE_ORDER).' AND o.current_state IN ('.$valid_state_sale.')
			GROUP BY DATE(invoice_date)
		');
		foreach ($result as $row)
			$chart->getCurve(1)->setPoint(strtotime($row['invoice_date'].' 02:00:00'), $row['total_converted']);
		$chart->setSize(580, 170);
		$chart->setTimeMode(strtotime('-7 DAYS', time()), time(), 'd');
		$currency = Tools::setCurrency($this->context->cookie);
		$chart->getCurve(1)->setLabel($this->l('Sales + Tax').' ('.strtoupper($currency->iso_code).')');

		$content .= $chart->fetch();
		$content .= '	</div>
		</div>';
		return $content;
	}
}
