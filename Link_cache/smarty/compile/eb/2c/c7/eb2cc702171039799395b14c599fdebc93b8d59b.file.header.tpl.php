<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:34
         compiled from "/var/www/modules/ganalytics/views/templates/hook/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:28771297534873226fa666-62278482%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eb2cc702171039799395b14c599fdebc93b8d59b' => 
    array (
      0 => '/var/www/modules/ganalytics/views/templates/hook/header.tpl',
      1 => 1395440930,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28771297534873226fa666-62278482',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ganalytics_id' => 0,
    'universal_analytics' => 0,
    'isOrder' => 0,
    'trans' => 0,
    'items' => 0,
    'item' => 0,
    'pageTrack' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5348732283d144_52829896',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5348732283d144_52829896')) {function content_5348732283d144_52829896($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>

<script type="text/javascript">



  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  


  ga('create', '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['ganalytics_id']->value, 'htmlall', 'UTF-8');?>
', 'farmalisto.com.co');
  
  ga('send', 'pageview');

    
 <?php if ($_smarty_tpl->tpl_vars['universal_analytics']->value==true){?>
  
    <?php if ($_smarty_tpl->tpl_vars['isOrder']->value==true){?>
       ga('require', 'ecommerce', 'ecommerce.js');
    
    ga('ecommerce:addTransaction', {
        'id': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['id'], 'htmlall', 'UTF-8');?>
',
        'affiliation': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['store'], 'htmlall', 'UTF-8');?>
',
        'revenue': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['total'], 'htmlall', 'UTF-8');?>
',
        'tax': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['tax'], 'htmlall', 'UTF-8');?>
',
        'shipping': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['shipping'], 'htmlall', 'UTF-8');?>
',
        'city': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['city'], 'htmlall', 'UTF-8');?>
',
        'state': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['state'], 'htmlall', 'UTF-8');?>
',
        'country': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['country'], 'htmlall', 'UTF-8');?>
',
        'currency': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['currency'], 'htmlall', 'UTF-8');?>
'
    });

    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    ga('ecommerce:addItem', {
        'id': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['OrderId'], 'htmlall', 'UTF-8');?>
',
        'sku': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['SKU'], 'htmlall', 'UTF-8');?>
',
        'name': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Product'], 'htmlall', 'UTF-8');?>
',
        'category': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Category'], 'htmlall', 'UTF-8');?>
',
        'price': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Price'], 'htmlall', 'UTF-8');?>
',
        'quantity': '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Quantity'], 'htmlall', 'UTF-8');?>
'
    });
    <?php } ?>
    ga('ecommerce:send');
     //setTimeout("", 250);
     $().delay(250);
    //alert("ecommerce:send");
    <?php }?>
    <?php }else{ ?>
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['ganalytics_id']->value, 'htmlall', 'UTF-8');?>
']);
    // Recommended value by Google doc and has to before the trackPageView
    _gaq.push(['_setSiteSpeedSampleRate', 5]);

    _gaq.push(['_trackPageview'<?php if (isset($_smarty_tpl->tpl_vars['pageTrack']->value)){?>, '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['pageTrack']->value, 'htmlall', 'UTF-8');?>
'<?php }?>]);

    <?php if ($_smarty_tpl->tpl_vars['isOrder']->value==true){?>            
        
    _gaq.push(['_addTrans',
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['id'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['store'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['total'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['tax'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['shipping'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['city'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['state'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['country'], 'htmlall', 'UTF-8');?>
' 
    ]);

    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    _gaq.push(['_addItem',
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['OrderId'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['SKU'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Product'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Category'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Price'], 'htmlall', 'UTF-8');?>
', 
        '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Quantity'], 'htmlall', 'UTF-8');?>
' 
    ]);
    <?php } ?>
    
    
    _gaq.push(['_trackTrans']);
     //alert("trackTrans");
    
    <?php }?>
    
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
    
    
   
    
    <?php }?>
    
 
     
     console.log('PageTrack: '+<?php if (isset($_smarty_tpl->tpl_vars['pageTrack']->value)){?> '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['pageTrack']->value, 'htmlall', 'UTF-8');?>
'<?php }?>); 
     
     <?php if ($_smarty_tpl->tpl_vars['isOrder']->value==true){?>
     console.log("id: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['id'], 'htmlall', 'UTF-8');?>
");
     console.log("store: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['store'], 'htmlall', 'UTF-8');?>
");
     console.log("total: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['total'], 'htmlall', 'UTF-8');?>
");
     console.log("tax: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['tax'], 'htmlall', 'UTF-8');?>
");
     console.log("shipping: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['shipping'], 'htmlall', 'UTF-8');?>
");
     console.log("city: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['city'], 'htmlall', 'UTF-8');?>
");
     console.log("state: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['state'], 'htmlall', 'UTF-8');?>
");
     console.log("country: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['trans']->value['country'], 'htmlall', 'UTF-8');?>
");
     
     <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    
        console.log("OrderId: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['OrderId'], 'htmlall', 'UTF-8');?>
");
        console.log("SKU: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['SKU'], 'htmlall', 'UTF-8');?>
");
        console.log("Product: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Product'], 'htmlall', 'UTF-8');?>
");
        console.log("Category: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Category'], 'htmlall', 'UTF-8');?>
");
        console.log("Price: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Price'], 'htmlall', 'UTF-8');?>
");
        console.log("Quantity: <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['item']->value['Quantity'], 'htmlall', 'UTF-8');?>
");

    <?php } ?>
     <?php }?>
     
     
     
     
     
</script><?php }} ?>