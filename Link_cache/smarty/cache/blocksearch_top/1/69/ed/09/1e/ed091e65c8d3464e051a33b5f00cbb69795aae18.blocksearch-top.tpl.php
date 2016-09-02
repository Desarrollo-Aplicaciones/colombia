<?php /*%%SmartyHeaderCode:209173066853487322917bc8-72207789%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed091e65c8d3464e051a33b5f00cbb69795aae18' => 
    array (
      0 => '/var/www/themes/gomarket/modules/blocksearch/blocksearch-top.tpl',
      1 => 1387578294,
      2 => 'file',
    ),
    'd0f761ba46d2675330e0f7e988c6cd2e121e371b' => 
    array (
      0 => '/var/www/modules/blocksearch/blocksearch-instantsearch.tpl',
      1 => 1382821254,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209173066853487322917bc8-72207789',
  'variables' => 
  array (
    'hook_mobile' => 0,
    'link' => 0,
    'ENT_QUOTES' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873229ff017_41028032',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873229ff017_41028032')) {function content_534873229ff017_41028032($_smarty_tpl) {?><div id="search_block_top"><form action="http://test.farmalisto.com.co/buscar" id="searchbox" method="get"><p> <label for="search_query_top"></label> <input type="hidden" name="controller" value="search" /> <input type="hidden" name="orderby" value="position" /> <input type="hidden" name="orderway" value="desc" /> <input class="search_query" type="text" id="search_query_top" name="search_query" value="Buscar en toda la tienda... "  onfocus="this.value=''" onblur="if (this.value =='') this.value='Buscar en toda la tienda...'" /> <input type="submit" name="submit_search" value="Buscar" class="button" /></p></form></div><script type="text/javascript">/* <![CDATA[ */function tryToCloseInstantSearch(){if($('#old_center_column').length>0)
{$('#center_column').remove();$('#old_center_column').attr('id','center_column');$('#center_column').show();return false;}}
instantSearchQueries=new Array();function stopInstantSearchQueries(){for(i=0;i<instantSearchQueries.length;i++){instantSearchQueries[i].abort();}
instantSearchQueries=new Array();}
$("#search_query_top").keyup(function(){if($(this).val().length>0){stopInstantSearchQueries();instantSearchQuery=$.ajax({url:'http://test.farmalisto.com.co/buscar',data:{instantSearch:1,id_lang:1,q:$(this).val()},dataType:'html',type:'POST',success:function(data){if($("#search_query_top").val().length>0)
{tryToCloseInstantSearch();$('#center_column').attr('id','old_center_column');$('#old_center_column').after('<div id="center_column" class="'+$('#old_center_column').attr('class')+'">'+data+'</div>');$('#old_center_column').hide();ajaxCart.overrideButtonsInThePage();$("#instant_search_results a.close").click(function(){$("#search_query_top").val('');return tryToCloseInstantSearch();});return false;}
else
tryToCloseInstantSearch();}});instantSearchQueries.push(instantSearchQuery);}
else
tryToCloseInstantSearch();});/* ]]> */</script><script type="text/javascript">/* <![CDATA[ */$('document').ready(function(){$("#search_query_top").autocomplete('http://test.farmalisto.com.co/buscar',{minChars:3,max:10,width:500,selectFirst:false,scroll:false,dataType:"json",formatItem:function(data,i,max,value,term){return value;},parse:function(data){var mytab=new Array();for(var i=0;i<data.length;i++)
mytab[mytab.length]={data:data[i],value:data[i].cname+' > '+data[i].pname};return mytab;},extraParams:{ajaxSearch:1,id_lang:1}}).result(function(event,data,formatted){$('#search_query_top').val(data.pname);document.location.href=data.product_link;})});/* ]]> */</script><?php }} ?>