<?php
/**
*
* Layout for the add to cart popup
*
* @package	VirtueMart
* @subpackage Cart
* @author Max Milbers
*
* @link https://virtuemart.net
* @copyright Copyright (c) 2013 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$menu = $app->getMenu();
$menuItem = $menu->getItems('link', 'index.php?option=com_virtuemart&view=cart', true);
$menuItemId = 142; // hardcoded value as a fallback
if (empty($menuItem)) {
	$menuItemId = $menuItem->id;
}



echo '<div class="is-cart-popup">';
echo '<div class="is-cart-popup-links">';
echo '<a class="is-continue_link button button-bevel button-outline button-xsmall" href="' . $this->continue_link . '" >' . vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';
echo '<a class="is-showcart button button-bevel button-outline button-xsmall" href="' . $this->cart_link . '&Itemid=' . $menuItemId . '">' . vmText::_('COM_VIRTUEMART_CART_SHOW') . '</a>';
echo '</div>';

if ($this->products) {
	foreach ($this->products as $product) {
		if ($product->quantity > 0) {
			echo '<h5>' . vmText::sprintf('COM_VIRTUEMART_CART_PRODUCT_ADDED', $product->product_name . ' ' . $product->product_s_desc, $product->quantity) . '</h5>';
		}
		if (!empty($product->errorMsg)) {
			echo '<div>' . $product->errorMsg . '</div>';
		}
	}
}


if (VmConfig::get('popup_rel', 1)) {
	if ($this->products and is_array($this->products) and count($this->products) > 0) {

		$product = reset($this->products);

		$customFieldsModel = VmModel::getModel('customfields');
		$product->customfields = $customFieldsModel->getCustomEmbeddedProductCustomFields($product->allIds, 'R');

		$customFieldsModel->displayProductCustomfieldFE($product, $product->customfields);
		if (!empty($product->customfields)) {
			echo '<div class="product-related-products">';
			echo '<h4>' . vmText::_('COM_VIRTUEMART_RELATED_PRODUCTS') . '</h4>';
			foreach ($product->customfields as $rFields) {

				if (!empty($rFields->display)) {
					echo '<div class="product-field product-field-type-' . $rFields->field_type . '">';
					echo '<div class="product-field-display">' . $rFields->display . '</div>';
					echo '</div>';
				}
			}
			echo '</div>';
		}
	}
}
echo '</div>';

$j = 'jQuery(document).ready(function($) {
	$(".is-cart-popup-links .is-continue_link")
	.on("click", function(e){
		e.preventDefault();
		if ($.fancybox && typeof $.fancybox.close === "function") {
			$.fancybox.close()
		} else {
			$(document).trigger("close.facebox");
		}
		location.reload(true);
	});
});';
vmJsApi::addJScript('closePopup', $j);
