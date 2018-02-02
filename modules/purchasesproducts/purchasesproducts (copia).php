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
        $fields_list = array(
            'id_order' => array(
                'title' => $this->l('Id Order'),
                'width' => 140,
                ['list'] => $this->orders_array, 
                'filter_type' => 'int', 'bool', 'decimal',
                'filter_key' => 'o!delivery_date',
                 'filter_type' => 'int'
            ),
            'delivery_date' => array(
                'title' => $this->l('Fecha'),
                'width' => 140,
                'type' => 'datetime',                
                'filter_key' => 'o!id_order',
            ),
            'city' => array(
                'title' => $this->l('Ciudad'),
                'width' => 140,
                'type' => 'text',
                'filter_key' => 'a!city',
                
            )
        );
   
        $helper = new HelperList();
        $helper->identifier = 'id_order';
        $helper->_orderBy = 'id_order';
        $helper->_orderWay = 'DESC';
        $helper->show_toolbar = false;
        $helper->actions = array('edit', 'delete', 'view');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $ordersSql = 'SELECT  o.id_order, o.delivery_date, a.city
            FROM ' . _DB_PREFIX_ . 'orders AS  o 
            INNER JOIN ' . _DB_PREFIX_ . 'address AS a ON ( o.id_address_delivery = a.id_address )
            WHERE  current_state = 3';

        $orders = Db::getInstance()->ExecuteS($ordersSql);
        $this->smarty->assign('listPurchases', $helper->generateList($orders, $fields_list)); 	

        $suppliersSql= 'SELECT  id_supplier,name FROM '._DB_PREFIX_.'supplier';
        $this->smarty->assign('suppliers', Db::getInstance()->ExecuteS($suppliersSql)); 
        
        $this->smarty->assign('displayName', $this->displayName);
        
        return $this->display(__FILE__, 'views/templates/admin/purchasesproducts.tpl');
        
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
     * Se encarga de almacenar en la base de datoss
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