{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<form action="../agregar.php" method="post" id="myForm1" target="Details" onSubmit="return popWindow2(this.target);">
	
<input type="hidden" name="id_supply_order" value="{$smarty.get.id_supply_order}">
<input type="hidden" name="id_emp" value="{$employee->id}">
</form>

<script src="../js/jquery.modal.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../js/jquery.modal.css" type="text/css" media="screen" />




<div id="ex5" class="modal">
    <iframe  id="testFrame" src="../agregar.php?id_supply_order={$smarty.get.id_supply_order}&id_emp={$employee->id}" frameBorder="0" style="width: 100%; height: 95%; "></iframe>
    <a href="#" rel="modal:close">Cerrar la ventana, sin guardar los cambios.</a>
</div>

   
{if !$simple_header}
    <script type="text/javascript">
        
     
   $(document).ready(function() {

    
          $("#desasociaricr").click(function(){
         $('#testFrame').attr('src', '../agregar.php?id_supply_order={$smarty.get.id_supply_order}&id_emp={$employee->id}&opcion=update&lastname={$employee->lastname}&firstname={$employee->firstname}');
      });
        
       $("#asociaricr").click(function(){
            $('#testFrame').attr('src', '../agregar.php?id_supply_order={$smarty.get.id_supply_order}&id_emp={$employee->id}&opcion=save&lastname={$employee->lastname}&firstname={$employee->firstname}');
       });
    
   //reload 1 iframe
  $('#testFrame')[0].contentWindow.location.reload(true);
    
   $('a[href="#ex5"]').click(function(event) {
  event.preventDefault();
   $(this).modal({
   escapeClose: false,
   clickClose: false,
   showClose: false
   });
   }); 
        

      
                     
                    
                    
   $('table.{$list_id} .filter').keypress(function(event){
    formSubmit(event, 'submitFilterButton{$list_id}')
   })
  });
    </script>
    
	{* Display column names and arrows for ordering (ASC, DESC) *}
	{if $is_order_position}
		<script type="text/javascript" src="../js/jquery/plugins/jquery.tablednd.js"></script>
		<script type="text/javascript">
			var token = '{$token}';
			var come_from = '{$list_id}';
			var alternate = {if $order_way == 'DESC'}'1'{else}'0'{/if};
		</script>
		<script type="text/javascript" src="../js/admin-dnd.js"></script>
	{/if}

	<script type="text/javascript">
		$(function() {
			if ($("table.{$list_id} .datepicker").length > 0)
				$("table.{$list_id} .datepicker").datepicker({
					prevText: '',
					nextText: '',
					dateFormat: 'yy-mm-dd'
				});
		});
	</script>


{/if}{* End if simple_header *}

{if $show_toolbar}
	{include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
{/if}

{if !$simple_header}
	<div class="leadin">{block name="leadin"}{/block}</div>
{/if}

{block name="override_header"}{/block}


{hook h='displayAdminListBefore'}
{if isset($name_controller)}
	{capture name=hookName assign=hookName}display{$name_controller|ucfirst}ListBefore{/capture}
	{hook h=$hookName}
{elseif isset($smarty.get.controller)}
	{capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|htmlentities}ListBefore{/capture}
	{hook h=$hookName}
{/if}


{if !$simple_header}
<form method="post" action="{$action}" class="form" id="detail_prods">

	{block name="override_form_extra"}{/block}

	<input type="hidden" id="submitFilter{$list_id}" name="submitFilter{$list_id}" value="0"/>
{/if}
	<table class="table_grid" name="list_table">
		{if !$simple_header}
			<tr>
				<td style="vertical-align: bottom;">
					<span style="float: left;">
						{if $page > 1}
							<input type="image" src="../img/admin/list-prev2.gif" onclick="getE('submitFilter{$list_id}').value=1"/>&nbsp;
							<input type="image" src="../img/admin/list-prev.gif" onclick="getE('submitFilter{$list_id}').value={$page - 1}"/>
						{/if}
						{l s='Page'} <b>{$page}</b> / {$total_pages}
						{if $page < $total_pages}
							<input type="image" src="../img/admin/list-next.gif" onclick="getE('submitFilter{$list_id}').value={$page + 1}"/>&nbsp;
							<input type="image" src="../img/admin/list-next2.gif" onclick="getE('submitFilter{$list_id}').value={$total_pages}"/>
						{/if}
						| {l s='Display'}
						<select name="{$list_id}_pagination" onchange="submit()">
							{* Choose number of results per page *}
							{foreach $pagination AS $value}
								<option value="{$value|intval}"{if $selected_pagination == $value} selected="selected" {elseif $selected_pagination == NULL && $value == $pagination[1]} selected="selected2"{/if}>{$value|intval}</option>
							{/foreach}
						</select>
						/ {$list_total} {l s='result(s)'}
					</span>
					{*<span style="float: right;">
						<input type="submit" id="submitFilterButton{$list_id}" name="submitFilter" value="{l s='Filter'}" class="button" />					
						<input type="submit" name="submitReset{$list_id}" value="{l s='Reset'}" class="button" />
					</span>
					<span class="clear"></span>*}
                                        
<script language="Javascript">
    
 
function popWindow2(wName){
	features = 'width=400,height=400,toolbar=no,location=no,directories=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}


function abrirCaja()
{
posx=((screen.availWidth-800)/2)
posy=((screen.availHeight-600)/2)
eval("boxwindow=window.open('../agregar.php?id_supply_order={$smarty.get.id_supply_order}&id_emp={$employee->id}','boxwindow','width=800,height=600,toolbar=0,directories=0,status=0,scrollbars=0,resize=0,menubar=0,screenx="+posx+",screeny="+posy+",left="+posx+",top="+posy+"')")
}

</script> 

					<span style="float: right; ">
                                            {literal} 
                                                <a style="background-color: #00BB35; border-radius: 5px;  padding: 5px;" id="asociaricr" href="#ex5">Asociar C&oacute;digos ICR</a> | <a style="background-color: sandybrown; border-radius: 5px;  padding: 5px;" id="desasociaricr" href="#ex5">Des-asociar C&oacute;digos ICR</a>
                                           {/literal}
                                           <!--
                                            <input type="button" id="adicionarIcrOrden" name="adicionarIcrOrden" value="Asociar" onclick="abrirCaja()" class="button" />					
						<input type="button" id="eliminarIcrOrden" name="eliminarIcrOrden" value="Desasociar-1" class="button" />
                                                <input type="button" id="myButton1" value="Asociar - 2">
                                               
                                                -->
                                         

                                                
                                                               
					</span>
					<span class="clear"></span>


				</td>
			</tr>
		{/if}
		<tr>
			<td{if $simple_header} style="border:none;"{/if}>
				<table
				{if $table_id} id={$table_id}{/if}
				class="table {if $table_dnd}tableDnD{/if} {$list_id}"
				cellpadding="0" cellspacing="0"
				style="width: 100%; margin-bottom:10px;">
					<col width="10px" />
					{foreach $fields_display AS $key => $params}
						<col {if isset($params.width) && $params.width != 'auto'}width="{$params.width}px"{/if}/>
					{/foreach}
					{if $shop_link_type}
						<col width="80px" />
					{/if}
					{if $has_actions}
						<col width="52px" />
					{/if}
					<thead>
						<tr class="nodrag nodrop" style="height: 40px">
							<th class="center">
								{if $has_bulk_actions}
									<input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, '{$list_id}Box[]', this.checked)" />
								{/if}
							</th>
							{foreach $fields_display AS $key => $params}
								<th {if isset($params.align)} class="{$params.align}"{/if}>
									{if isset($params.hint)}<span class="hint" name="help_box">{$params.hint}<span class="hint-pointer">&nbsp;</span></span>{/if}
									<span class="title_box">
										{$params.title}
									</span>
									{if (!isset($params.orderby) || $params.orderby) && !$simple_header}
										<br />
										<a href="{$currentIndex}&{$list_id}Orderby={$key|urlencode}&{$list_id}Orderway=desc&token={$token}{if isset($smarty.get.$identifier)}&{$identifier}={$smarty.get.$identifier|intval}{/if}">
										<img border="0" src="../img/admin/down{if isset($order_by) && ($key == $order_by) && ($order_way == 'DESC')}_d{/if}.gif" /></a>
										<a href="{$currentIndex}&{$list_id}Orderby={$key|urlencode}&{$list_id}Orderway=asc&token={$token}{if isset($smarty.get.$identifier)}&{$identifier}={$smarty.get.$identifier|intval}{/if}">
										<img border="0" src="../img/admin/up{if isset($order_by) && ($key == $order_by) && ($order_way == 'ASC')}_d{/if}.gif" /></a>
									{elseif !$simple_header}
										<br />&nbsp;
									{/if}
								</th>
							{/foreach}
							{if $shop_link_type}
								<th>
									{if $shop_link_type == 'shop'}
										{l s='Shop'}
									{else}
										{l s='Group shop'}
									{/if}
									<br />&nbsp;
								</th>
							{/if}
							{if $has_actions}
								<th class="center">{l s='Actions'}{if !$simple_header}<br />&nbsp;{/if}</th>
							{/if}
						</tr>
 						{if !$simple_header}
						<tr class="nodrag nodrop filter {if $row_hover}row_hover{/if}" style="height: 35px;">
							<td class="center">
								{if $has_bulk_actions}
									--
								{/if}
							</td>

							{* Filters (input, select, date or bool) *}
							{foreach $fields_display AS $key => $params}
								<td {if isset($params.align)} class="{$params.align}" {/if}>
									{if isset($params.search) && !$params.search}
										--
									{else}
										{if $params.type == 'bool'}
											<select onchange="$('#submitFilterButton{$list_id}').focus();$('#submitFilterButton{$list_id}').click();" name="{$list_id}Filter_{$key}">
												<option value="">--</option>
												<option value="1" {if $params.value == 1} selected="selected" {/if}>{l s='Yes'}</option>
												<option value="0" {if $params.value == 0 && $params.value != ''} selected="selected" {/if}>{l s='No'}</option>
											</select>
										{elseif $params.type == 'date' || $params.type == 'datetime'}
											{l s='From'} <input type="text" class="filter datepicker" id="{$params.id_date}_0" name="{$params.name_date}[0]" value="{if isset($params.value.0)}{$params.value.0}{/if}"{if isset($params.width)} style="width:70px"{/if}/><br />
											{l s='To'} <input type="text" class="filter datepicker" id="{$params.id_date}_1" name="{$params.name_date}[1]" value="{if isset($params.value.1)}{$params.value.1}{/if}"{if isset($params.width)} style="width:70px"{/if}/>
										{elseif $params.type == 'select'}
											{if isset($params.filter_key)}
												<select onchange="$('#submitFilterButton{$list_id}').focus();$('#submitFilterButton{$list_id}').click();" name="{$list_id}Filter_{$params.filter_key}" {if isset($params.width)} style="width:{$params.width}px"{/if}>
													<option value="" {if $params.value == ''} selected="selected" {/if}>--</option>
													{if isset($params.list) && is_array($params.list)}
														{foreach $params.list AS $option_value => $option_display}
															<option value="{$option_value}" {if $option_display == $params.value ||  $option_value == $params.value} selected="selected"{/if}>{$option_display}</option>
														{/foreach}
													{/if}
												</select>
											{/if}
										{else}
											<input type="text" class="filter" name="{$list_id}Filter_{if isset($params.filter_key)}{$params.filter_key}{else}{$key}{/if}" value="{$params.value|escape:'htmlall':'UTF-8'}" {if isset($params.width) && $params.width != 'auto'} style="width:{$params.width}px"{else}style="width:95%"{/if} />
										{/if}
									{/if}
								</td>
							{/foreach}

							{if $shop_link_type}
								<td>--</td>
							{/if}
							{if $has_actions}
								<td class="center">--</td>
							{/if}
							</tr>
						{/if}
						</thead>





{block name=override_header}
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$('input.quantity_received_today').live('click', function() {
		/* checks checkbox when the input is clicked */
		$(this).parents('tr:eq(0)').find('input[type=checkbox]').attr('checked', true);
	});

	$('.quantity_received_today').prop("disabled", true);
	$('.noborder').prop("disabled", true);
        
        if ($('#testFrame').length){
       //Ejecutar si existe el elemento
      $('.supply_order_ds_cant').attr("disabled","disabled") 
      //$('.supply_order_ds_cant').css("display", "none");
 
}
	

});
</script>
{/block}
<pre>
{*$employee|@print_r*} 
</pre>





