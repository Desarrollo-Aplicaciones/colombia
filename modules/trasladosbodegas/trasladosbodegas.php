<?php
if (!defined('_PS_VERSION_'))
  exit;
 
class TrasladosBodegas extends Module
  {
  public function __construct()
    {
    $this->name = 'trasladosbodegas';
    $this->tab = 'Test';
    $this->version = '0.1 Alfa';
    $this->author = 'Farmalisto - Esteban Rincón Correa';
    $this->need_instance = 0;
 
    parent::__construct();
 
    $this->displayName = $this->l('Traslado de productos entre Bodegas');
    $this->description = $this->l('Módulo para trasladar productos entre las diferentes bodegas de acuerdo a su ICR');
    }
 
  public function install()
    {
    if (!$id_tab = Tab::getIdFromClassName('AdminWarehouseTransfer'))
        {
        $tab = new Tab();
        $tab->class_name = 'AdminWarehouseTransfer';
        $tab->module = 'trasladosbodegas';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminStock');
        foreach (Language::getLanguages(false) as $lang)
        $tab->name[(int)$lang['id_lang']] = 'Traslados de inventario';
        if (!$tab->save())
        return $this->_abortInstall($this->l('Imposible crear la pestaña de nuevo'));
        }
        $query = "CREATE TABLE IF NOT EXISTS `ps_supply_order_icr_history` (
            `id_supply_order_icr_history` INT(11) NOT NULL AUTO_INCREMENT,
            `id_origin_warehouse` INT(11) NOT NULL,
            `id_employee` INT(11) NOT NULL,
            `id_icr` INT(11) NOT NULL,
            `date` DATETIME NOT NULL,
            PRIMARY KEY (`id_supply_order_icr_history`)
        )
        DEFAULT CHARSET=utf8
        ENGINE=Aria";
        if(!(Db::getInstance()->Execute($query_icr_duplicado)))
        {
            return $this->_abortInstall($this->l('Imposible crear la tabla de nuevo'));
        }

        Configuration::updateValue('HOME_FEATURED_NBR', 8);

        if (!parent::install()) {   
            return false;
        }

        return true;
    }
    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }
    public function displayForm()
    {
        $bodega = array();
        $query = new DbQuery();
        $query->select('id_warehouse,reference,name');
        $query->from('warehouse');
        $items = Db::getInstance()->executeS($query);
        foreach ($items as $item) {
            $bodega[$item['id_warehouse']] = $item['reference'].' - '.$item['name'];
        }
        $this->context->smarty->assign(array('bodega'=> $bodega));
        return $this->display(__FILE__, 'trasladosbodegas.tpl', $this->getCacheId());
    }
    public function getContent()
    {
        $output = '<h2>'.$this->displayName.'</h2>';
        if (Tools::isSubmit('submitICR'))
        {
            if (Tools::getValue('destination_warehouse') != 'todas')
            {
                $data = $_POST;
                $id_destino = $data['destination_warehouse'];
                $campo = array();
                unset($data['tab']);
                unset($data['submitICR']);
                unset($data['destination_warehouse']);
                if (count($data) > 0)
                {
                    $arreglo = $this->armarArreglo($data);
                    foreach ($arreglo as $value) {
                        if(!(Db::getInstance()->insert('supply_order_icr_history', $value)) ||
                        !(Db::getInstance()->update('supply_order_icr', array('id_warehouse' => $id_destino),"id_icr='".$value['id_icr']."'")))
                        {
                            $output .= $this->displayError("Error en el acceso a la base de datos");
                            break;
                        }
                    }
                    $output .= $this->displayConfirmation('ICRs transferidos con éxito');
                }
                else{
                    $output .= $this->displayError("No se ingresaron ICRs");
                }
            }
            else{
                 $output .= $this->displayError("Bodega de destino no válida");
            }
        }
        elseif (Tools::isSubmit('export')){
            $bodega = Tools::getValue('destination_warehouse');
            $data = $this->generarReporte($bodega);
            $this->exportarXls($data);
            exit;
        }
        elseif (Tools::isSubmit('exportDetail')){
            $bodega = Tools::getValue('destination_warehouse');
            $data = $this->generarReporte($bodega, true);
            $this->exportarXls($data, "detalle");
            exit;
        }
        return $output.$this->displayForm();
    }
    public function armarArreglo($data)
    {
      $respuesta = array();
      foreach($data as $nombre_campo => $valor){
        $asignacion .= $nombre_campo . " = " . $valor." " ;
        $campo = explode('_', $nombre_campo);
        if ( $campo[0] == 'icr'){
          $respuesta[$campo[1]]['id_icr'] = $valor;
          $respuesta[$campo[1]]['id_employee'] = $this->context->cookie->id_employee;
          $respuesta[$campo[1]]['date'] = date('Y-m-d H:i:s');
        }
        if ( $campo[0] == 'origin'){
          $respuesta[$campo[1]]['id_origin_warehouse'] = $valor;
        }
      }
      return $respuesta;
    }
    public function generarReporte($bodega, $detail = FALSE){
        $query = new DbQuery();
        if($detail == FALSE){
            $query->select('sod.id_product AS Id,p.reference AS Referencia,pla.name AS Nombre,COUNT(sod.id_product) AS Cantidad,w.name AS Bodega, sod.id_supply_order AS ordensuministro');
        }
        else{
            $query->select('sod.id_product AS Id,p.reference AS Referencia,pla.name AS Nombre,i.cod_icr AS Codigo,w.name AS Bodega, sod.id_supply_order AS ordensuministro');
        }
        $query->from('supply_order_icr', 'soi');
        $query->innerJoin('icr', 'i', ' i.id_icr = soi.id_icr ');
        $query->innerJoin('warehouse', 'w', ' w.id_warehouse = soi.id_warehouse ');
        $query->innerJoin('supply_order_detail', 'sod', ' sod.id_supply_order_detail = soi.id_supply_order_detail ');
        $query->innerJoin('product', 'p', ' p.id_product = sod.id_product ');
        $query->innerJoin('product_lang', 'pla', ' pla.id_product = sod.id_product ');
        if($detail != FALSE && $bodega != "todas"){
            $query->where('soi.id_warehouse = '.$bodega);
        }
        elseif($bodega != "todas"){
            $query->where('soi.id_warehouse = '.$bodega);
            $query->groupBy('sod.id_product');
        }
        elseif($detail != FALSE){
            $query->groupBy('i.cod_icr,sod.id_product, soi.id_warehouse');
        }
        else{
            $query->groupBy('sod.id_product, soi.id_warehouse');
        }
        $items = Db::getInstance_slv()->executeS($query);
        return $items;
    }
    public function exportarXls($data, $nombre = NULL){
      $filename ='icr'.$nombre.date('Y-m-d').".xls";
      $arreglo = implode("\t", array_keys($data[0]))."\n";
      foreach($data as $key => $nombre_campo){
        $arreglo .= implode("\t", preg_replace("[\n|\r|\n\r|\t]", "", $nombre_campo))."\n";
      }
      header('Content-type: application/ms-excel');
      header('Content-Disposition: attachment; filename='.$filename);
      header('Content-Type: application/force-download; charset=UTF-8');
      header('Cache-Control: no-store, no-cache');
      echo $arreglo;
    }
  }
?>