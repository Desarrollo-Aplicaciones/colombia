<?php
class AdminPdfController extends AdminPdfControllerCore
{

	public function generateInvoicePDFByIdOrder($id_order)
	{
		$order = new Order((int)$id_order);

		if (!Validate::isLoadedObject($order))
			die(Tools::displayError('The order cannot be found within your database.'));

		$order_invoice_list = $order->getInvoicesCollection();
		Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));
		$this->generatePDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $order->current_state);
	}

	public function generatePDF($object, $template, $stateorder = 0)
	{
		$pdf = new PDF($object, $template, Context::getContext()->smarty);
		if ($template=="SupplyOrderForm") {
			$pdf->renderSupplyOrderForm();
		}elseif ($template=='SupplyOrderReceipt') {
			$pdf->renderSupplyOrderReceipt();
		} else { 

			if ( $stateorder == 6 ) {
				$stateorder = true;
			} else {
				$stateorder = false;
			}
			$pdf->render(true, $stateorder);			
		}
	}

	public function processGenerateSupplyOrderReceiptPDF()
	{
		if (!Tools::isSubmit('id_supply_order'))
			die (Tools::displayError('The supply order ID is missing.'));

		$id_supply_order = (int)Tools::getValue('id_supply_order');
		$supply_order = new SupplyOrder($id_supply_order);

		if (!Validate::isLoadedObject($supply_order))
			die(Tools::displayError('The supply order cannot be found within your database.'));
		$this->generatePDF($supply_order, PDF::TEMPLATE_SUPPLY_ORDER_RECEIPT);
	}
}