<?php
class PDF extends PDFCore
{
	const TEMPLATE_SUPPLY_ORDER_RECEIPT = 'SupplyOrderReceipt';

	public function renderSupplyOrderForm($display = true)
	{
		$this->pdf_renderer = new PDFGenerator((bool)Configuration::get('PS_PDF_USE_CACHE'));
		$render = false;
		$this->pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
		foreach ($this->objects as $object)
		{
			$template = $this->getTemplateObject($object);
			if (!$template)
				continue;

			if (empty($this->filename))
			{
				$this->filename = $template->getFilename();
				if (count($this->objects) > 1)
					$this->filename = $template->getBulkFilename();
			}

			$template->assignHookData($object);

			$this->pdf_renderer->createHeader($template->getHeader());
			$this->pdf_renderer->createFooter($template->getFooter());
			$this->pdf_renderer->createContent($template->getContent());
			$this->pdf_renderer->SetHeaderMargin(5);
			$this->pdf_renderer->SetFooterMargin(20);
			$this->pdf_renderer->setMargins(10, 30, 10);
			$this->pdf_renderer->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$this->pdf_renderer->AddPage();
			$this->pdf_renderer->writeHTML($this->pdf_renderer->content, true, false, true, false, '');
			$render = true;

			unset($template);
		}

		if ($render)
		{
			// clean the output buffer
			if (ob_get_level() && ob_get_length() > 0)
				ob_clean();
			return $this->pdf_renderer->render($this->filename, $display);
		}
	}


	public function render($display = true, $back_cancel = false)
	{
		$render = false;
		$this->pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
		foreach ($this->objects as $object)
		{
			$template = $this->getTemplateObject($object);
			if (!$template)
				continue;

			if (empty($this->filename))
			{
				$this->filename = $template->getFilename();
				if (count($this->objects) > 1)
					$this->filename = $template->getBulkFilename();
			}

			$template->assignHookData($object);

			$this->pdf_renderer->createHeader($template->getHeader());
			$this->pdf_renderer->createFooter($template->getFooter());
			$this->pdf_renderer->createContent($template->getContent());
			$this->pdf_renderer->writePage($back_cancel);
			$render = true;

			unset($template);
		}

		if ($render)
		{
			// clean the output buffer
			if (ob_get_level() && ob_get_length() > 0)
				ob_clean();
			return $this->pdf_renderer->render($this->filename, $display);
		}
	}

	public function renderSupplyOrderReceipt($display = true)
	{
		$this->pdf_renderer = new PDFGenerator((bool)Configuration::get('PS_PDF_USE_CACHE'));
		$render = false;
		$this->pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
		foreach ($this->objects as $object)
		{
			$template = $this->getTemplateObject($object);
			if (!$template)
				continue;

			if (empty($this->filename))
			{
				$this->filename = $template->getFilename();
				if (count($this->objects) > 1)
					$this->filename = $template->getBulkFilename();
			}

			$template->assignHookData($object);

			$this->pdf_renderer->createHeader($template->getHeader());
			$this->pdf_renderer->createFooter($template->getFooter());
			$this->pdf_renderer->createContent($template->getContent());
			$this->pdf_renderer->SetHeaderMargin(5);
			$this->pdf_renderer->SetFooterMargin(20);
			$this->pdf_renderer->setMargins(10, 30, 10);
			$this->pdf_renderer->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$this->pdf_renderer->AddPage();
			$this->pdf_renderer->writeHTML($this->pdf_renderer->content, true, false, true, false, '');
			$render = true;

			unset($template);
		}

		if ($render)
		{
			// clean the output buffer
			if (ob_get_level() && ob_get_length() > 0)
				ob_clean();
			return $this->pdf_renderer->render($this->filename, $display);
		}
	}
	
}