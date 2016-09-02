<?php

/**
 * @since 1.5
 */
class PDFGenerator extends PDFGeneratorCore
{

	public function __construct($use_cache = false)
	{
		if ( in_array("generateInvoicePDF", $_REQUEST) ) {
			TCPDF::__construct('P', 'mm', 'TIRA', true, 'UTF-8', $use_cache, false);
		} else {
			parent::__construct('P', 'mm', 'A4', true, 'UTF-8', $use_cache, false);	
		}
	}

	/**
	 * Write a PDF page
	 */
	public function writePage( $back_cancel = false )
	{
		$this->SetHeaderMargin(5);
		$this->SetFooterMargin(18);
		$this->setMargins(10, 40, 10);
		$this->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

		if ( in_array("generateInvoicePDF", $_REQUEST) ) {
			$this->SetHeaderMargin(0);
			$this->SetFooterMargin(0);
			$this->setMargins(5, 0, 5);
			$this->SetAutoPageBreak(true, 0);
		}

		$this->AddPage();

		// if( $back_cancel == true ) {
		// 	$img_file = _PS_IMG_DIR_.'Anulada-02.png';
	 //        $this->Image($img_file, 0, 0, 550, 600, '', '', '', false, 300, '', false, false, 0);
	 //    }

		$this->writeHTML($this->content, true, false, true, false, '');
	}

}	