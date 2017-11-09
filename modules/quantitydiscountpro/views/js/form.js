/**
* Quantity Discount Pro
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2017 idnovate.com
*  @license   See above
*/

/********** CONDITIONS **********/
var condition_selectors = new Array('country', 'carrier', 'group', 'cart_rule', 'shop', 'product', 'category', 'attribute', 'zone', 'state', 'manufacturer', 'supplier', 'order_state', 'gender', 'currency');

function addConditionGroup()
{
	condition_group_id = 1;
	condition_counters_length = condition_counters.length;
	condition_counters[condition_counters_length] = 1;

	$.get(
		'ajax-tab.php', {
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newConditionGroup: 1,
			condition_id: (!isNaN(condition_counters[condition_group_id]) ? condition_counters[condition_group_id] : '1'),
			condition_group_id: (condition_counters_length ? condition_counters_length : '1'),
		},
		function(content) {
			if (content != "") {
				$('#conditions').append(content);
			}
		}
	);
}

function addCondition(element)
{
	condition_group_id = $(element).parent().parent().find('input[name^="condition_group"]').val();
	if (isNaN(condition_group_id)) {
		condition_group_id = 1;
		condition_counters[condition_group_id] = 1;
	} else {
		if (isNaN(condition_counters[condition_group_id])) {
			condition_counters[condition_group_id] = 1;
		} else {
			condition_counters[condition_group_id] += 1;
		}
	}

	$.get(
		'ajax-tab.php',	{
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newCondition:1,
			condition_id: (!isNaN(condition_counters[condition_group_id]) ? condition_counters[condition_group_id] : '1'),
			condition_group_id:condition_group_id,
		},
		function(content) {
			if (content != "") {
				$(element).parent().parent().find('.conditions_container').append(content);
			}
		}
	);
}

function removeConditionGroup(element) {
	condition_group_id = $(element).parent().parent().remove();
}

function removeCondition(id) {
	$('#condition_'+id+'_container').remove();
}

function toggleQuantityDiscountFilter(id) {
	if ($(id).is("input")) {
		if ($(id).prop('checked'))
			$('#' + $(id).attr('id') + '_div').show(400);
		else
			$('#' + $(id).attr('id') + '_div').hide(200);
	} else {
		if ($("input[name='"+$(id).attr('id')+"']:checked").val() == 1)
			$('#' + $(id).attr('id') + '_div').show(400);
		else
			$('#' + $(id).attr('id') + '_div').hide(200);
	}
}

function removeQuantityDiscountOption(item) {
	var id_selected = $(item).attr('id').replace('remove', '2');
	var id_unselected = $(item).attr('id').replace('remove', '1');
	$('#' + id_selected + ' option:selected').remove().appendTo('#' + id_unselected);
}

function addQuantityDiscountOption(item) {
	var id_selected = $(item).attr('id').replace('add', '2');
	var id_unselected = $(item).attr('id').replace('add', '1');
	$('#' + id_unselected + ' option:selected').remove().appendTo('#' + id_selected);
}


function toggleActionFilters() {
	$('[name^=action_filter_by_]:checked').each(function() {
		if ($(this).val() == 0) {
			$(this).parents().eq(4).next().hide();
		} else {
			$(this).parents().eq(4).next().show();

		}
	});
}

function hideAllConditions(element) {
	$("#"+element.attr('id')+" div[class^='condition_type_options_']").hide();
}

function hideDisplayCodeField() {
	if (!$('#code').val()) {
		$('#code_prefix').parents().eq(3).show();
	} else {
		$('#code_prefix').parents().eq(3).hide();
		$('#code_prefix').val('');
	}
}

function toggleFilters() {
	$('[name^=condition_filter_by_]:checked').each(function() {
		if ($(this).val() == 0) {
			$(this).parents().eq(4).next().hide();
		} else {
			$(this).parents().eq(4).next().show();

		}
	});
}

// Main form submit
$('#quantity_discount_rule_form').submit(function() {
	if ($('#customerFilter').val() == '') {
		$('#condition_id_customer').val('0');
	}

	for (i in condition_selectors) {
		$('[id^=' + condition_selectors[i] + '_select_2]').each(function() {
			$(this).find('option').each(function() {
				$(this).prop('selected', true);
			});

			if ($(this).val()) {
				$('[id*="_' + $(this).attr('id') + '_json"]').val(JSON.stringify($(this).val()));
			}

			$(this).remove();
		});
	}
});

/* Hide/Display code field */
hideDisplayCodeField();
$('#code').keyup(function() {
	hideDisplayCodeField();
});

/********** ACTIONS **********/
var action_selectors = new Array('product', 'category', 'attribute', 'manufacturer', 'supplier');

