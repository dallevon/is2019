<?php
/**
 * Layout for the shopping cart
 *
 * @package    VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2016 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$path = JURI::base(true) . '/templates/' . $app->getTemplate() . '/';
$document = JFactory::getDocument();
$document->addScript($path . 'js/vm-helpers.js');


echo '<fieldset class="vm-fieldset-pricelist">';
echo '<table class="cart-summary" cellspacing="0" cellpadding="0" border="0" width="100%">';
echo '<tr class="tablerow hidden-phone">';
echo '<th class="vm-cart-item-name">' . vmText::_('COM_VIRTUEMART_CART_NAME') . '</th>';
// echo '<th class="vm-cart-item-sku" >'.vmText::_('COM_VIRTUEMART_CART_SKU').'</th>';
echo '<th class="vm-cart-item-basicprice">' . vmText::_('COM_VIRTUEMART_CART_PRICE') . '</th>';
echo '<th class="vm-cart-item-quantity">' . vmText::_('COM_VIRTUEMART_CART_QUANTITY') . '</th>';

if (VmConfig::get('show_tax')) {
	$tax = vmText::_('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT');
	if (!empty($this->cart->cartData['VatTax'])) {
		if (count($this->cart->cartData['VatTax']) < 2) {
			reset($this->cart->cartData['VatTax']);
			$taxd = current($this->cart->cartData['VatTax']);
			$tax = shopFunctionsF::getTaxNameWithValue($taxd['calc_name'], $taxd['calc_value']);
		}
	}
	echo '<th class="vm-cart-item-tax"><span  class="priceColor2">' . $tax . '</span></th>';
}

// echo '<th class="vm-cart-item-discount" ><span  class="priceColor2">' . vmText::_('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') . '</span></th>';
echo '<th class="vm-cart-item-total">' . vmText::_('COM_VIRTUEMART_CART_TOTAL') . '</th>';
echo '</tr>';

$i = 1;

foreach ($this->cart->products as $pkey => $prow) {
	$prow->prices = array_merge($prow->prices, $this->cart->cartPrices[$pkey]);

	echo '<tr style="vertical-align: top" class="tablerow sectiontableentry' . $i . '">';
	echo '<td class="vm-cart-item-name">';
	echo '<input type="hidden" name="cartpos[]" value="' . $pkey . '">';
	echo '<span class="mobiletitle name visible-phone" style="display: none;">' . vmText::_('COM_VIRTUEMART_CART_NAME') . '</span>';
	$image = '';
	if ($prow->virtuemart_media_id) {
		$image = '<span class="cart-images is-cart-image">';
		if (!empty($prow->images[0])) {
			$image .= $prow->images[0]->displayMediaThumb('', false);
		}
		$image .= '</span>';
	}
	echo JHtml::link($prow->url, $image . '<span>' . $prow->product_name . ' <small>' . $prow->product_s_desc . '</small></span>');
	echo '</td>';
	// echo '<td class="vm-cart-item-sku">'.$prow->product_sku.'</td>';
	echo '<td class="vm-cart-item-basicprice">';
	echo '<span class="mobiletitle visible-phone" style="display: none;">' . vmText::_('COM_VIRTUEMART_CART_PRICE') . '</span>';

	if (VmConfig::get('checkout_show_origprice', 1) && $prow->prices['discountedPriceWithoutTax'] != $prow->prices['priceWithoutTax']) {
		echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv('basePriceVariant', '', $prow->prices, true, false) . '</span><br />';
	}
	if ($prow->prices['discountedPriceWithoutTax']) {
		echo $this->currencyDisplay->createPriceDiv('discountedPriceWithoutTax', '', $prow->prices, false, false, 1.0, false, true);
	} else {
		echo $this->currencyDisplay->createPriceDiv('basePriceVariant', '', $prow->prices, false, false, 1.0, false, true);
	}
	echo '</td>';
	echo '<td class="vm-cart-item-quantity">';
	echo '<span class="mobiletitle visible-phone" style="display: none;">' . vmText::_('COM_VIRTUEMART_CART_QUANTITY') . '</span>';
	echo '<div>';
	if ($prow->step_order_level) {
		$step = $prow->step_order_level;
	} else {
		$step = 1;
	}
	if ($step == 0) {
		$step = 1;
	}
	$available = $prow->product_in_stock - $prow->product_ordered;
	$over_available_message = '';
	if ($available < 0) {
		$available = 0;
		$over_available_message = vmText::sprintf('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_OUT_TIP');
	} else {
		$over_available_message = vmText::sprintf('COM_VIRTUEMART_OVER_AVAILABLE_AMOUNT_ADDED', $available);
	}
	$wrong_amount_message = vmText::sprintf('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', $step);

	?>

	<input type="text" onblur="Virtuemart.checkQuantityInCart(this,<?php echo $step ?>,<?php echo $available ?>,'<?php echo  $wrong_amount_message ?>', '<?php echo $over_available_message; ?>');" onsubmit="Virtuemart.checkQuantityInCart(this,<?php echo $step ?>,<?php echo $available ?>,'<?php echo  $wrong_amount_message ?>', '<?php echo $over_available_message; ?>');" title="<?php echo vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="quantity-input js-recalculate" size="3" maxlength="4" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>" min="<?php echo $step; ?>" />

	<button type="submit" class="update-button fas fa-sync-alt button button-bevel button-outline button-xsmal" name="updatecart.<?php echo $pkey ?>" title="<?php echo vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" data-dynamic-update="1"></button>
	<button type="submit" class="delete-button fas fa-trash-alt button button-bevel button-outline button-xsmal" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_('COM_VIRTUEMART_CART_DELETE') ?>"></button>

	<?php
	echo '<div>';

	echo '</td>';


	if (VmConfig::get('show_tax')) {
		echo '<td class="vm-cart-item-tax"><span class="priceColor2">' . '<span class="mobiletitle visible-phone" style="display: none;">' . $tax . '</span>' .
			$this->currencyDisplay->createPriceDiv('taxAmount', '', $prow->prices, false, false, $prow->quantity, false, true) . '</span></td>';
	}

	// echo '<td class="vm-cart-item-discount"><span class="priceColor2">' . $this->currencyDisplay->createPriceDiv('discountAmount', '', $prow->prices, false, false, $prow->quantity, false, true) . '</span></td>';

	echo '<td class="vm-cart-item-total">';
	echo '<span class="mobiletitle visible-phone" style="display: none;">' . vmText::_('COM_VIRTUEMART_CART_TOTAL') . '</span>';
	if (VmConfig::get('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
		echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv('basePriceWithTax', '', $prow->prices, true, false, $prow->quantity) . '</span><br />';
	} elseif (VmConfig::get('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && !empty($prow->prices['basePriceVariant']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
		echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv('basePriceVariant', '', $prow->prices, true, false, $prow->quantity) . '</span><br />';
	}
	echo $this->currencyDisplay->createPriceDiv('salesPrice', '', $prow->prices, false, false, $prow->quantity);
	echo '</td>';
	echo '</tr>';

	$i = ($i == 1) ? 2 : 1;
}


// Begin of SubTotal, Tax, Shipment, Coupon Discount and Total listing
$first_colspan = 3;
if (VmConfig::get('show_tax')) {
	$colspan = 2;
} else {
	$colspan = 1;
}
echo '<tr class="tablerow spacer"><td colspan="' . $first_colspan . '">&nbsp;</td><td colspan="' . $colspan . '"><hr /></td></tr>';

echo '<tr class="tablerow prices-total" sectiontableentry1">';
echo '<td colspan="' . $first_colspan . '" style="text-align: right;" class="prices-total-title">' . vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL') . '</td>';
if (VmConfig::get('show_tax')) {
	echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currencyDisplay->createPriceDiv('taxAmount', '', $this->cart->cartPrices, false, false, true) . '</span></td>';
}

// echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currencyDisplay->createPriceDiv('discountAmount', '', $this->cart->cartPrices, false) . '</span></td>';

echo '<td style="text-align: right;" class="prices-total-value">' . $this->currencyDisplay->createPriceDiv('salesPrice', '', $this->cart->cartPrices, false) . '</td>';

echo '</tr>';

if (VmConfig::get('coupons_enable')) {
	echo '<tr class="tablerow sectiontableentry2">';
	echo '<td colspan="' . $first_colspan . '" style="text-align: left;">';
	if (!empty($this->layoutName) && $this->layoutName == $this->cart->layout) {
		echo $this->loadTemplate('coupon');
	}
	if (!empty($this->cart->cartData['couponCode'])) {
		echo $this->cart->cartData['couponCode'];
		echo $this->cart->cartData['couponDescr'] ? (' (' . $this->cart->cartData['couponDescr'] . ')') : '';
		echo '</td>';
		if (VmConfig::get('show_tax')) {
			echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv('couponTax', '', $this->cart->cartPrices['couponTax'], false) . '</td>';
		}
		// <!--<td style="text-align: right;">&nbsp;</td> -->
		echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv('salesPriceCoupon', '', $this->cart->cartPrices['salesPriceCoupon'], false) . '</td>';
	} else {
		echo '&nbsp;</td>';
		echo '<td colspan="' . $colspan . '" style="text-align: left;">&nbsp;</td>';
	}
	echo '</tr>';
}

foreach ($this->cart->cartData['DBTaxRulesBill'] as $rule) {
	echo '<tr class="tablerow sectiontableentry' . $i . '">';
	echo '<td colspan="' . $first_colspan . '" style="text-align: right;">' . $rule['calc_name'] . '</td>';
	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;"></td>';
	}
	echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '&nbsp;</td>';
	// echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '&nbsp;</td>';
	echo '</tr>';
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}


foreach ($this->cart->cartData['taxRulesBill'] as $rule) {
	if ($rule['calc_value_mathop'] == 'avalara') continue;
	echo '<tr class="tablerow sectiontableentry' . $i . '">';
	echo '<td colspan="' . $first_colspan . '" style="text-align: right;">' . $rule['calc_name'] . '</td>';
	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '&nbsp;</td>';
	}
	// <!-- <td style="text-align: right;"></td> -->
	echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '&nbsp;</td>';
	echo '</tr>';
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}

foreach ($this->cart->cartData['DATaxRulesBill'] as $rule) {
	echo '<tr class="tablerow sectiontableentry' . $i . '">';
	echo '<td colspan="' . $first_colspan . '" style="text-align: right;">' . $rule['calc_name'] . '</td>';
	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;">&nbsp;</td>';
	}
	echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '</td>';
	// echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], false) . '</td>';
	echo '</tr>';
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}

if (
	VmConfig::get('oncheckout_opc', true) or
	!VmConfig::get('oncheckout_show_steps', false) or (!VmConfig::get('oncheckout_opc', true) and VmConfig::get('oncheckout_show_steps', false) and
		!empty($this->cart->virtuemart_shipmentmethod_id))
) {
	echo '<tr class="tablerow shipment sectiontableentry1" style="vertical-align:top;">';
	if (!$this->cart->automaticSelectedShipment) {
		echo '<td colspan="' . $first_colspan . '" style="align:left;vertical-align:top;">';
		echo '<h3>' . vmText::_('COM_VIRTUEMART_CART_SELECTED_SHIPMENT') . '</h3>';
		echo $this->cart->cartData['shipmentName'] . '<br/>';
		if (!empty($this->layoutName) and $this->layoutName == $this->cart->layout) {
			if (VmConfig::get('oncheckout_opc', 0)) {
				$previouslayout = $this->setLayout('select');
				echo $this->loadTemplate('shipment');
				$this->setLayout($previouslayout);
			} else {
				echo JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=cart&task=edit_shipment', $this->useXHTML, $this->useSSL), $this->select_shipment_text, 'class=""');
			}
		} else {
			echo vmText::_('COM_VIRTUEMART_CART_SHIPPING');
		}
		echo '</td>';
	} else {
		echo '<td colspan="' . $first_colspan . '" style="align:left;vertical-align:top;">';
		echo '<h3>' . vmText::_('COM_VIRTUEMART_CART_SELECTED_SHIPMENT') . '</h3>';
		echo $this->cart->cartData['shipmentName'];
		echo '<span class="floatright">' . $this->currencyDisplay->createPriceDiv('shipmentValue', '', $this->cart->cartPrices['shipmentValue'], false) . '</span>';
		echo '</td>';
	}

	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;"><span class="priceColor2">' . $this->currencyDisplay->createPriceDiv('shipmentTax', '', $this->cart->cartPrices['shipmentTax'], false) . '</span></td>';
	}
	// echo '<td style="text-align: right;">';
	if ($this->cart->cartPrices['salesPriceShipment'] < 0) echo $this->currencyDisplay->createPriceDiv('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'], false);
	echo '</td>';
	echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'], false) . '</td>';
	echo '</tr>';
}
if (
	$this->cart->pricesUnformatted['salesPrice'] > 0.0 and (VmConfig::get('oncheckout_opc', true) or
		!VmConfig::get('oncheckout_show_steps', false) or ((!VmConfig::get('oncheckout_opc', true) and VmConfig::get('oncheckout_show_steps', false)) and !empty($this->cart->virtuemart_paymentmethod_id)))
) {
	echo '<tr class="tablerow payment sectiontableentry1" style="vertical-align:top;">';
	if (!$this->cart->automaticSelectedPayment) {
		echo '<td colspan="' . $first_colspan . '" style="align:left;vertical-align:top;">';
		echo '<h3>' . vmText::_('COM_VIRTUEMART_CART_SELECTED_PAYMENT') . '</h3>';
		echo $this->cart->cartData['paymentName'] . '<br/>';
		if (!empty($this->layoutName) && $this->layoutName == $this->cart->layout) {
			if (VmConfig::get('oncheckout_opc', 0)) {
				$previouslayout = $this->setLayout('select');
				echo $this->loadTemplate('payment');
				$this->setLayout($previouslayout);
			} else {
				echo JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=cart&task=editpayment', $this->useXHTML, $this->useSSL), $this->select_payment_text, 'class=""');
			}
		} else {
			echo vmText::_('COM_VIRTUEMART_CART_PAYMENT');
		}
		echo '</td>';
	} else {
		echo '<td colspan="3" style="align:left;vertical-align:top;">';
		echo '<h3>' . vmText::_('COM_VIRTUEMART_CART_SELECTED_PAYMENT') . '</h3>';
		echo $this->cart->cartData['paymentName'];
		echo '</td>';
	}
	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currencyDisplay->createPriceDiv('paymentTax', '', $this->cart->cartPrices['paymentTax'], false) . '</span></td>';
	}
	// echo '<td style="text-align: right;">';
	if ($this->cart->cartPrices['salesPricePayment'] < 0) echo $this->currencyDisplay->createPriceDiv('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], false) . '</td>';
	echo '<td style="text-align: right;">' . $this->currencyDisplay->createPriceDiv('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], false) . '</td>';
	echo '</tr>';
}

echo '<tr class="tablerow spacer"><td colspan="' . $first_colspan . '">&nbsp;</td><td colspan="' . $colspan . '"><hr /></td></tr>';

echo '<tr class="tablerow cart-total sectiontableentry2">';
echo '<td colspan="' . $first_colspan . '" style="text-align: right;" class="cart-total-title">' . vmText::_('COM_VIRTUEMART_CART_TOTAL') . ':</td>';
if (VmConfig::get('show_tax')) {
	echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currencyDisplay->createPriceDiv('billTaxAmount', '', $this->cart->cartPrices['billTaxAmount'], false) . '</span></td>';
}
// echo '<td style="text-align: right;"><span  class="priceColor2">' . $this->currencyDisplay->createPriceDiv('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], false) . '</span></td>';
echo '<td style="text-align: right;" class="cart-total-value"><strong>' . $this->currencyDisplay->createPriceDiv('billTotal', '', $this->cart->cartPrices['billTotal'], false) . '</strong></td>';
echo '</tr>';

if ($this->totalInPaymentCurrency) {
	echo '<tr class="tablerow sectiontableentry2">';
	echo '<td colspan="' . $first_colspan . '" style="text-align: right;">' . vmText::_('COM_VIRTUEMART_CART_TOTAL_PAYMENT') . ':</td>';
	if (VmConfig::get('show_tax')) {
		echo '<td style="text-align: right;">&nbsp;</td>';
	}
	// echo '<td style="text-align: right;">&nbsp;</td>';
	echo '<td style="text-align: right;"><strong>' . $this->totalInPaymentCurrency . '</strong></td>';
	echo '</tr>';
}

//Show VAT tax separated
if (!empty($this->cart->cartData)) {
	if (!empty($this->cart->cartData['VatTax'])) {
		$c = count($this->cart->cartData['VatTax']);
		if (!VmConfig::get('show_tax') or $c > 1) {
			if ($c > 0) {

				echo '<tr class="tablerow sectiontableentry2">';
				echo '<td colspan="' . ($first_colspan - 1) . '">&nbsp;</td>';
				echo '<td colspan="2" style="text-align: left;border-bottom: 1px solid #333;">' . vmText::_('COM_VIRTUEMART_TOTAL_INCL_TAX') . '</td>';
				if (VmConfig::get('show_tax')) {
					echo '<td>&nbsp;</td>';
				}
				// echo '<td>&nbsp;</td>';
				echo '</tr>';
			}
			foreach ($this->cart->cartData['VatTax'] as $vatTax) {
				if (!empty($vatTax['result'])) {
					echo '<tr class="tablerow sectiontableentry' . $i . '">';
					echo '<td colspan="' . ($first_colspan - 1) . '">&nbsp;</td>';
					echo '<td style="text-align: right;">' . shopFunctionsF::getTaxNameWithValue($vatTax['calc_name'], $vatTax['calc_value']) . '</td>';
					echo '<td style="text-align: right;"><span class="priceColor2">' . $this->currencyDisplay->createPriceDiv('taxAmount', '', $vatTax['result'], false, false, 1.0, false, true) . '</span></td>';
					if (VmConfig::get('show_tax')) {
						echo '<td>&nbsp;</td>';
					}
					// echo '<td>&nbsp;</td>';
					echo '</tr>';
				}
			}
		}
	}
}
echo '</table>';
echo '</fieldset>';
