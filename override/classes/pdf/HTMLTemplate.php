<?php

abstract class HTMLTemplate extends HTMLTemplateCore
{
	public function getHeader()
	{
		$shop_name = Configuration::get('PS_SHOP_NAME', null, null, (int)$this->order->id_shop);
		$path_logo = $this->getLogo();

		$width = 0;
		$height = 0;
		if (!empty($path_logo))
			list($width, $height) = getimagesize($path_logo);

		$this->smarty->assign(array(
			'logo_path' => $path_logo,
			'img_ps_dir' => 'https://'.Tools::getMediaServer(_PS_IMG_)._PS_IMG_,
			'img_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
			'title' => $this->title,
			'date' => $this->date,
			'shop_name' => $shop_name,
			'width_logo' => $width,
			'height_logo' => $height,
			'invoice_number_header' => (int)$this->order->invoice_number
		));

		if ( get_class($this) == 'HTMLTemplateInvoice' ) {
			return false;
		} else {
			return $this->smarty->fetch($this->getTemplate('header'));
		}
	}

	public function getFooter()
	{
		$shop_address = $this->getShopAddress();
		$this->smarty->assign(array(
			'available_in_your_account' => $this->available_in_your_account,
			'shop_address' => $shop_address,
			'shop_fax' => Configuration::get('PS_SHOP_FAX', null, null, (int)$this->order->id_shop),
			'shop_phone' => Configuration::get('PS_SHOP_PHONE', null, null, (int)$this->order->id_shop),
			'shop_details' => Configuration::get('PS_SHOP_DETAILS', null, null, (int)$this->order->id_shop),
			'free_text' => Configuration::get('PS_INVOICE_FREE_TEXT', (int)Context::getContext()->language->id, null, (int)$this->order->id_shop)
		));

		if ( get_class($this) == 'HTMLTemplateInvoice' ) {
			return false;
		} else {
			return $this->smarty->fetch($this->getTemplate('footer'));
		}
	}
}