function addActionProduct(element)
{
	action_counter += 1;

	$.get(
		'ajax-tab.php',	{
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newActionProduct:1,
			action_id: action_counter,
		},
		function(content) {
			if (content != "") {
				$(element).parent().parent().find('.actions_container').append(content);
			}
		}
	);
}

function removeActionProduct(id) {
	$('#action_'+id+'_container').remove();
}

function hideAllActions(element) {
	$("#"+element.attr('id')+" div[class*='action_apply_discount_to_']").hide();
}

function toggleConditions(element) {
	var id_value = element.attr('name');
	var pattern = /\[(.*?)\]/g;
	var id = [];
	var match;
	while ((match = pattern.exec(id_value)) != null) {
		id.push(match[1]);
	}

	var value = element.val();

	$("#condition_"+id[0]+"_"+id[1]+"_container [class*='condition_type_options_']").hide();
	$("#condition_"+id[0]+"_"+id[1]+"_container .condition_type_options_" + value).show();
	$("#condition_"+id[0]+"_"+id[1]+"_container div[class*='condition_type_options_hide_']").show();
	$("#condition_"+id[0]+"_"+id[1]+"_container div.condition_type_options_hide_" + value).hide();
}

toggleQuantityDiscountFilter($('#product_restriction'));

function displayQuantityDiscountTab(tab)
{
	$('.quantity_discount_rule_tab').hide();
	$('.tab-row.active').removeClass('active');
	$('#quantity_discount_rule_' + tab).show();
	$('#quantity_discount_rule_link_' + tab).parent().addClass('active');
	$('#currentFormTab').val(tab);
}

$('.quantity_discount_rule_tab').hide();
$('.tab-row.active').removeClass('active');
$('#quantity_discount_rule_' + currentFormTab).show();
$('#quantity_discount_rule_link_' + currentFormTab).parent().addClass('active');

