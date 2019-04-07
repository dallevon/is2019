<?php
/**
*
* Order items view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details_items.php 9831 2018-05-07 13:45:33Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">';

echo '<tr style="text-align: left;" class="sectiontableheader">';

// echo '<th style="text-align: left;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') . '</th>';
echo '<th style="text-align: left;">' . vmText::_('COM_VIRTUEMART_PRODUCT_NAME_TITLE') . '</th>';
echo '<th style="text-align: center;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_STATUS') . '</th>';
echo '<th style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRICE') . '</th>';
echo '<th style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_QTY') . '</th>';

if (VmConfig::get('show_tax')) {
	echo '<th style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_TAX') . '</th>';
}

// echo '<th style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL_DISCOUNT_AMOUNT') . '</th>';
echo '<th style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') . '</th>';

echo '</tr>';

foreach ($this->orderdetails['items'] as $item) {
	$qtt = $item->product_quantity;
	$_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id, false);

	echo '<tr style="vertical-align: top;">';

	// echo '<td style="text-align: left;">' . $item->order_item_sku . '</td>';
	echo '<td style="text-align: left;">';
	echo '<div><a href="' . $_link . '">' . $item->order_item_name . ' <small>' . $item->product_s_desc . '</small>' . '</a></div>';
	echo '</td>';
	echo '<td style="text-align: center;">' . $this->orderstatuses[$item->order_status] . '</td>';
	echo '<td style="text-align: right;" class="priceCol">';
	$item->product_discountedPriceWithoutTax = (float)$item->product_discountedPriceWithoutTax;
	if (!empty($item->product_priceWithoutTax) && $item->product_discountedPriceWithoutTax != $item->product_priceWithoutTax) {
		echo '<span class="line-through">' . $this->currency->priceDisplay($item->product_item_price, $this->user_currency_id) . '</span><br />';
		echo '<span>' . $this->currency->priceDisplay($item->product_discountedPriceWithoutTax, $this->user_currency_id) . '</span><br />';
	} else {
		echo '<span>' . $this->currency->priceDisplay($item->product_item_price, $this->user_currency_id) . '</span><br />';
	}
	echo '</td>';
	echo '<td style="text-align: right;">' . $qtt . '</td>';

	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;" class="priceCol"><span class="priceColor2">' . $this->currency->priceDisplay($item->product_tax, $this->user_currency_id, $qtt) . '</span></td>';
	}

	// echo '<td style="text-align: right;" class="priceCol">' . $this->currency->priceDisplay($item->product_subtotal_discount, $this->user_currency_id) . '</td>';
	echo '<td style="text-align: right;" class="priceCol">';
	$item->product_basePriceWithTax = (float)$item->product_basePriceWithTax;
	$class = '';
	if (!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price) {
		echo '<span class="line-through" >' . $this->currency->priceDisplay($item->product_basePriceWithTax, $this->user_currency_id, $qtt) . '</span><br />';
	} elseif (empty($item->product_basePriceWithTax) && $item->product_item_price != $item->product_final_price) {
		echo '<span class="line-through">' . $this->currency->priceDisplay($item->product_item_price, $this->user_currency_id, $qtt) . '</span><br />';
	}
	echo $this->currency->priceDisplay($item->product_subtotal_with_tax, $this->user_currency_id);
	echo '</td>';

	echo '</tr>';
}

echo '<tr class="sectiontableentry1">';

echo '<td colspan="4" style="text-align: right;">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL') . '</td>';
if (VmConfig::get('show_tax')) {
	echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_tax, $this->user_currency_id) . '</span></td>';
}
// echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_discountAmount, $this->user_currency_id) . '</span></td>';
echo '<td style="text-align: right;">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_salesPrice, $this->user_currency_id) . '</td> ';

echo '</tr>';

if ($this->orderdetails['details']['BT']->coupon_discount <> 0.00) {
	$coupon_code = $this->orderdetails['details']['BT']->coupon_code ? '(' . $this->orderdetails['details']['BT']->coupon_code . ') ' : ' ';
	echo '<tr>';

	echo '<td style="text-align: right;" class="pricePad" colspan="4">' . vmText::_(' COM_VIRTUEMART_COUPON_DISCOUNT ') . $coupon_code . '</td>';
	if (VmConfig::get(' show_tax ')) {
		echo '<td style="text-align: right;">&nbsp;</td>';
	}
	// echo '<td style="text-align: right;">&nbsp;</td>';
	echo '<td style="text-align: right;">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->coupon_discount, $this->user_currency_id) . '</td>';

	echo '</tr>';
}

foreach ($this->orderdetails['calc_rules'] as $rule) {
	if ($rule->calc_kind == ' DBTaxRulesBill ') {
		echo '<tr>';

		echo '<td colspan="4" style="text-align: right;" class="pricePad">' . $rule->calc_rule_name . '</td>';
		if (VmConfig::get(' show_tax ')) {
			echo '<td style="text-align: right;">&nbsp;</td>';
		}
		// echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';
		echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';

		echo '</tr>';
	} elseif ($rule->calc_kind == ' taxRulesBill ') {
		echo '<tr>';

		echo '<td colspan="4" style="text-align: right;" class="pricePad">' . $rule->calc_rule_name . '</td>';
		if (VmConfig::get(' show_tax ')) {
			echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';
		}
		// echo '<td style="text-align: right;">&nbsp;</td>';
		echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';

		echo '</tr>';
	} elseif ($rule->calc_kind == ' DATaxRulesBill ') {
		echo '<tr>';

		echo '<td colspan="4" style="text-align: right;" class="pricePad">' . $rule->calc_rule_name . '</td>';
		if (VmConfig::get(' show_tax ')) {
			echo '<td style="text-align: right;">&nbsp;</td>';
		}
		// echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';
		echo '<td style="text-align: right;">' . $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id) . '</td>';

		echo '</tr>';
	}
}

echo '<tr>';

echo '<td style="text-align: right;" class="pricePad" colspan="4">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPPING') . '</td>';
if (VmConfig::get(' show_tax ')) {
	echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_shipment_tax, $this->user_currency_id) . '</span></td>';
}
// echo '<td style="text-align: right;">&nbsp;</td>';
echo '<td style="text-align: right;">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_shipment + $this->orderdetails['details']['BT']->order_shipment_tax, $this->user_currency_id) . '</td>';

echo '</tr>';

echo '<tr>';

echo '<td style="text-align: right;" class="pricePad" colspan="4">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT') . '</td>';

if (VmConfig::get(' show_tax ')) {
	echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_payment_tax, $this->user_currency_id) . '</span></td>';
}
// echo '<td style="text-align: right;">&nbsp;</td>';
echo '<td style="text-align: right;">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_payment + $this->orderdetails['details']['BT']->order_payment_tax, $this->user_currency_id) . '</td>';

echo '</tr>';

echo '<tr>';

echo '<td style="text-align: right;" class="pricePad" colspan="4"><strong>' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') . '</strong></td>';

if (VmConfig::get(' show_tax ')) {
	echo '<td style="text-align: right;"><span class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_billTaxAmount, $this->user_currency_id) . '</span></td>';
}
// echo '<td style="text-align: right;"><span class="priceColor2">' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_billDiscountAmount, $this->user_currency_id) . '</span></td>';
echo '<td style="text-align: right;"><strong>' . $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_total, $this->user_currency_id) . '</strong></td>';

echo '</tr>';

echo '</table>';
