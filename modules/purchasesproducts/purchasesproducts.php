<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once("models/Orders.php");
/**
 * Compras de productos
 * Semaforo de compras, permite obtener el control de las ventas realizadas y el detalle de los productos existentes
 * 
 * Se debe tener en cuenta que al instalar el módulo, toca actualizar el procedimiento almacenado con nombre update_stock_available_mv
 * debido que este se le añadiran 2 campos adicionales para su buen funcionamiento, mas información en la wiki.
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
    protected $oder = '';
        
    public function __construct()
    {
        $this->name = 'purchasesproducts';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Farmalisto';
        $this->need_instance = 0;
        $this->dir = '/modules/purchasesproducts/';
        $this->orders = new Orders();
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
        $this->context->controller->addCSS($this->_path . 'views/css/inventory.css');
        $this->context->controller->addJS($this->_path . 'views/js/inventory.js');
        if(!Tools::getValue('page')) {
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
        $this->createTables();
        return true;
    }
    
    /**
     * Crea la tablas necesarias para su respectivo funcionamiento del módulos de compras
     */
    protected function createTables() {
        
        Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `ps_reserve_product` (
            `id_reserve`  int NOT NULL AUTO_INCREMENT ,
            `id_order`  int NOT NULL ,
            `id_product`  int NOT NULL ,
            `quantity_reserve`  int NOT NULL ,
            `missing_reserve`  int NOT NULL ,
            PRIMARY KEY (`id_reserve`)
        );");
        
        Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `ps_history_reserve` (
            `id_history`  int NOT NULL AUTO_INCREMENT ,
            `id_order`  int NOT NULL ,
            `quantity_icrs`  int NOT NULL ,
            PRIMARY KEY (`id_history`)
        );");
        
        Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `ps_inventarios_proveedor` (
            `id_inventario_proveedor`  int NOT NULL AUTO_INCREMENT ,
            `ean`  varchar(50) NOT NULL ,
            `codigo_proveedor`  int NOT NULL ,
            `descripcion`  varchar(80) NOT NULL ,
            `valor_proveedor`  int NOT NULL ,
            `unidades_proveedor`  int NOT NULL ,
            `proveedor`  varchar(30) NOT NULL ,
            `id_proveedor`  int NOT NULL ,
            PRIMARY KEY (`id_inventario_proveedor`)
        );");
        
        Db::getInstance()->execute("ALTER TABLE `ps_stock_available_mv`
            ADD COLUMN `reserve_on_stock`  int(11) NULL DEFAULT 0 AFTER `out_of_stock`;");

    }
    
    /**
     * Crea la tablas necesarias para su respectivo funcionamiento del módulos de compras
     */
    protected function dropTables() {
        
        Db::getInstance()->execute("DROP TABLE `ps_reserve_product`;");
        
        Db::getInstance()->execute("DROP TABLE `ps_history_reserve`;");
        
        Db::getInstance()->execute("DROP TABLE `ps_inventarios_proveedor`;");
        
        Db::getInstance()->execute("ALTER TABLE `ps_stock_available_mv`
                            DROP COLUMN `reserve_on_stock`;");

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
        $this->dropTables();
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
                $this->context->controller->addCSS($this->_path . 'views/css/'.Tools::getValue('page').'.css');
                $id_proveedor = Tools::getValue('id_proveedor');
                if(isset($id_proveedor) && !empty($id_proveedor)) {
                    
                    $this->context->controller->addJS($this->_path . 'views/js/'.Tools::getValue('page').'_proveedor.js');

                    $this->smarty->assign('gridDataDetail', json_encode($this->orders->getOrdersToBuyToProvider($id_proveedor)));
                } else {
                    
                    $this->context->controller->addJS($this->_path . 'views/js/'.Tools::getValue('page').'.js');
                    
                    $this->smarty->assign('gridDataDetail', json_encode($this->orders->getOrdersToBuy()));
                }
                    //$this->smarty->assign('inventory', $this->groupArray($this->orders->getInventory(), 'id_proveedor', 'proveedor'));
                    $this->smarty->assign('proveedor', $this->orders->getNameProvider());
                break;
            case 'loadinventoryfield':
                    $this->loadinventoryfield();
                break;
            default:
                $this->smarty->assign('gridData', json_encode($this->orders->getOrdersToProducts()));
        }

        if(Tools::getValue('page')) {
            return $this->display(__FILE__, 'views/templates/admin/'. Tools::getValue('page') .'.tpl');
        } else {
            // Página por defecto
            return $this->display(__FILE__, 'views/templates/admin/purchasesproducts.tpl');
        }
    }

    /**
    * Realizo agrupamiento de la información de los proveedores.
    */
    public function groupArray($array, $groupkey, $nameProvider)
    {
        if (count($array)>0)
        {
            $keys = array_keys($array[0]);
            $removekey = array_search($groupkey, $keys);        if ($removekey===false)
                return array("Clave \"$groupkey\" no existe");
            else
                unset($keys[$removekey]);

            $groupcriteria = array();
            $return=array();
            foreach($array as $value)
            {
                $item=null;
                foreach ($keys as $key)
                {
                    $item[$key] = $value[$key];
                }
                $busca = array_search($value[$groupkey], $groupcriteria);
                if ($busca === false)
                {
                    $groupcriteria[]=$value[$groupkey];
                    $return[]=array($groupkey=>$value[$groupkey], $nameProvider=>$value[$nameProvider],'groupeddata'=>array());
                    $busca=count($return)-1;
                }
                $return[$busca]['groupeddata'][]=$item;
            }

            return $return;
        }
        else
            return array();
    }

    public function loadinventoryfield() {
        
        if ($_FILES['fileField']['tmp_name']){
            
            $errors = '';

            $plik_tmp = $_FILES['fileField']['tmp_name']; 
            $plik_nazwa = $_FILES['fileField']['name']; 
            $plik_rozmiar = $_FILES['fileField']['size'];
            
            if(is_uploaded_file($plik_tmp)){
                
                $date=date("Y-m-d-h-i-s");
                
                if (move_uploaded_file($_FILES['fileField']['tmp_name'], '..'.$this->dir.$date.".csv")){
                    
                    $fileName = __DIR__ . '/'.$date.".csv";

                    if (($fichero = fopen($fileName, "r")) !== FALSE) {

                        $this->orders->truncateInventory();

                        $dataSave = true;
                        $i = 0;
                        while (($datos = fgetcsv($fichero, 0, ";", "\"", "\""))) {
                            
                            if($i > 0) {

                                if(!$this->orders->insertInventory($datos)) {
                                    $dataSave = false;
                                }
                            }

                            $i++;

                        }
                        
                        unlink($fileName);

                        fclose($fichero);

                        if(!$dataSave) {
                            $errors = "&errors=1";
                        }
                    }
                    
                }   
            } 
        }
            
            
        $token = Tools::getValue('token') ? Tools::getValue('token') : $this->token;
        Tools::redirectAdmin('index.php?controller=AdminModules&token='.$token.'&configure=purchasesproducts'.$errors);
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