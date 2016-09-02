<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:59:20
         compiled from "/var/www/themes/gomarket/product.tpl" */ ?>
<?php /*%%SmartyHeaderCode:649638387534873c8ce17e2-77742494%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '748e650dc6581a3697eb529fdc17beaec1bde348' => 
    array (
      0 => '/var/www/themes/gomarket/product.tpl',
      1 => 1397075459,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '649638387534873c8ce17e2-77742494',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'errors' => 0,
    'currencySign' => 0,
    'currencyRate' => 0,
    'currencyFormat' => 0,
    'currencyBlank' => 0,
    'tax_rate' => 0,
    'jqZoomEnabled' => 0,
    'product' => 0,
    'groups' => 0,
    'display_qties' => 0,
    'allow_oosp' => 0,
    'key_specific_price' => 0,
    'specific_price_value' => 0,
    'group_reduction' => 0,
    'ecotaxTax_rate' => 0,
    'last_qties' => 0,
    'no_tax' => 0,
    'priceDisplay' => 0,
    'restricted_country_mode' => 0,
    'PS_CATALOG_MODE' => 0,
    'cover' => 0,
    'stock_management' => 0,
    'priceDisplayPrecision' => 0,
    'productPriceWithoutReduction' => 0,
    'productPrice' => 0,
    'img_ps_dir' => 0,
    'customizationFields' => 0,
    'field' => 0,
    'imgIndex' => 0,
    'textFieldIndex' => 0,
    'key' => 0,
    'pictures' => 0,
    'img_prod_dir' => 0,
    'combinationImages' => 0,
    'combinationId' => 0,
    'combination' => 0,
    'image' => 0,
    'images' => 0,
    'combinations' => 0,
    'idCombination' => 0,
    'attributesCombinations' => 0,
    'aC' => 0,
    'adminActionDisplay' => 0,
    'base_dir' => 0,
    'confirmation' => 0,
    'img_dir' => 0,
    'have_image' => 0,
    'link' => 0,
    'lang_iso' => 0,
    'isformula' => 0,
    'imageIds' => 0,
    'url_manufacturer' => 0,
    'img_manufacturer' => 0,
    'quantityBackup' => 0,
    'category' => 0,
    'tax_enabled' => 0,
    'display_tax_label' => 0,
    'packItems' => 0,
    'ecotax_tax_exc' => 0,
    'ecotax_tax_inc' => 0,
    'unit_price' => 0,
    'cart_qties' => 0,
    'valor_restante' => 0,
    'packItem' => 0,
    'HOOK_PRODUCT_OOS' => 0,
    'HOOK_PRODUCT_ACTIONS' => 0,
    'group' => 0,
    'id_attribute_group' => 0,
    'groupName' => 0,
    'colors' => 0,
    'id_attribute' => 0,
    'group_attribute' => 0,
    'col_img_dir' => 0,
    'img_col_dir' => 0,
    'default_colorpicker' => 0,
    'HOOK_EXTRA_RIGHT' => 0,
    'quantity_discounts' => 0,
    'quantity_discount' => 0,
    'features' => 0,
    'accessories' => 0,
    'HOOK_PRODUCT_TAB' => 0,
    'attachments' => 0,
    'feature' => 0,
    'attachment' => 0,
    'accessory' => 0,
    'grid_product' => 0,
    'accessoryLink' => 0,
    'static_token' => 0,
    'customizationFormTarget' => 0,
    'pic_dir' => 0,
    'customizationField' => 0,
    'textFields' => 0,
    'HOOK_PRODUCT_TAB_CONTENT' => 0,
    'HOOK_PRODUCT_FOOTER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873c9731819_77863396',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873c9731819_77863396')) {function content_534873c9731819_77863396($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
if (!is_callable('smarty_modifier_date_format')) include '/var/www/tools/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_capitalize')) include '/var/www/tools/smarty/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_math')) include '/var/www/tools/smarty/plugins/function.math.php';
if (!is_callable('smarty_function_counter')) include '/var/www/tools/smarty/plugins/function.counter.php';
?>


	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<?php if (count($_smarty_tpl->tpl_vars['errors']->value)==0){?>
	<script type="text/javascript">
// <![CDATA[

// PrestaShop internal settings
var currencySign = '<?php echo html_entity_decode($_smarty_tpl->tpl_vars['currencySign']->value,2,"UTF-8");?>
';
var currencyRate = '<?php echo floatval($_smarty_tpl->tpl_vars['currencyRate']->value);?>
';
var currencyFormat = '<?php echo intval($_smarty_tpl->tpl_vars['currencyFormat']->value);?>
';
var currencyBlank = '<?php echo intval($_smarty_tpl->tpl_vars['currencyBlank']->value);?>
';
var taxRate = <?php echo floatval($_smarty_tpl->tpl_vars['tax_rate']->value);?>
;
var jqZoomEnabled = <?php if ($_smarty_tpl->tpl_vars['jqZoomEnabled']->value){?>true<?php }else{ ?>false<?php }?>;

//JS Hook
var oosHookJsCodeFunctions = new Array();

// Parameters
var id_product = '<?php echo intval($_smarty_tpl->tpl_vars['product']->value->id);?>
';
var productHasAttributes = <?php if (isset($_smarty_tpl->tpl_vars['groups']->value)){?>true<?php }else{ ?>false<?php }?>;
var quantitiesDisplayAllowed = <?php if ($_smarty_tpl->tpl_vars['display_qties']->value==1){?>true<?php }else{ ?>false<?php }?>;
var quantityAvailable = <?php if ($_smarty_tpl->tpl_vars['display_qties']->value==1&&$_smarty_tpl->tpl_vars['product']->value->quantity){?><?php echo $_smarty_tpl->tpl_vars['product']->value->quantity;?>
<?php }else{ ?>0<?php }?>;
var allowBuyWhenOutOfStock = <?php if ($_smarty_tpl->tpl_vars['allow_oosp']->value==1){?>true<?php }else{ ?>false<?php }?>;
var availableNowValue = '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->available_now, 'quotes', 'UTF-8');?>
';
var availableLaterValue = '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->available_later, 'quotes', 'UTF-8');?>
';
var productPriceTaxExcluded = <?php echo (($tmp = @$_smarty_tpl->tpl_vars['product']->value->getPriceWithoutReduct(true))===null||$tmp==='' ? 'null' : $tmp);?>
 - <?php echo $_smarty_tpl->tpl_vars['product']->value->ecotax;?>
;
var productBasePriceTaxExcluded = <?php echo $_smarty_tpl->tpl_vars['product']->value->base_price;?>
 - <?php echo $_smarty_tpl->tpl_vars['product']->value->ecotax;?>
;

var reduction_percent = <?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']=='percentage'){?><?php echo $_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']*100;?>
<?php }else{ ?>0<?php }?>;
var reduction_price = <?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']=='amount'){?><?php echo floatval($_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']);?>
<?php }else{ ?>0<?php }?>;
var specific_price = <?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['price']){?><?php echo $_smarty_tpl->tpl_vars['product']->value->specificPrice['price'];?>
<?php }else{ ?>0<?php }?>;
var product_specific_price = new Array();
<?php  $_smarty_tpl->tpl_vars['specific_price_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['specific_price_value']->_loop = false;
 $_smarty_tpl->tpl_vars['key_specific_price'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['product']->value->specificPrice; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['specific_price_value']->key => $_smarty_tpl->tpl_vars['specific_price_value']->value){
$_smarty_tpl->tpl_vars['specific_price_value']->_loop = true;
 $_smarty_tpl->tpl_vars['key_specific_price']->value = $_smarty_tpl->tpl_vars['specific_price_value']->key;
?>
product_specific_price['<?php echo $_smarty_tpl->tpl_vars['key_specific_price']->value;?>
'] = '<?php echo $_smarty_tpl->tpl_vars['specific_price_value']->value;?>
';
<?php } ?>
var specific_currency = <?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['id_currency']){?>true<?php }else{ ?>false<?php }?>;
var group_reduction = '<?php echo $_smarty_tpl->tpl_vars['group_reduction']->value;?>
';
var default_eco_tax = <?php echo $_smarty_tpl->tpl_vars['product']->value->ecotax;?>
;
var ecotaxTax_rate = <?php echo $_smarty_tpl->tpl_vars['ecotaxTax_rate']->value;?>
;
var currentDate = '<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d %H:%M:%S');?>
';
var maxQuantityToAllowDisplayOfLastQuantityMessage = <?php echo $_smarty_tpl->tpl_vars['last_qties']->value;?>
;
var noTaxForThisProduct = <?php if ($_smarty_tpl->tpl_vars['no_tax']->value==1){?>true<?php }else{ ?>false<?php }?>;
var displayPrice = <?php echo $_smarty_tpl->tpl_vars['priceDisplay']->value;?>
;
var productReference = '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->reference, 'htmlall', 'UTF-8');?>
';
var productAvailableForOrder = <?php if ((isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&$_smarty_tpl->tpl_vars['restricted_country_mode']->value)||$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>'0'<?php }else{ ?>'<?php echo $_smarty_tpl->tpl_vars['product']->value->available_for_order;?>
'<?php }?>;
var productShowPrice = '<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?><?php echo $_smarty_tpl->tpl_vars['product']->value->show_price;?>
<?php }else{ ?>0<?php }?>';
var productUnitPriceRatio = '<?php echo $_smarty_tpl->tpl_vars['product']->value->unit_price_ratio;?>
';
var idDefaultImage = <?php if (isset($_smarty_tpl->tpl_vars['cover']->value['id_image_only'])){?><?php echo $_smarty_tpl->tpl_vars['cover']->value['id_image_only'];?>
<?php }else{ ?>0<?php }?>;
var stock_management = <?php echo intval($_smarty_tpl->tpl_vars['stock_management']->value);?>
;
<?php if (!isset($_smarty_tpl->tpl_vars['priceDisplayPrecision']->value)){?>
<?php $_smarty_tpl->tpl_vars['priceDisplayPrecision'] = new Smarty_variable(2, null, 0);?>
<?php }?>
<?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value||$_smarty_tpl->tpl_vars['priceDisplay']->value==2){?>
<?php $_smarty_tpl->tpl_vars['productPrice'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPrice(true,@constant('NULL'),$_smarty_tpl->tpl_vars['priceDisplayPrecision']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['productPriceWithoutReduction'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPriceWithoutReduct(false,@constant('NULL')), null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['priceDisplay']->value==1){?>
<?php $_smarty_tpl->tpl_vars['productPrice'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPrice(false,@constant('NULL'),$_smarty_tpl->tpl_vars['priceDisplayPrecision']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['productPriceWithoutReduction'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPriceWithoutReduct(true,@constant('NULL')), null, 0);?>
<?php }?>


var productPriceWithoutReduction = '<?php echo $_smarty_tpl->tpl_vars['productPriceWithoutReduction']->value;?>
';
var productPrice = '<?php echo $_smarty_tpl->tpl_vars['productPrice']->value;?>
';

// Customizable field
var img_ps_dir = '<?php echo $_smarty_tpl->tpl_vars['img_ps_dir']->value;?>
';
var customizationFields = new Array();
<?php $_smarty_tpl->tpl_vars['imgIndex'] = new Smarty_variable(0, null, 0);?>
<?php $_smarty_tpl->tpl_vars['textFieldIndex'] = new Smarty_variable(0, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['customizationFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']++;
?>
<?php $_smarty_tpl->tpl_vars["key"] = new Smarty_variable("pictures_".((string)$_smarty_tpl->tpl_vars['product']->value->id)."_".((string)$_smarty_tpl->tpl_vars['field']->value['id_customization_field']), null, 0);?>
customizationFields[<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['customizationFields']['index']);?>
] = new Array();
customizationFields[<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['customizationFields']['index']);?>
][0] = '<?php if (intval($_smarty_tpl->tpl_vars['field']->value['type'])==0){?>img<?php echo $_smarty_tpl->tpl_vars['imgIndex']->value++;?>
<?php }else{ ?>textField<?php echo $_smarty_tpl->tpl_vars['textFieldIndex']->value++;?>
<?php }?>';
customizationFields[<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['customizationFields']['index']);?>
][1] = <?php if (intval($_smarty_tpl->tpl_vars['field']->value['type'])==0&&isset($_smarty_tpl->tpl_vars['pictures']->value[$_smarty_tpl->tpl_vars['key']->value])&&$_smarty_tpl->tpl_vars['pictures']->value[$_smarty_tpl->tpl_vars['key']->value]){?>2<?php }else{ ?><?php echo intval($_smarty_tpl->tpl_vars['field']->value['required']);?>
<?php }?>;
<?php } ?>

// Images
var img_prod_dir = '<?php echo $_smarty_tpl->tpl_vars['img_prod_dir']->value;?>
';
var combinationImages = new Array();

<?php if (isset($_smarty_tpl->tpl_vars['combinationImages']->value)){?>
<?php  $_smarty_tpl->tpl_vars['combination'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['combination']->_loop = false;
 $_smarty_tpl->tpl_vars['combinationId'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['combinationImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['combination']->key => $_smarty_tpl->tpl_vars['combination']->value){
$_smarty_tpl->tpl_vars['combination']->_loop = true;
 $_smarty_tpl->tpl_vars['combinationId']->value = $_smarty_tpl->tpl_vars['combination']->key;
?>
combinationImages[<?php echo $_smarty_tpl->tpl_vars['combinationId']->value;?>
] = new Array();
<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['combination']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['f_combinationImage']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['f_combinationImage']['index']++;
?>
combinationImages[<?php echo $_smarty_tpl->tpl_vars['combinationId']->value;?>
][<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['f_combinationImage']['index'];?>
] = <?php echo intval($_smarty_tpl->tpl_vars['image']->value['id_image']);?>
;
<?php } ?>
<?php } ?>
<?php }?>

combinationImages[0] = new Array();
<?php if (isset($_smarty_tpl->tpl_vars['images']->value)){?>
<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['f_defaultImages']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['f_defaultImages']['index']++;
?>
combinationImages[0][<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['f_defaultImages']['index'];?>
] = <?php echo $_smarty_tpl->tpl_vars['image']->value['id_image'];?>
;
<?php } ?>
<?php }?>

// Translations
var doesntExist = '<?php echo smartyTranslate(array('s'=>'This combination does not exist for this product. Please select another combination.','js'=>1),$_smarty_tpl);?>
';
var doesntExistNoMore = '<?php echo smartyTranslate(array('s'=>'This product is no longer in stock','js'=>1),$_smarty_tpl);?>
';
var doesntExistNoMoreBut = '<?php echo smartyTranslate(array('s'=>'with those attributes but is available with others.','js'=>1),$_smarty_tpl);?>
';
var uploading_in_progress = '<?php echo smartyTranslate(array('s'=>'Uploading in progress, please be patient.','js'=>1),$_smarty_tpl);?>
';
var fieldRequired = '<?php echo smartyTranslate(array('s'=>'Please fill in all the required fields before saving your customization.','js'=>1),$_smarty_tpl);?>
';

<?php if (isset($_smarty_tpl->tpl_vars['groups']->value)){?>
	// Combinations
	<?php  $_smarty_tpl->tpl_vars['combination'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['combination']->_loop = false;
 $_smarty_tpl->tpl_vars['idCombination'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['combinations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['combination']->key => $_smarty_tpl->tpl_vars['combination']->value){
$_smarty_tpl->tpl_vars['combination']->_loop = true;
 $_smarty_tpl->tpl_vars['idCombination']->value = $_smarty_tpl->tpl_vars['combination']->key;
?>
	var specific_price_combination = new Array();
	var available_date = new Array();
	specific_price_combination['reduction_percent'] = <?php if ($_smarty_tpl->tpl_vars['combination']->value['specific_price']&&$_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction']&&$_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction_type']=='percentage'){?><?php echo $_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction']*100;?>
<?php }else{ ?>0<?php }?>;
	specific_price_combination['reduction_price'] = <?php if ($_smarty_tpl->tpl_vars['combination']->value['specific_price']&&$_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction']&&$_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction_type']=='amount'){?><?php echo $_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction'];?>
<?php }else{ ?>0<?php }?>;
	specific_price_combination['price'] = <?php if ($_smarty_tpl->tpl_vars['combination']->value['specific_price']&&$_smarty_tpl->tpl_vars['combination']->value['specific_price']['price']){?><?php echo $_smarty_tpl->tpl_vars['combination']->value['specific_price']['price'];?>
<?php }else{ ?>0<?php }?>;
	specific_price_combination['reduction_type'] = '<?php if ($_smarty_tpl->tpl_vars['combination']->value['specific_price']){?><?php echo $_smarty_tpl->tpl_vars['combination']->value['specific_price']['reduction_type'];?>
<?php }?>';
	specific_price_combination['id_product_attribute'] = <?php if ($_smarty_tpl->tpl_vars['combination']->value['specific_price']){?><?php echo intval($_smarty_tpl->tpl_vars['combination']->value['specific_price']['id_product_attribute']);?>
<?php }else{ ?>0<?php }?>;
	available_date['date'] = '<?php echo $_smarty_tpl->tpl_vars['combination']->value['available_date'];?>
';
	available_date['date_formatted'] = '<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0][0]->dateFormat(array('date'=>$_smarty_tpl->tpl_vars['combination']->value['available_date'],'full'=>false),$_smarty_tpl);?>
';
	addCombination(<?php echo intval($_smarty_tpl->tpl_vars['idCombination']->value);?>
, new Array(<?php echo $_smarty_tpl->tpl_vars['combination']->value['list'];?>
), <?php echo $_smarty_tpl->tpl_vars['combination']->value['quantity'];?>
, <?php echo $_smarty_tpl->tpl_vars['combination']->value['price'];?>
, <?php echo $_smarty_tpl->tpl_vars['combination']->value['ecotax'];?>
, <?php echo $_smarty_tpl->tpl_vars['combination']->value['id_image'];?>
, '<?php echo addslashes($_smarty_tpl->tpl_vars['combination']->value['reference']);?>
', <?php echo $_smarty_tpl->tpl_vars['combination']->value['unit_impact'];?>
, <?php echo $_smarty_tpl->tpl_vars['combination']->value['minimal_quantity'];?>
, available_date, specific_price_combination);
	<?php } ?>
	<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['attributesCombinations']->value)){?>
	// Combinations attributes informations
	var attributesCombinations = new Array();
	<?php  $_smarty_tpl->tpl_vars['aC'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aC']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['attributesCombinations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aC']->key => $_smarty_tpl->tpl_vars['aC']->value){
$_smarty_tpl->tpl_vars['aC']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['aC']->key;
?>
	tabInfos = new Array();
	tabInfos['id_attribute'] = '<?php echo intval($_smarty_tpl->tpl_vars['aC']->value['id_attribute']);?>
';
	tabInfos['attribute'] = '<?php echo $_smarty_tpl->tpl_vars['aC']->value['attribute'];?>
';
	tabInfos['group'] = '<?php echo $_smarty_tpl->tpl_vars['aC']->value['group'];?>
';
	tabInfos['id_attribute_group'] = '<?php echo intval($_smarty_tpl->tpl_vars['aC']->value['id_attribute_group']);?>
';
	attributesCombinations.push(tabInfos);
	<?php } ?>
	<?php }?>
	$(window).load(function(){
			//	Responsive layout, resizing the items
			$('#thumbs_list_frame').carouFredSel({
				responsive: true,
				width: '70%',
				height : 'variable',
				prev: '#prev-thumnail',
				next: '#next-thumnail',
				auto: false,
				swipe: {
					onTouch : true
				},
				items: {
					width: 90,
					visible: {
						min: 2,
						max: 3
					}
				},
				scroll: {
					
					items : 3 ,       //  The number of items scrolled.
					direction : 'left',    //  The direction of the transition.
					duration  : 500   //  The duration of the transition.
				}
			});
		});
	$(document).ready(function() {
		cs_resize_tab();
		$('div.title_hide_show').first().addClass('selected');
		$('#more_info_sheets').on('click', '.title_hide_show', function() {
			$(this).next().toggle();
			if($(this).next().css('display') == 'block'){
				$(this).addClass('selected');
			}else{
				$(this).removeClass('selected');
			}
			return false;
		}).next().hide();
	});
	$(window).resize(function() {
		cs_resize_tab();
	});
	function isMobile() {
		if( navigator.userAgent.match(/Android/i) ||
			navigator.userAgent.match(/webOS/i) ||
			navigator.userAgent.match(/iPad/i) ||
			navigator.userAgent.match(/iPhone/i) ||
			navigator.userAgent.match(/iPod/i)
			){
			return true;
	}
	return false;
}
function cs_resize_tab()	{
	if(!isMobile())
	{
		$('.content_hide_show').removeAttr( 'style' );
	}
	if(getWidthBrowser() < 767){
		$('ul#more_info_tabs').hide();
		$('div.title_hide_show').show();
	} else {
		$('div.title_hide_show').hide();
		$('ul#more_info_tabs').show();
	}
}
$('.cart_quantity_up').unbind('click').live('click', function(){
	var qty_now=$("#quantity_wanted").val();
	var qty_new=parseInt(qty_now)+1;
	$("#quantity_wanted").val(qty_new);
});
$('.cart_quantity_down').unbind('click').live('click', function(){
	var qty_now=$("#quantity_wanted").val();
	if(parseInt(qty_now)>1)
	{
		var qty_new=parseInt(qty_now)-1;
		$("#quantity_wanted").val(qty_new);
	}
});
//]]>
</script>

<script>
$(document).ready(function(){
var nuevosElementos = $("<div ><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->name, 'htmlall', 'UTF-8');?>
.</div>");
nuevosElementos.appendTo("#tituloCategoryProd");
});
</script>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div id="primary_block" class="clearfix">

	<?php if (isset($_smarty_tpl->tpl_vars['adminActionDisplay']->value)&&$_smarty_tpl->tpl_vars['adminActionDisplay']->value){?>
	<div id="admin-action">
		<p><?php echo smartyTranslate(array('s'=>'This product is not visible to your customers.'),$_smarty_tpl);?>

			<input type="hidden" id="admin-action-product-id" value="<?php echo $_smarty_tpl->tpl_vars['product']->value->id;?>
" />
			<input type="submit" value="<?php echo smartyTranslate(array('s'=>'Publish'),$_smarty_tpl);?>
" class="exclusive" onclick="submitPublishProduct('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
<?php echo smarty_modifier_escape($_GET['ad'], 'htmlall', 'UTF-8');?>
', 0, '<?php echo smarty_modifier_escape($_GET['adtoken'], 'htmlall', 'UTF-8');?>
')"/>
			<input type="submit" value="<?php echo smartyTranslate(array('s'=>'Back'),$_smarty_tpl);?>
" class="exclusive" onclick="submitPublishProduct('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
<?php echo smarty_modifier_escape($_GET['ad'], 'htmlall', 'UTF-8');?>
', 1, '<?php echo smarty_modifier_escape($_GET['adtoken'], 'htmlall', 'UTF-8');?>
')"/>
		</p>
		<p id="admin-action-result"></p>
	</p>
</div>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['confirmation']->value)&&$_smarty_tpl->tpl_vars['confirmation']->value){?>
<p class="confirmation">
	<?php echo $_smarty_tpl->tpl_vars['confirmation']->value;?>

</p>
<?php }?>
<!-- right infos-->
<div id="pb-right-column">
	<!-- product img-->
	<div id="image-block"   style="background: url('<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
Recuadro-principal-producto.png');background-repeat: no-repeat;-webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;">
	<?php if ($_smarty_tpl->tpl_vars['have_image']->value){?>
	<span id="view_full_size" >
		<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value->link_rewrite,$_smarty_tpl->tpl_vars['cover']->value['id_image'],'large_default');?>
" <?php if ($_smarty_tpl->tpl_vars['jqZoomEnabled']->value){?>class="jqzoom" alt="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value->link_rewrite,$_smarty_tpl->tpl_vars['cover']->value['id_image'],'thickbox_default');?>
"<?php }else{ ?> title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->name, 'htmlall', 'UTF-8');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->name, 'htmlall', 'UTF-8');?>
" <?php }?> id="bigpic"/>
		<span class="span_link"><?php echo smartyTranslate(array('s'=>'View full size'),$_smarty_tpl);?>
</span>
	</span>

	<?php }else{ ?>
	<span id="view_full_size">
		<img src="<?php echo $_smarty_tpl->tpl_vars['img_prod_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
-default-large_default.jpg" id="bigpic" alt="" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->name, 'htmlall', 'UTF-8');?>
"/>
		<span class="span_link"><?php echo smartyTranslate(array('s'=>'View full size'),$_smarty_tpl);?>
</span>
	</span>
	<?php }?>

</div>

<div id="logoPrecio">
	<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/ProductPage Aprobada Codigo Barras Oculto copia-10.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="100%" height="100%" />
</div>
<?php if (isset($_smarty_tpl->tpl_vars['isformula']->value)&&$_smarty_tpl->tpl_vars['isformula']->value){?>



	<div id="formula_medica">
	<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/ProductPage Aprobada Codigo Barras Oculto copia-08.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="100%" height="100%" />
	</div>
	<?php }else{ ?>
	<div id="formula_medica">
	<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/formulablanco.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="100%" height="100%" />
	</div>
    <?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['images']->value)&&count($_smarty_tpl->tpl_vars['images']->value)>0){?>
<!-- thumbnails -->
<!-- thumbnails -->
<?php if (isset($_smarty_tpl->tpl_vars['images']->value)&&count($_smarty_tpl->tpl_vars['images']->value)<2){?>
    <div id="views_block" class="clearfix ">

	<div id="thumbs_list">
		<ul id="thumbs_list_frame">
			<?php if (isset($_smarty_tpl->tpl_vars['images']->value)){?>
			<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['image']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['image']->index++;
 $_smarty_tpl->tpl_vars['image']->first = $_smarty_tpl->tpl_vars['image']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['thumbnails']['first'] = $_smarty_tpl->tpl_vars['image']->first;
?>
			<?php $_smarty_tpl->tpl_vars['imageIds'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['product']->value->id)."-".((string)$_smarty_tpl->tpl_vars['image']->value['id_image']), null, 0);?>
			<li id="thumbnail_<?php echo $_smarty_tpl->tpl_vars['image']->value['id_image'];?>
" style="margin: 27px 7px 0px;width: 70px;height: 59px;
														
														">
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value->link_rewrite,$_smarty_tpl->tpl_vars['imageIds']->value,'thickbox_default');?>
" rel="other-views" class="thickbox <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['thumbnails']['first']){?>shown<?php }?>" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['image']->value['legend']);?>
">
				<img id="thumb_<?php echo $_smarty_tpl->tpl_vars['image']->value['id_image'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mini_prod.jpg" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['image']->value['legend']);?>
" style="border-radius: 3px;height: 38px;margin-top: 0px;width: 50%;"/>
			</a>
		</li>
		<?php } ?>
		<?php }?>
	</ul>
	<a id="prev-thumnail" class="btn prev" href="#">&lt;</a>
	<a id="next-thumnail" class="btn next" href="#">&gt;</a>
</div>

</div> 

        <?php }else{ ?>
            
            <div id="views_block" class="clearfix <?php if (isset($_smarty_tpl->tpl_vars['images']->value)&&count($_smarty_tpl->tpl_vars['images']->value)<2){?>hidden<?php }?>">

	<div id="thumbs_list">
		<ul id="thumbs_list_frame">
			<?php if (isset($_smarty_tpl->tpl_vars['images']->value)){?>
			<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['image']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['image']->index++;
 $_smarty_tpl->tpl_vars['image']->first = $_smarty_tpl->tpl_vars['image']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['thumbnails']['first'] = $_smarty_tpl->tpl_vars['image']->first;
?>
			<?php $_smarty_tpl->tpl_vars['imageIds'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['product']->value->id)."-".((string)$_smarty_tpl->tpl_vars['image']->value['id_image']), null, 0);?>
			<li id="thumbnail_<?php echo $_smarty_tpl->tpl_vars['image']->value['id_image'];?>
" style="width: 125px!important;">
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value->link_rewrite,$_smarty_tpl->tpl_vars['imageIds']->value,'thickbox_default');?>
" rel="other-views" class="thickbox <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['thumbnails']['first']){?>shown<?php }?>" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['image']->value['legend']);?>
" style="height:68px;">
				
	<div style="background: url('<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
escritorii.png');  background-repeat: no-repeat top center;
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;padding: 0px 24px;height: 58px;width: 60px;">
		<img id="thumb_<?php echo $_smarty_tpl->tpl_vars['image']->value['id_image'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value->link_rewrite,$_smarty_tpl->tpl_vars['imageIds']->value,'medium_default');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['image']->value['legend']);?>
" style="border-radius: 3px;height: 56px;margin-top: 0px;width: 98%;"/></div>
			</a>
		</li>
		<?php } ?>
		<?php }?>
	</ul>
	<a id="prev-thumnail" class="btn prev" href="#">&lt;</a>
	<a id="next-thumnail" class="btn next" href="#">&gt;</a>
</div>

</div>
      <!-- cfffff -->      
           
            
            
        <?php }?>

<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['images']->value)&&count($_smarty_tpl->tpl_vars['images']->value)>1){?><p class="resetimg clear"><span id="wrapResetImages" style="display: none;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/i_display.png" alt="<?php echo smartyTranslate(array('s'=>'Cancel'),$_smarty_tpl);?>
" width="24" height="18"/> <a id="resetImages" href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['product']->value);?>
" onclick="$('span#wrapResetImages').hide('slow');return (false);"><?php echo smartyTranslate(array('s'=>'Display all pictures'),$_smarty_tpl);?>
</a></span></p><?php }?>

</div>
<div id="pb-left-column">

    <div id="cabeceraTitulo">

	<!--contenedor logo Fabricante-->
	<div id="fabricante" >
		<?php if ($_smarty_tpl->tpl_vars['url_manufacturer']->value!=''&&$_smarty_tpl->tpl_vars['url_manufacturer']->value!=0){?>
		<a href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['url_manufacturer']->value;?>
">
		<?php }?>	

		<img style="width: 100px;height: 45px;" src="<?php echo $_smarty_tpl->tpl_vars['img_manufacturer']->value;?>
">

		<?php if ($_smarty_tpl->tpl_vars['url_manufacturer']->value!=''&&$_smarty_tpl->tpl_vars['url_manufacturer']->value!=0){?>
		</a>
		<?php }?>	
	</div>

	<!--fin contenedor logo Fabricante-->
		<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/linea-lab-product.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" style=""/>
	<!---titulo producto-->
	<div id="tituloProducto">
		<h1 id="titulo_producto"><?php echo smarty_modifier_capitalize(mb_strtolower(smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->name, 'htmlall', 'UTF-8'), 'UTF-8'));?>
</h1>
	</div>
	<!--fin titulo producto-->



</div>
	<!--contenedor gris de referencia y cantidad-->
								<div id="product_reference" <?php if (isset($_smarty_tpl->tpl_vars['groups']->value)||!$_smarty_tpl->tpl_vars['product']->value->reference){?>style="display: none;"<?php }?>>

									<label id="cantidad"><?php echo smartyTranslate(array('s'=>'Quantity:'),$_smarty_tpl);?>
</label>
									<input type="text" name="qty" id="quantity_wanted" class="text" value="<?php if (isset($_smarty_tpl->tpl_vars['quantityBackup']->value)){?><?php echo intval($_smarty_tpl->tpl_vars['quantityBackup']->value);?>
<?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['product']->value->minimal_quantity>1){?><?php echo $_smarty_tpl->tpl_vars['product']->value->minimal_quantity;?>
<?php }else{ ?>1<?php }?><?php }?>" size="2" maxlength="3" <?php if ($_smarty_tpl->tpl_vars['product']->value->minimal_quantity>1){?>onkeyup="checkMinimalQuantity(<?php echo $_smarty_tpl->tpl_vars['product']->value->minimal_quantity;?>
);"<?php }?> />

									<span class="cs_cart_quantity">
										<a rel="nofollow" class="cart_quantity_up" id="" href="javascript:void(0)" title="<?php echo smartyTranslate(array('s'=>'Add'),$_smarty_tpl);?>
">
											<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/quantity_up.gif" alt="<?php echo smartyTranslate(array('s'=>'Add'),$_smarty_tpl);?>
" width="10" height="10" /></a>
											<a rel="nofollow" class="cart_quantity_down" id="" href="javascript:void(0)" title="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
">
												<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/quantity_down.gif" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="10" height="10" />
											</a>
										</p>
									</div>



										<div id="disponibilidad">
											<?php if ($_smarty_tpl->tpl_vars['product']->value->quantity<=0){?><span class="outofstock" style="display:none;"><?php echo smartyTranslate(array('s'=>'Agotado'),$_smarty_tpl);?>
</span><?php }else{ ?><?php }?><!-- number of item in stock -->
											<?php if (($_smarty_tpl->tpl_vars['display_qties']->value==1&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value&&$_smarty_tpl->tpl_vars['product']->value->available_for_order)){?>
											<?php }?><p class="category_name" id="dispo3">
											<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['category']->value->name, 'htmlall', 'UTF-8');?>

										</p>
										<?php if ($_smarty_tpl->tpl_vars['product']->value->description_short){?>
										<div id="short_description_content" class="rte align_justify"><?php echo $_smarty_tpl->tpl_vars['product']->value->description_short;?>
</div>
										<?php }?>




									</div>
									<?php if (isset($_smarty_tpl->tpl_vars['isformula']->value)&&$_smarty_tpl->tpl_vars['isformula']->value){?>
										<div id="formulaMedica1"><img id="formulaMedica1" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/ProductPage Aprobada Codigo Barras Oculto copia-09.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" /><span id="labelFormula">Este medicamento sólo se vende con prescripción médica. NO SE AUTOMEDIQUE.</span></div>
										<?php }else{ ?>
										<div id="formulaMedica1"><img id="formulaMedica1" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/formulablanco.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" style="height: 68px;width: 69px;"/><span id="labelFormula">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
                            <?php }?>
											<div id="compraSegura"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/linea2.png" id="linea2">Compra Segura<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/producto/linea.png" id="linea"></div>

										<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/medios-pago.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" id="mediosPagos"/>



										<div class="cs_price price" id="price">
											<?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value||$_smarty_tpl->tpl_vars['priceDisplay']->value==2){?>
											<?php $_smarty_tpl->tpl_vars['productPrice'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPrice(true,@constant('NULL'),$_smarty_tpl->tpl_vars['priceDisplayPrecision']->value), null, 0);?>
											<?php $_smarty_tpl->tpl_vars['productPriceWithoutReduction'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPriceWithoutReduct(false,@constant('NULL')), null, 0);?>
											<?php }elseif($_smarty_tpl->tpl_vars['priceDisplay']->value==1){?>
											<?php $_smarty_tpl->tpl_vars['productPrice'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPrice(false,@constant('NULL'),$_smarty_tpl->tpl_vars['priceDisplayPrecision']->value), null, 0);?>
											<?php $_smarty_tpl->tpl_vars['productPriceWithoutReduction'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getPriceWithoutReduct(true,@constant('NULL')), null, 0);?>
											<?php }?>

											<div id="centrarPrecio">

											<div class="our_price_display" style="margin-left: 19px;">
												<label id="labelPrecio">Precio</label>

												<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value>=0&&$_smarty_tpl->tpl_vars['priceDisplay']->value<=2){?>
												<span id="our_price_display" style="word-spacing: -9px;"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['productPrice']->value),$_smarty_tpl);?>
</span>
					<!--<?php if ($_smarty_tpl->tpl_vars['tax_enabled']->value&&((isset($_smarty_tpl->tpl_vars['display_tax_label']->value)&&$_smarty_tpl->tpl_vars['display_tax_label']->value==1)||!isset($_smarty_tpl->tpl_vars['display_tax_label']->value))){?>
						<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value==1){?><?php echo smartyTranslate(array('s'=>'tax excl.'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'tax incl.'),$_smarty_tpl);?>
<?php }?>
						<?php }?>-->
						<?php }?>
					</div>
					</div>

					<?php if ($_smarty_tpl->tpl_vars['product']->value->on_sale){?>
					<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
onsale_<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
.gif" alt="<?php echo smartyTranslate(array('s'=>'On sale'),$_smarty_tpl);?>
" class="on_sale_img"/>
					<span class="on_sale"><?php echo smartyTranslate(array('s'=>'On sale!'),$_smarty_tpl);?>
</span>
					<?php }elseif($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']&&$_smarty_tpl->tpl_vars['productPriceWithoutReduction']->value>$_smarty_tpl->tpl_vars['productPrice']->value){?>
					<span class="discount"><?php echo smartyTranslate(array('s'=>'Reduced price!'),$_smarty_tpl);?>
</span>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value==2){?>
					<br />
					<span id="pretaxe_price"><span id="pretaxe_price_display"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value->getPrice(false,@constant('NULL'))),$_smarty_tpl);?>
</span>&nbsp;<?php echo smartyTranslate(array('s'=>'tax excl.'),$_smarty_tpl);?>
</span>
					<?php }?>
					<p id="reduction_percent" <?php if (!$_smarty_tpl->tpl_vars['product']->value->specificPrice||$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']!='percentage'){?> style="display:none;"<?php }?>><span id="reduction_percent_display"><?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']=='percentage'){?>-<?php echo $_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']*100;?>
%<?php }?></span></p>
					<p id="reduction_amount" <?php if (!$_smarty_tpl->tpl_vars['product']->value->specificPrice||$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']!='amount'&&intval($_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction'])==0){?> style="display:none"<?php }?>><span id="reduction_amount_display"><?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction_type']=='amount'&&intval($_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction'])!=0){?>-<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>floatval($_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction'])),$_smarty_tpl);?>
<?php }?></span></p>
					<?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']){?>
					<p id="old_price"><span class="bold">
						<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value>=0&&$_smarty_tpl->tpl_vars['priceDisplay']->value<=2){?>
						<?php if ($_smarty_tpl->tpl_vars['productPriceWithoutReduction']->value>$_smarty_tpl->tpl_vars['productPrice']->value){?>
						<span id="old_price_display"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['productPriceWithoutReduction']->value),$_smarty_tpl);?>
</span>
						<!-- <?php if ($_smarty_tpl->tpl_vars['tax_enabled']->value&&$_smarty_tpl->tpl_vars['display_tax_label']->value==1){?>
							<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value==1){?><?php echo smartyTranslate(array('s'=>'tax excl.'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'tax incl.'),$_smarty_tpl);?>
<?php }?>
							<?php }?> -->
							<?php }?>
							<?php }?>
						</span>
					</p>
					<?php }?>
					<?php if (count($_smarty_tpl->tpl_vars['packItems']->value)&&$_smarty_tpl->tpl_vars['productPrice']->value<$_smarty_tpl->tpl_vars['product']->value->getNoPackPrice()){?>
					<p class="pack_price"><?php echo smartyTranslate(array('s'=>'instead of'),$_smarty_tpl);?>
 <span style="text-decoration: line-through;"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value->getNoPackPrice()),$_smarty_tpl);?>
</span></p>
					<br class="clear" />
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['product']->value->ecotax!=0){?>
					<p class="price-ecotax"><?php echo smartyTranslate(array('s'=>'include'),$_smarty_tpl);?>
 <span id="ecotax_price_display"><?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value==2){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['convertAndFormatPrice'][0][0]->convertAndFormatPrice($_smarty_tpl->tpl_vars['ecotax_tax_exc']->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['convertAndFormatPrice'][0][0]->convertAndFormatPrice($_smarty_tpl->tpl_vars['ecotax_tax_inc']->value);?>
<?php }?></span> <?php echo smartyTranslate(array('s'=>'for green tax'),$_smarty_tpl);?>

						<?php if ($_smarty_tpl->tpl_vars['product']->value->specificPrice&&$_smarty_tpl->tpl_vars['product']->value->specificPrice['reduction']){?>
						<br /><?php echo smartyTranslate(array('s'=>'(not impacted by the discount)'),$_smarty_tpl);?>

						<?php }?>
					</p>
					<?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['product']->value->unity)&&$_smarty_tpl->tpl_vars['product']->value->unit_price_ratio>0.000000){?>
					<?php echo smarty_function_math(array('equation'=>"pprice / punit_price",'pprice'=>$_smarty_tpl->tpl_vars['productPrice']->value,'punit_price'=>$_smarty_tpl->tpl_vars['product']->value->unit_price_ratio,'assign'=>'unit_price'),$_smarty_tpl);?>

					<p class="unit-price"><span id="unit_price_display"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['unit_price']->value),$_smarty_tpl);?>
</span> <?php echo smartyTranslate(array('s'=>'per'),$_smarty_tpl);?>
 <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value->unity, 'htmlall', 'UTF-8');?>
</p>
					<?php }?>
					
					<?php }?>
					<?php if ((!$_smarty_tpl->tpl_vars['allow_oosp']->value&&$_smarty_tpl->tpl_vars['product']->value->quantity<=0)||!$_smarty_tpl->tpl_vars['product']->value->available_for_order||(isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&$_smarty_tpl->tpl_vars['restricted_country_mode']->value)||$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
				<span class="exclusive" style="display:none;">
					<span></span>
					<?php echo smartyTranslate(array('s'=>'Agregar al Carrito'),$_smarty_tpl);?>

				</span>
				<?php }else{ ?>
				<p id="add_to_cart" class="buttons_bottom_block" >
					<input type="submit" id="btnComprar" name="Submit" value="<?php echo smartyTranslate(array('s'=>'COMPRAR'),$_smarty_tpl);?>
" class="exclusive" />
				</p>
				<?php }?>
				<div id="conteDescuento">
					<span style="color: #8D2F2B;font-weight: 600!important;font-size: 11px!important;font-family: 'Open Sans', sans-serif!important;"><!-- Valor restante para envío gratuito -->
					<?php if (($_smarty_tpl->tpl_vars['cart_qties']->value>0&&$_smarty_tpl->tpl_vars['valor_restante']->value!=0)||($_smarty_tpl->tpl_vars['cart_qties']->value==0&&$_smarty_tpl->tpl_vars['valor_restante']->value!=0)){?>
					Tu env&#237;o gratis por compras superiores a <br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['valor_restante']->value),$_smarty_tpl);?>
 en toda Colombia.
					<?php }elseif($_smarty_tpl->tpl_vars['cart_qties']->value>0&&$_smarty_tpl->tpl_vars['valor_restante']->value==0){?>
					Tu env&iacute;o es gratuito!.
					<?php }?></span>
				</div>

										<div id="elementos_redes">
										<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
barra-arriba.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" id="mediosPagos" style="width: 213px;margin-top: 10px;"/>
										<div class="itemGooglePlusOneButton">
											<div class="g-plusone" data-size="medium"></div>

											<!-- Place this tag after the last +1 button tag. -->
											<script type="text/javascript">
											(function() {
												var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
												po.src = 'https://apis.google.com/js/plusone.js';
												var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
											})();
											</script>
										</div>

										<div class="itemFacebookButton" id="caraLibro">
											<div id="fb-root"></div>
											<script type="text/javascript">
											(function(d, s, id) {
												var js, fjs = d.getElementsByTagName(s)[0];
												if (d.getElementById(id)) return;
												js = d.createElement(s); js.id = id;
												js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
												fjs.parentNode.insertBefore(js, fjs);
											}(document, 'script', 'facebook-jssdk'));
											</script>
											<div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="true"></div>
										</div>

											<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
											<img style="width: 213px;margin-top: -16px;" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
barra-abajo.png" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" id="mediosPagos"/>
										</div>	
				</div>	

									<!--<div id="redes_sociales" > 
										<a href="http://www.linkedin.com/company/farmalisto" target="_blank" title="LinkedIn"><div alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" style="border:0px !important;" id="in"/></div></a>	
										
										<a href="https://plus.google.com/+FarmalistoColombia/posts" target="_blank" title="Google +"><div  alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" id="goog"/></div></a>

										<a href="https://twitter.com/farmalistocol" target="_blank" title="Twitter"><div alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" id="twit"/></div></a>

										<a href="https://www.facebook.com/farmalistocolombia" target="_blank" title="Facebook Oficial"><div alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
"  id="face"/></div></a>

									</div>-->

										

										

								

										
									</div>
									<!--fin contenedor gris referencia y cantidad-->
																			<?php if ($_smarty_tpl->tpl_vars['product']->value->description_short||count($_smarty_tpl->tpl_vars['packItems']->value)>0){?>
										<div id="short_description_block">

											<?php if ($_smarty_tpl->tpl_vars['product']->value->description){?>
											<p class="buttons_bottom_block" style="display:none;" ><a href="javascript:{}" class="button"><?php echo smartyTranslate(array('s'=>'More details'),$_smarty_tpl);?>
</a></p>
											<?php }?>
											<?php if (count($_smarty_tpl->tpl_vars['packItems']->value)>0){?>
											<div class="short_description_pack">
												<h3><?php echo smartyTranslate(array('s'=>'Pack content'),$_smarty_tpl);?>
</h3>
												<?php  $_smarty_tpl->tpl_vars['packItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['packItem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['packItems']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['packItem']->key => $_smarty_tpl->tpl_vars['packItem']->value){
$_smarty_tpl->tpl_vars['packItem']->_loop = true;
?>
												<div class="pack_content">
													<?php echo $_smarty_tpl->tpl_vars['packItem']->value['pack_quantity'];?>
 x <a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['packItem']->value['id_product'],$_smarty_tpl->tpl_vars['packItem']->value['link_rewrite'],$_smarty_tpl->tpl_vars['packItem']->value['category']);?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['packItem']->value['name'], 'htmlall', 'UTF-8');?>
</a>
													<p><?php echo $_smarty_tpl->tpl_vars['packItem']->value['description_short'];?>
</p>
												</div>
												<?php } ?>
											</div>
											<?php }?>
										</div>
										<?php }?>
						</div>

	<!-- Out of stock hook -->
		<p style="display:none;" id="oosHook"<?php if ($_smarty_tpl->tpl_vars['product']->value->quantity>0){?> style="display: none;"<?php }?>>
			<?php echo $_smarty_tpl->tpl_vars['HOOK_PRODUCT_OOS']->value;?>

		</p>
		

		<?php if (($_smarty_tpl->tpl_vars['product']->value->show_price&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value))||isset($_smarty_tpl->tpl_vars['groups']->value)||$_smarty_tpl->tpl_vars['product']->value->reference||(isset($_smarty_tpl->tpl_vars['HOOK_PRODUCT_ACTIONS']->value)&&$_smarty_tpl->tpl_vars['HOOK_PRODUCT_ACTIONS']->value)){?>
		<!-- Agregar al carrito form-->
		<form id="buy_block" <?php if ($_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value&&!isset($_smarty_tpl->tpl_vars['groups']->value)&&$_smarty_tpl->tpl_vars['product']->value->quantity>0){?>class="hidden"<?php }?> action="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart');?>
" method="post">

			<!-- hidden datas -->
			<p class="hidden">
				<input type="hidden" name="token" />
				<input type="hidden" name="id_product" value="<?php echo intval($_smarty_tpl->tpl_vars['product']->value->id);?>
" id="product_page_product_id" />
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
			</p>

			<div class="product_attributes">
				<?php if (isset($_smarty_tpl->tpl_vars['groups']->value)){?>
				<!-- attributes -->
				<div id="attributes">
					<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_smarty_tpl->tpl_vars['id_attribute_group'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['id_attribute_group']->value = $_smarty_tpl->tpl_vars['group']->key;
?>
					<?php if (count($_smarty_tpl->tpl_vars['group']->value['attributes'])){?>
					<fieldset class="attribute_fieldset">
						<label class="attribute_label" for="group_<?php echo intval($_smarty_tpl->tpl_vars['id_attribute_group']->value);?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['group']->value['name'], 'htmlall', 'UTF-8');?>
 :</label>
						<?php $_smarty_tpl->tpl_vars["groupName"] = new Smarty_variable("group_".((string)$_smarty_tpl->tpl_vars['id_attribute_group']->value), null, 0);?>
						<div class="attribute_list">
							<?php if (($_smarty_tpl->tpl_vars['group']->value['group_type']=='select')){?>
							<select name="<?php echo $_smarty_tpl->tpl_vars['groupName']->value;?>
" id="group_<?php echo intval($_smarty_tpl->tpl_vars['id_attribute_group']->value);?>
" class="attribute_select" onchange="findCombination();getProductAttribute();<?php if (count($_smarty_tpl->tpl_vars['colors']->value)>0){?>$('#wrapResetImages').show('slow');<?php }?>;">
								<?php  $_smarty_tpl->tpl_vars['group_attribute'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group_attribute']->_loop = false;
 $_smarty_tpl->tpl_vars['id_attribute'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['group']->value['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group_attribute']->key => $_smarty_tpl->tpl_vars['group_attribute']->value){
$_smarty_tpl->tpl_vars['group_attribute']->_loop = true;
 $_smarty_tpl->tpl_vars['id_attribute']->value = $_smarty_tpl->tpl_vars['group_attribute']->key;
?>
								<option value="<?php echo intval($_smarty_tpl->tpl_vars['id_attribute']->value);?>
"<?php if ((isset($_GET[$_smarty_tpl->tpl_vars['groupName']->value])&&intval($_GET[$_smarty_tpl->tpl_vars['groupName']->value])==$_smarty_tpl->tpl_vars['id_attribute']->value)||$_smarty_tpl->tpl_vars['group']->value['default']==$_smarty_tpl->tpl_vars['id_attribute']->value){?> selected="selected"<?php }?> title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['group_attribute']->value, 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['group_attribute']->value, 'htmlall', 'UTF-8');?>
</option>
								<?php } ?>
							</select>
							<?php }elseif(($_smarty_tpl->tpl_vars['group']->value['group_type']=='color')){?>
							<ul id="color_to_pick_list" class="clearfix">
								<?php $_smarty_tpl->tpl_vars["default_colorpicker"] = new Smarty_variable('', null, 0);?>
								<?php  $_smarty_tpl->tpl_vars['group_attribute'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group_attribute']->_loop = false;
 $_smarty_tpl->tpl_vars['id_attribute'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['group']->value['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group_attribute']->key => $_smarty_tpl->tpl_vars['group_attribute']->value){
$_smarty_tpl->tpl_vars['group_attribute']->_loop = true;
 $_smarty_tpl->tpl_vars['id_attribute']->value = $_smarty_tpl->tpl_vars['group_attribute']->key;
?>
								<li<?php if ($_smarty_tpl->tpl_vars['group']->value['default']==$_smarty_tpl->tpl_vars['id_attribute']->value){?> class="selected"<?php }?>>
								<a id="color_<?php echo intval($_smarty_tpl->tpl_vars['id_attribute']->value);?>
" class="color_pick<?php if (($_smarty_tpl->tpl_vars['group']->value['default']==$_smarty_tpl->tpl_vars['id_attribute']->value)){?> selected<?php }?>" style="background: <?php echo $_smarty_tpl->tpl_vars['colors']->value[$_smarty_tpl->tpl_vars['id_attribute']->value]['value'];?>
;" title="<?php echo $_smarty_tpl->tpl_vars['colors']->value[$_smarty_tpl->tpl_vars['id_attribute']->value]['name'];?>
" onclick="colorPickerClick(this);getProductAttribute();<?php if (count($_smarty_tpl->tpl_vars['colors']->value)>0){?>$('#wrapResetImages').show('slow');<?php }?>">
									<?php if (file_exists((($_smarty_tpl->tpl_vars['col_img_dir']->value).($_smarty_tpl->tpl_vars['id_attribute']->value)).('.jpg'))){?>
									<img src="<?php echo $_smarty_tpl->tpl_vars['img_col_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['id_attribute']->value;?>
.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['colors']->value[$_smarty_tpl->tpl_vars['id_attribute']->value]['name'];?>
" width="20" height="20" /><br>
									<?php }?>
								</a>
							</li>
							<?php if (($_smarty_tpl->tpl_vars['group']->value['default']==$_smarty_tpl->tpl_vars['id_attribute']->value)){?>
							<?php $_smarty_tpl->tpl_vars['default_colorpicker'] = new Smarty_variable($_smarty_tpl->tpl_vars['id_attribute']->value, null, 0);?>
							<?php }?>
							<?php } ?>
						</ul>
						<input type="hidden" class="color_pick_hidden" name="<?php echo $_smarty_tpl->tpl_vars['groupName']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['default_colorpicker']->value;?>
" />
						<?php }elseif(($_smarty_tpl->tpl_vars['group']->value['group_type']=='radio')){?>
						<?php  $_smarty_tpl->tpl_vars['group_attribute'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group_attribute']->_loop = false;
 $_smarty_tpl->tpl_vars['id_attribute'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['group']->value['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group_attribute']->key => $_smarty_tpl->tpl_vars['group_attribute']->value){
$_smarty_tpl->tpl_vars['group_attribute']->_loop = true;
 $_smarty_tpl->tpl_vars['id_attribute']->value = $_smarty_tpl->tpl_vars['group_attribute']->key;
?>
						<input type="radio" class="attribute_radio" name="<?php echo $_smarty_tpl->tpl_vars['groupName']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['id_attribute']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['group']->value['default']==$_smarty_tpl->tpl_vars['id_attribute']->value)){?> checked="checked"<?php }?> onclick="findCombination();getProductAttribute();<?php if (count($_smarty_tpl->tpl_vars['colors']->value)>0){?>$('#wrapResetImages').show('slow');<?php }?>">
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['group_attribute']->value, 'htmlall', 'UTF-8');?>
<br/>
						<?php } ?>
						<?php }?>
					</div>
				</fieldset>
				<?php }?>
				<?php } ?>
			</div>
			<?php }?>
			

			<!-- quantity wanted -->
			

			<!-- minimal quantity wanted -->
			<p id="minimal_quantity_wanted_p" as="as"<?php if ($_smarty_tpl->tpl_vars['product']->value->minimal_quantity<=1||!$_smarty_tpl->tpl_vars['product']->value->available_for_order||$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?> style="display: none;"<?php }?>>
				<?php echo smartyTranslate(array('s'=>'This product is not sold individually. You must select at least'),$_smarty_tpl);?>
 <b id="minimal_quantity_label"><?php echo $_smarty_tpl->tpl_vars['product']->value->minimal_quantity;?>
</b> <?php echo smartyTranslate(array('s'=>'quantity for this product.'),$_smarty_tpl);?>

			</p>
			<?php if ($_smarty_tpl->tpl_vars['product']->value->minimal_quantity>1){?>
			<script type="text/javascript">
			checkMinimalQuantity();
			</script>
			<?php }?>

			<!-- availability -->
			<!--<p id="availability_statut"<?php if (($_smarty_tpl->tpl_vars['product']->value->quantity<=0&&!$_smarty_tpl->tpl_vars['product']->value->available_later&&$_smarty_tpl->tpl_vars['allow_oosp']->value)||($_smarty_tpl->tpl_vars['product']->value->quantity>0&&!$_smarty_tpl->tpl_vars['product']->value->available_now)||!$_smarty_tpl->tpl_vars['product']->value->available_for_order||$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?> style="display: none;"<?php }?>>
				<span id="availability_label"><?php echo smartyTranslate(array('s'=>'Availability:'),$_smarty_tpl);?>
</span>
				<span id="availability_value"<?php if ($_smarty_tpl->tpl_vars['product']->value->quantity<=0){?> class="warning_inline"<?php }?>>
				<?php if ($_smarty_tpl->tpl_vars['product']->value->quantity<=0){?><?php if ($_smarty_tpl->tpl_vars['allow_oosp']->value){?><?php echo $_smarty_tpl->tpl_vars['product']->value->available_later;?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'This product is no longer in stock'),$_smarty_tpl);?>
<?php }?><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['product']->value->available_now;?>
<?php }?>
			</span>-->
		</p>





		<p class="warning_inline" id="last_quantities" style="display:none;"<?php if (($_smarty_tpl->tpl_vars['product']->value->quantity>$_smarty_tpl->tpl_vars['last_qties']->value||$_smarty_tpl->tpl_vars['product']->value->quantity<=0)||$_smarty_tpl->tpl_vars['allow_oosp']->value||!$_smarty_tpl->tpl_vars['product']->value->available_for_order||$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?> style="display: none"<?php }?> ><?php echo smartyTranslate(array('s'=>'Warning: Last items in stock!'),$_smarty_tpl);?>
</p>
	</div>

	<div class="content_prices clearfix">
		<!-- prices -->
		<?php if ($_smarty_tpl->tpl_vars['product']->value->show_price&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>

		<?php if ($_smarty_tpl->tpl_vars['product']->value->online_only){?>
		<p class="online_only"><?php echo smartyTranslate(array('s'=>'Online only'),$_smarty_tpl);?>
</p>
		<?php }?>



		<?php if (isset($_smarty_tpl->tpl_vars['HOOK_PRODUCT_ACTIONS']->value)&&$_smarty_tpl->tpl_vars['HOOK_PRODUCT_ACTIONS']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_PRODUCT_ACTIONS']->value;?>
<?php }?>

		<div class="clear"></div>
	</div>
</form>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['HOOK_EXTRA_RIGHT']->value)&&$_smarty_tpl->tpl_vars['HOOK_EXTRA_RIGHT']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_EXTRA_RIGHT']->value;?>
<?php }?>
</div>
</div>

<?php if ((isset($_smarty_tpl->tpl_vars['quantity_discounts']->value)&&count($_smarty_tpl->tpl_vars['quantity_discounts']->value)>0)){?>
<!-- quantity discount -->
<ul class="idTabs clearfix">
	<li><a href="#discount" style="cursor: pointer" class="selected"><?php echo smartyTranslate(array('s'=>'Quantity discount'),$_smarty_tpl);?>
</a></li>
</ul>
<div id="quantityDiscount">
	<table class="std">
		<thead>
			<tr>
				<th><?php echo smartyTranslate(array('s'=>'product'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'from (qty)'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'discount'),$_smarty_tpl);?>
</th>
			</tr>
		</thead>
		<tbody>
			<?php  $_smarty_tpl->tpl_vars['quantity_discount'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['quantity_discount']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['quantity_discounts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['quantity_discount']->key => $_smarty_tpl->tpl_vars['quantity_discount']->value){
$_smarty_tpl->tpl_vars['quantity_discount']->_loop = true;
?>
			<tr id="quantityDiscount_<?php echo $_smarty_tpl->tpl_vars['quantity_discount']->value['id_product_attribute'];?>
">
				<td>
					<?php if ((isset($_smarty_tpl->tpl_vars['quantity_discount']->value['attributes'])&&($_smarty_tpl->tpl_vars['quantity_discount']->value['attributes']))){?>
					<?php echo $_smarty_tpl->tpl_vars['product']->value->getProductName($_smarty_tpl->tpl_vars['quantity_discount']->value['id_product'],$_smarty_tpl->tpl_vars['quantity_discount']->value['id_product_attribute']);?>

					<?php }else{ ?>
					<?php echo $_smarty_tpl->tpl_vars['product']->value->getProductName($_smarty_tpl->tpl_vars['quantity_discount']->value['id_product']);?>

					<?php }?>
				</td>
				<td><?php echo intval($_smarty_tpl->tpl_vars['quantity_discount']->value['quantity']);?>
</td>
				<td>
					<?php if ($_smarty_tpl->tpl_vars['quantity_discount']->value['price']>=0||$_smarty_tpl->tpl_vars['quantity_discount']->value['reduction_type']=='amount'){?>
					-<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>floatval($_smarty_tpl->tpl_vars['quantity_discount']->value['real_value'])),$_smarty_tpl);?>

					<?php }else{ ?>
					-<?php echo floatval($_smarty_tpl->tpl_vars['quantity_discount']->value['real_value']);?>
%
					<?php }?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php }?>

<!-- description and features -->
<?php if ((isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->description)||(isset($_smarty_tpl->tpl_vars['features']->value)&&$_smarty_tpl->tpl_vars['features']->value)||(isset($_smarty_tpl->tpl_vars['accessories']->value)&&$_smarty_tpl->tpl_vars['accessories']->value)||(isset($_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB']->value)&&$_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB']->value)||(isset($_smarty_tpl->tpl_vars['attachments']->value)&&$_smarty_tpl->tpl_vars['attachments']->value)||isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->customizable){?>
<div id="global" >
<div id="conconImage">
	<div id="more_info_block" class="clear">
		<ul id="more_info_tabs" class="idTabs idTabsShort clearfix">
		<?php if ($_smarty_tpl->tpl_vars['product']->value->description){?><li><a id="more_info_tab_more_info" href="#idTab1" style="text-transform: capitalize;"><?php echo smartyTranslate(array('s'=>'More info'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['features']->value){?><li><a id="more_info_tab_data_sheet" href="#idTab2" style="text-transform: capitalize;"><?php echo smartyTranslate(array('s'=>'Data sheet'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['attachments']->value){?><li><a id="more_info_tab_attachments" href="#idTab9"><?php echo smartyTranslate(array('s'=>'Download'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['accessories']->value)&&$_smarty_tpl->tpl_vars['accessories']->value){?><li><a href="#idTab4"><?php echo smartyTranslate(array('s'=>'Accessories'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->customizable){?><li><a href="#idTab10"><?php echo smartyTranslate(array('s'=>'Product customization'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php echo $_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB']->value;?>

		</ul>

		<div id="more_info_sheets" class="sheets align_justify">

			<?php if ($_smarty_tpl->tpl_vars['product']->value->description){?><div class="title_hide_show" style="text-transform: capitalize;"><?php echo smartyTranslate(array('s'=>'More info'),$_smarty_tpl);?>
</div><?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->description){?>
			<!-- full description -->
			<div id="idTab1" class="rte content_hide_show"><div id="scro"><?php echo $_smarty_tpl->tpl_vars['product']->value->description;?>
</div></div>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['features']->value){?><div class="title_hide_show" style="text-transform: capitalize;"><?php echo smartyTranslate(array('s'=>'Data sheet'),$_smarty_tpl);?>
</div><?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['features']->value)&&$_smarty_tpl->tpl_vars['features']->value){?>
			<!-- product's features -->
			<ul id="idTab2" class="bullet content_hide_show" style="text-transform: capitalize;font-family: 'Open Sans', sans-serif;"><div id="scro">
				<?php  $_smarty_tpl->tpl_vars['feature'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['feature']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['features']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['feature']->key => $_smarty_tpl->tpl_vars['feature']->value){
$_smarty_tpl->tpl_vars['feature']->_loop = true;
?>
				<?php if (isset($_smarty_tpl->tpl_vars['feature']->value['value'])){?>
				<li><span><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['feature']->value['name'], 'htmlall', 'UTF-8');?>
</span> <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['feature']->value['value'], 'htmlall', 'UTF-8');?>
</li>
				<?php }?>
				<?php } ?></div>
			</ul>
			<?php }?>

			<?php if ($_smarty_tpl->tpl_vars['attachments']->value){?><div class="title_hide_show" style="display:none"><?php echo smartyTranslate(array('s'=>'Download'),$_smarty_tpl);?>
</div><?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['attachments']->value)&&$_smarty_tpl->tpl_vars['attachments']->value){?>
			<ul id="idTab9" class="bullet content_hide_show"style="font-family: 'Open Sans', sans-serif;">
				<?php  $_smarty_tpl->tpl_vars['attachment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attachment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attachments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attachment']->key => $_smarty_tpl->tpl_vars['attachment']->value){
$_smarty_tpl->tpl_vars['attachment']->_loop = true;
?>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('attachment',true,null,"id_attachment=".((string)$_smarty_tpl->tpl_vars['attachment']->value['id_attachment']));?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['attachment']->value['name'], 'htmlall', 'UTF-8');?>
</a><br /><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['attachment']->value['description'], 'htmlall', 'UTF-8');?>
</li>
				<?php } ?>
			</ul>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['accessories']->value)&&$_smarty_tpl->tpl_vars['accessories']->value){?><div class="title_hide_show" style="display:none"><?php echo smartyTranslate(array('s'=>'Accessories'),$_smarty_tpl);?>
</div><?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['accessories']->value)&&$_smarty_tpl->tpl_vars['accessories']->value){?>
			<!-- accessories -->
			<ul id="idTab4" class="bullet content_hide_show">
				<div class="block products_block accessories_block clearfix">
					<div class="block_content">
						<ul id="product_list">
							<?php  $_smarty_tpl->tpl_vars['accessory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['accessory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['accessories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['accessory']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['accessory']->iteration=0;
 $_smarty_tpl->tpl_vars['accessory']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['accessory']->key => $_smarty_tpl->tpl_vars['accessory']->value){
$_smarty_tpl->tpl_vars['accessory']->_loop = true;
 $_smarty_tpl->tpl_vars['accessory']->iteration++;
 $_smarty_tpl->tpl_vars['accessory']->index++;
 $_smarty_tpl->tpl_vars['accessory']->first = $_smarty_tpl->tpl_vars['accessory']->index === 0;
 $_smarty_tpl->tpl_vars['accessory']->last = $_smarty_tpl->tpl_vars['accessory']->iteration === $_smarty_tpl->tpl_vars['accessory']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['accessories_list']['first'] = $_smarty_tpl->tpl_vars['accessory']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['accessories_list']['last'] = $_smarty_tpl->tpl_vars['accessory']->last;
?>
							<?php if (($_smarty_tpl->tpl_vars['accessory']->value['allow_oosp']||$_smarty_tpl->tpl_vars['accessory']->value['quantity']>0)&&$_smarty_tpl->tpl_vars['accessory']->value['available_for_order']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)){?>
							<?php $_smarty_tpl->tpl_vars['accessoryLink'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['accessory']->value['id_product'],$_smarty_tpl->tpl_vars['accessory']->value['link_rewrite'],$_smarty_tpl->tpl_vars['accessory']->value['category']), null, 0);?>
							<li class="<?php if (isset($_smarty_tpl->tpl_vars['grid_product']->value)){?><?php echo $_smarty_tpl->tpl_vars['grid_product']->value;?>
<?php }else{ ?>grid_6<?php }?> ajax_block_product <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['accessories_list']['first']){?>first_item<?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['accessories_list']['last']){?>last_item<?php }else{ ?>item<?php }?> product_accessories_description clearfix">
								<div class="center_block">
									<div class="image"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['accessoryLink']->value, 'htmlall', 'UTF-8');?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['accessory']->value['name'], 'htmlall', 'UTF-8');?>
" class="product_img_link"><img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['accessory']->value['link_rewrite'],$_smarty_tpl->tpl_vars['accessory']->value['id_image'],'home_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['accessory']->value['legend'], 'htmlall', 'UTF-8');?>
"/></a></div>
									<div class="name_product"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['accessoryLink']->value, 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['accessory']->value['name'], 'htmlall', 'UTF-8');?>
</a></div>
									<div class="content_price">
										<?php if ($_smarty_tpl->tpl_vars['accessory']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?> <span class="price"><?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value!=1){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['accessory']->value['price']),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['accessory']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?></span><?php }?>
									</div>
									<div class="product_desc">
										<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['accessory']->value['description_short']),90,'...');?>

									</div>
									<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
									<a rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['accessory']->value['id_product']);?>
" class="exclusive button ajax_add_to_cart_button" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['accessory']->value['id_product']);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',true,null,"qty=1&amp;id_product=".$_tmp1."&amp;token=".((string)$_smarty_tpl->tpl_vars['static_token']->value)."&amp;add");?>
" rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['accessory']->value['id_product']);?>
" title="<?php echo smartyTranslate(array('s'=>'Agregar al carrito'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Agregar al carrito'),$_smarty_tpl);?>
</a>
									<?php }?>
								</div>
							</li>
							<?php }?>
							<?php } ?>
						</ul>
					</div>
				</div>
			</ul>
			<?php }?>
		</div>
		
	</div>


		<div id="imagenMarco">
			<div id="marco"></div>
		</div>
		<div id="imagenMarco2">
			<div id="marco"></div>
		</div>
	</div>

	<div id="informac">
		<a href="http://www.farmalisto.com.co/775-para-vacaciones?n=200&utm_source=PDP&utm_medium=BannerPDP&utm_term=ProductDetail&utm_content=Vacaciones&utm_campaign=0011_08042014" target="_blank"><img id="imgge" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
zonaPublicitaria/pdp.jpg" style="width: 240px;height: 232px;" alt="Los productos para tus vacaciones"/></a>
		

		
</div>



		<!-- Customizable products -->
		<?php if (isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->customizable){?><div class="title_hide_show" style="display:none"><?php echo smartyTranslate(array('s'=>'Product customization'),$_smarty_tpl);?>
</div><?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['product']->value)&&$_smarty_tpl->tpl_vars['product']->value->customizable){?>
		<div id="idTab10" class="bullet customization_block content_hide_show">
			<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['customizationFormTarget']->value;?>
" enctype="multipart/form-data" id="customizationForm" class="clearfix">
				<p class="infoCustomizable">
					<?php echo smartyTranslate(array('s'=>'After saving your customized product, remember to add it to your cart.'),$_smarty_tpl);?>

					<?php if ($_smarty_tpl->tpl_vars['product']->value->uploadable_files){?><br /><?php echo smartyTranslate(array('s'=>'Allowed file formats are: GIF, JPG, PNG'),$_smarty_tpl);?>
<?php }?>
				</p>
				<?php if (intval($_smarty_tpl->tpl_vars['product']->value->uploadable_files)){?>
				<div class="customizableProductsFile">
					<h3><?php echo smartyTranslate(array('s'=>'Pictures'),$_smarty_tpl);?>
</h3>
					<ul id="uploadable_files" class="clearfix">
						<?php echo smarty_function_counter(array('start'=>0,'assign'=>'customizationField'),$_smarty_tpl);?>

						<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['customizationFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']++;
?>
						<?php if ($_smarty_tpl->tpl_vars['field']->value['type']==0){?>
						<li class="customizationUploadLine<?php if ($_smarty_tpl->tpl_vars['field']->value['required']){?> required<?php }?>"><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(((('pictures_').($_smarty_tpl->tpl_vars['product']->value->id)).('_')).($_smarty_tpl->tpl_vars['field']->value['id_customization_field']), null, 0);?>
							<?php if (isset($_smarty_tpl->tpl_vars['pictures']->value[$_smarty_tpl->tpl_vars['key']->value])){?>
							<div class="customizationUploadBrowse">
								<img src="<?php echo $_smarty_tpl->tpl_vars['pic_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['pictures']->value[$_smarty_tpl->tpl_vars['key']->value];?>
_small" alt="" />
								<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getProductDeletePictureLink($_smarty_tpl->tpl_vars['product']->value,$_smarty_tpl->tpl_vars['field']->value['id_customization_field']);?>
" title="<?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
" >
									<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
" class="customization_delete_icon" width="12" height="12" />
								</a>
							</div>
							<?php }?>

							<div class="customizationUploadBrowse">
								<label class="customizationUploadBrowseDescription"><?php if (!empty($_smarty_tpl->tpl_vars['field']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Please select an image file from your hard drive'),$_smarty_tpl);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['field']->value['required']){?><sup>*</sup><?php }?></label>
								<input type="file" name="file<?php echo $_smarty_tpl->tpl_vars['field']->value['id_customization_field'];?>
" id="img<?php echo $_smarty_tpl->tpl_vars['customizationField']->value;?>
" class="customization_block_input <?php if (isset($_smarty_tpl->tpl_vars['pictures']->value[$_smarty_tpl->tpl_vars['key']->value])){?>filled<?php }?>" />
							</div>				
						</li>
						<?php echo smarty_function_counter(array(),$_smarty_tpl);?>

						<?php }?>
						<?php } ?>
					</ul>

				</div>

				<?php }?>

		
				<?php if (intval($_smarty_tpl->tpl_vars['product']->value->text_fields)){?>
				<div class="customizableProductsText">
					<h3><?php echo smartyTranslate(array('s'=>'Text'),$_smarty_tpl);?>
</h3>
					<ul id="text_fields">
						<?php echo smarty_function_counter(array('start'=>0,'assign'=>'customizationField'),$_smarty_tpl);?>

						<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['customizationFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['customizationFields']['index']++;
?>
						<?php if ($_smarty_tpl->tpl_vars['field']->value['type']==1){?>
						<li class="customizationUploadLine<?php if ($_smarty_tpl->tpl_vars['field']->value['required']){?> required<?php }?>">
							<label for ="textField<?php echo $_smarty_tpl->tpl_vars['customizationField']->value;?>
"><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(((('textFields_').($_smarty_tpl->tpl_vars['product']->value->id)).('_')).($_smarty_tpl->tpl_vars['field']->value['id_customization_field']), null, 0);?> <?php if (!empty($_smarty_tpl->tpl_vars['field']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['field']->value['required']){?><sup>*</sup><?php }?></label>
							<textarea type="text" name="textField<?php echo $_smarty_tpl->tpl_vars['field']->value['id_customization_field'];?>
" id="textField<?php echo $_smarty_tpl->tpl_vars['customizationField']->value;?>
" rows="1" cols="40" class="customization_block_input" /><?php if (isset($_smarty_tpl->tpl_vars['textFields']->value[$_smarty_tpl->tpl_vars['key']->value])){?><?php echo stripslashes($_smarty_tpl->tpl_vars['textFields']->value[$_smarty_tpl->tpl_vars['key']->value]);?>
<?php }?></textarea>
						</li>
						<?php echo smarty_function_counter(array(),$_smarty_tpl);?>

						<?php }?>
						<?php } ?>
					</ul>
				</div>
				<?php }?>
				<p id="customizedDatas">
					<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
					<input type="hidden" name="submitCustomizedDatas" value="1" />
					<input type="button" class="button" value="<?php echo smartyTranslate(array('s'=>'Save'),$_smarty_tpl);?>
" onclick="javascript:saveCustomization()" />
					<span id="ajax-loader" style="display:none"><img src="<?php echo $_smarty_tpl->tpl_vars['img_ps_dir']->value;?>
loader.gif" alt="loader" /></span>
				</p>
			</form>

									

			<p class="clear required"><sup>*</sup> <?php echo smartyTranslate(array('s'=>'required fields'),$_smarty_tpl);?>
</p>
		</div>
		<?php }?>

		<?php if (isset($_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB_CONTENT']->value)&&$_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB_CONTENT']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_PRODUCT_TAB_CONTENT']->value;?>
<?php }?>
		


<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['packItems']->value)&&count($_smarty_tpl->tpl_vars['packItems']->value)==0)||!isset($_smarty_tpl->tpl_vars['packItems']->value)){?>
<?php if (isset($_smarty_tpl->tpl_vars['HOOK_PRODUCT_FOOTER']->value)&&$_smarty_tpl->tpl_vars['HOOK_PRODUCT_FOOTER']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_PRODUCT_FOOTER']->value;?>
<?php }?>
<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['packItems']->value)&&count($_smarty_tpl->tpl_vars['packItems']->value)>0){?>
	<div id="blockpack">
		<h2><?php echo smartyTranslate(array('s'=>'Pack content'),$_smarty_tpl);?>
</h2>
		<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('products'=>$_smarty_tpl->tpl_vars['packItems']->value), 0);?>



	<?php }?>
<?php }?>

<?php }} ?>