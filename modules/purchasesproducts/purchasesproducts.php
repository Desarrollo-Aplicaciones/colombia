<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Compras de productos
 * Semaforo de compras, permite obtener el control de las ventas realizadas y el detalle de los productos existentes
 *
 * @author Tatiana Castiblanco
 */
class PurchasesProducts extends Module
{
    private $_html = '';
    protected $_errors = array();
    protected $_msg = '';
    protected $token = '';
    protected $moduleUrl = '';
        
    public function __construct()
    {
        $this->name = 'purchasesproducts';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Farmalisto';
        $this->need_instance = 0;
        //$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5');
        //$this->dependencies = array('blockcart');

        parent::__construct();

        $this->displayName = $this->l('Compra de productos');
        $this->description = $this->l('Permite obtener el control de las ventas realizadas y el detalle de los productos existentes.');
        
        //$this->confirmUninstall = $this->l('Estas seguro que quiere desinstalar?');
        //if (!Configuration::get('purchasesproducts'))      
        //$this->warning = $this->l('No name provided');
        
        global $cookie;
        $this->token = md5(pSQL(_COOKIE_KEY_.'AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$cookie->id_employee));
        $this->moduleUrl = 'index.php?controller=AdminModules&token='.$this->token.'&configure=purchasesproducts';
        
        $this->context->controller->addCSS($this->_path . 'grid/css/light-mint/all.min.css');
        $this->context->controller->addJS($this->_path . 'grid/js/shieldui-all.min.js');
        if(Tools::getValue('page')) {
            $this->context->controller->addCSS($this->_path . 'views/css/'.Tools::getValue('page').'.css');
            $this->context->controller->addJS($this->_path . 'views/js/'.Tools::getValue('page').'.js');
        } else {
            // Archivos JS y CSS para la página por defecto
            $this->context->controller->addCSS($this->_path . 'views/css/purchasesproducts.css');
            $this->context->controller->addJS($this->_path . 'views/js/purchasesproducts.js');
        }

    }
    
    /**
     * @see Module::install()
     * @return bool 
     */
    public function install()
    {
        if( !parent::install() || !$this->_createTab() ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @see Module::uninstall()
     * @return bool 
     */
    public function uninstall()
    {
        // Uninstall Module
        if ( !parent::uninstall() || !$this->_deleteTab() ) {
           return false;
        }

        return true;
    }

    /**
     * Crea un tab personalizado para el módulo durante la instalación
     *
     * @return bool 
     */
    private function _createTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminPurchasesProducts';
        $tab->module = 'purchasesproducts';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminStock'); // Aparecerá al final del menú existencias
        //$tab->position = 11;

        // Need a foreach for the language
        foreach (Language::getLanguages(false) as $lang) {
                $tab->name[(int)$lang['id_lang']] = 'Compras de productos';
        }
        if (!$tab->add()) {
            return false;
        }

        return true;
    }
   
   /**
    * Elimina el tab personalizado del módulo
    *
    * @return bool 
    */
   private function _deleteTab()
   {
        $tab = new Tab((int)Tab::getIdFromClassName('AdminPurchasesProducts'));
        if(!$tab->delete()) {
            return false;
        }

        return true;
   }
   
   /**
    * Se encarga de la configuración del módulo. 
    * Crea y gestiona el funcionamiento del formulario 
    * de configuración en el back-office.
    *
    * @return string  contenido html
    */
   public function getContent() 
   {
       return $this->_html . $this->_displayForm();
   }
   
   /**
    * Se encarga de generar el formulario de configuración mostrando
    * los campos necesarios.
    *
    * @return string  contenido html
    */
    private function _displayForm()
    {
        $this->smarty->assign('moduleUrl', $this->moduleUrl);
        $this->smarty->assign('displayName', $this->displayName);
        
        switch (Tools::getValue('page')) {
            case 'purchasedetail':
                    $ordersSql = 'SELECT 1 AS id, "Hola" AS producto, "123456" AS ean, "asdsa" AS laboratorio, "2017-02-01"  AS fecha, "Bogota" AS ciudad, "Hola" AS warehouse_quantity';
                    $orders = Db::getInstance()->ExecuteS($ordersSql);
                    
                    $this->smarty->assign('gridDataDetail', json_encode($orders));
                break;
            default:
                $ordersSql = 'SELECT  o.id_order AS id, od.product_name AS producto, p.reference AS ean,
                m.`name` AS laboratorio, date_format (o.delivery_date, "%Y-%m-%d") AS fecha, 
                a.city AS ciudad, sam.quantity AS warehouse_quantity,
                od.product_quantity AS solicitados
                FROM ' . _DB_PREFIX_ . 'orders AS  o 
                INNER JOIN ' . _DB_PREFIX_ . 'address AS a ON ( o.id_address_delivery = a.id_address )
                INNER JOIN ' . _DB_PREFIX_ . 'order_detail AS od  ON ( o.id_order = od.id_order)
                INNER JOIN ' . _DB_PREFIX_ . 'product AS p ON ( p.id_product = od.product_id)
                INNER JOIN ' . _DB_PREFIX_ . 'manufacturer AS m ON ( m.id_manufacturer = p.id_manufacturer)
                INNER JOIN ' . _DB_PREFIX_ . 'stock_available_mv AS sam ON ( sam.id_product = p.id_product )
                WHERE  current_state = 9 
                ORDER BY o.id_order ASC';

                $orders = Db::getInstance()->ExecuteS($ordersSql);
                $this->smarty->assign('gridData', json_encode($orders)); 
        }

        if(Tools::getValue('page')) {
            return $this->display(__FILE__, 'views/templates/admin/'. Tools::getValue('page') .'.tpl');
        } else {
            // Página por defecto
            return $this->display(__FILE__, 'views/templates/admin/purchasesproducts.tpl');
        }
    }
    
    /**
     * Comprueba que la información
     * introducida en el formulario es correcta
     *
     * @return bool
     */
    private function _postValidation()
    {
        return true;
    }
    
    /**
     * Se encarga de almacenar en la base de datos
     * la información introducida en el formulario
     *
     * @return bool
     */
    
    private function _postProcess()
    {
        // Válida si se ha pulsado el botón Guardar del formulario
        if( !Tools::isSubmit('btn-submit') ) {
            return false;
        }
    }
    
 
  
}