function showHideActionOptions()
{
	var remove = true;
	var first = true;
	$("[id='action_condition_new_action").hide();

	$("select[name^='action_id_type']").each(function() {
		id = $(this).attr('name').replace( /(^.*\[|\].*$)/g, '' );

		if (!first && remove) {
			$('#action_'+id+'_container').remove();
		}

		$("[id ^='action_condition_'][id $='["+id+"]']").hide();
		value = $("select[name='action_id_type["+id+"]']").val();

		if (value == 1) {
			// Shipping cost - Fixed discount
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_1["+id+"]']").show();
		} else if (value == 5) {
			// Shipping cost - Percentage discount
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
		} else if (value == 2) {
			// Order amount - Fixed discount
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_1["+id+"]']").show();
			$("[id='action_condition_amount_shipping["+id+"]']").show();
		} else if (value == 3) {
			// Order amount - Percentage discount
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_percent_shipping["+id+"]']").show();
			$("[id='action_condition_percent_discount["+id+"]']").show();
		} else if (value == 27) {
			// Product discount - Fixed discount
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_2["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();
			$("[id='action_condition_sort["+id+"]']").show();
			$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			$("input[name^='action_group_products_by'][value='product']").prop('checked', true)
		} else if (value == 28) {
			// Product discount - Percentage discount
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_product_maximum_amount["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_2["+id+"]']").show();
			$("[id='action_condition_regular_price["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();
			$("[id='action_condition_sort["+id+"]']").show();
			$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			$("input[name^='action_group_products_by'][value='product']").prop('checked', true)
		} else if (value == 29) {
			// Product discount - Fixed price
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_3["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();
			$("[id='action_condition_sort["+id+"]']").show();
			$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			$("input[name^='action_group_products_by'][value='product']").prop('checked', true)
		} else if (value == 6) {
			// Buy X Get Y - Fixed discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_2["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 7) {
			// Buy X Get Y - Percentage discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_product_maximum_amount["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_2["+id+"]']").show();
			$("[id='action_condition_attributes["+id+"]']").show();
			$("[id='action_condition_regular_price["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 8) {
			// Buy X Get Y - Fixed price
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_3["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 12) {
			// All products after X - Fixed discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_2["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 13) {
			// All products after X - Percentage discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_2["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_product_maximum_amount["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_3["+id+"]']").show();
			$("[id='action_condition_attributes["+id+"]']").show();
			$("[id='action_condition_regular_price["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 14) {
			// All products after X - Fixed price
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_2["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_4["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 15) {
			// Each group of X - Fixed discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_3["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_1["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 16) {
			// Each group of X - Percentage discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_3["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_3["+id+"]']").show();
			$("[id='action_condition_attributes["+id+"]']").show();
			$("[id='action_condition_regular_price["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 17) {
			// Each group of X - Fixed price
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_3["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_4["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 18) {
			// Each X-th after Y - Fixed discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_4["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_2["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_1["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 19) {
			// Each X-th after Y - Percentage discount
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_4["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_2["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_product_maximum_amount["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_3["+id+"]']").show();
			$("[id='action_condition_attributes["+id+"]']").show();
			$("[id='action_condition_regular_price["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 20) {
			// Each X-th after Y - Fixed price
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_4["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_2["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_4["+id+"]']").show();
			$("[id='action_condition_repeat["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();


			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}
		} else if (value == 21) {
			// Each X spent (over Z) Get Y - Fixed discount
			$("[id='action_condition_buy_amount["+id+"]']").show();
			$("[id='action_condition_amount_label_5["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_shipping["+id+"]']").show();
			$("[id='action_condition_buy_over["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();
		} else if (value == 26) {
			// X spent (over Z) Get Y - Percentage discount
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_3["+id+"]']").show();
			$("[id='action_condition_buy_over["+id+"]']").show();
			$("[id='action_condition_amount_shipping["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();
		} else if (value == 22) {
			// Buy X
			$("[id='action_condition_new_action").show();
			if (!first) {
				$("[id='action_condition_remove_button["+id+"]']").show();
			}
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_buy["+id+"]']").show();
			$("[id='action_condition_buy_label_1["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}

			remove = false;
		} else if (value == 100) {
			// Get a discount on A - Fixed discount
			$("[id='action_condition_new_action").show();
			if (!first) {
				$("[id='action_condition_remove_button["+id+"]']").show();
			}
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_2["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}

			remove = false;
		} else if (value == 101) {
			// Get a discount on A - Percentage discount
			$("[id='action_condition_new_action").show();
			if (!first) {
				$("[id='action_condition_remove_button["+id+"]']").show();
			}
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_value["+id+"]']").show();
			$("[id='action_condition_value_label_2["+id+"]']").show();
			$("[id='action_condition_maximum_amount["+id+"]']").show();
			$("[id='action_condition_product_maximum_amount["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}

			remove = false;
		} else if (value == 102) {
			// Get a discount on A - Fixed price
			$("[id='action_condition_new_action").show();
			if (!first) {
				$("[id='action_condition_remove_button["+id+"]']").show();
			}
			$("[id='action_condition_group_by["+id+"]']").show();
			$("[id='action_condition_get["+id+"]']").show();
			$("[id='action_condition_get_label_1["+id+"]']").show();
			$("[id='action_condition_amount["+id+"]']").show();
			$("[id='action_condition_reduction_currency["+id+"]']").show();
			$("[id='action_condition_amount_label_3["+id+"]']").show();
			$("[id='action_condition_filters["+id+"]']").show();

			if ($("input[name^='action_group_products_by']:checked").val() == 'product') {
				$("[id='action_condition_attributes["+id+"]']").show();
				$("[id='action_condition_sort["+id+"]']").hide();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			} else if ($("input[name^='action_group_products_by']:checked").val() == 'category') {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").show();
			} else {
				$("[id='action_condition_attributes["+id+"]']").hide();
				$("[id='action_condition_sort["+id+"]']").show();
				$("[id='action_condition_consider_only_default_category["+id+"]']").hide();
			}

			remove = false;
		} else if (value == 400) {
            // Product discount - Percentage discount
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_5["+id+"]']").show();
            //$("[id='action_condition_maximum_amount["+id+"]']").show();
            //$("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_tramos_value["+id+"]']").show();
            $("[id='action_condition_value_label_2["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            //$("[id='action_condition_sort["+id+"]']").show();
            //$("[id='action_condition_consider_only_default_category["+id+"]']").show();
            $("input[name^='action_group_products_by'][value='product']").prop('checked', true)


        }

		$("select[data-group='currency["+id+"]']").on('change', function () {
			$("select[data-group='currency["+id+"]']").val(this.value);
		});
		$("select[data-group='tax["+id+"]']").on('change', function () {
			$("select[data-group='tax["+id+"]']").val(this.value);
		});
		$("select[data-group='shipping["+id+"]']").on('change', function () {
			$("select[data-group='shipping["+id+"]']").val(this.value);
		});

		first = false;
	});

	return;
}

/********** MESSAGE **********/
function addMessage(element)
{
	message_counter += 1;

	$.get(
		'ajax-tab.php',	{
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newMessage: 1,
			lang: id_language,
			message_id: message_counter,
		},
		function(content) {
			if (content != "") {
				$(element).parent().parent().find('.messages_container').append(content);
			}
		}
	);
}

function removeMessage(id) {
	$('#message_'+id+'_container').remove();
}