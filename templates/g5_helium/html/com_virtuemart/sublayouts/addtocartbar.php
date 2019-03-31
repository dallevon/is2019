<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers
 * @todo handle child products
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];

if (isset($viewData['rowHeights'])) {
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = true;
}

$init = 1;
if (isset($viewData['init'])) {
	$init = $viewData['init'];
}

if (!empty($product->min_order_level) and $init < $product->min_order_level) {
	$init = $product->min_order_level;
}

$step = 1;
if (!empty($product->step_order_level)) {
	$step = $product->step_order_level;
	if (!empty($init)) {
		if ($init < $step) {
			$init = $step;
		} else {
			$init = ceil($init / $step) * $step;
		}
	}
	if (empty($product->min_order_level) and !isset($viewData['init'])) {
		$init = $step;
	}
}

$in_stock = $product->product_in_stock - $product->product_ordered;

$maxOrder = '';
if (!empty($product->max_order_level)) {
	$maxOrder = min($product->max_order_level, $in_stock);
} else {
	$maxOrder = $in_stock;
}

$cartProductsData = VirtueMartCart::getCart()->cartProductsData;
$quantitiyInCart = 0;

foreach ($cartProductsData as $cartProductData) {
	if ($product->virtuemart_product_id == $cartProductData['virtuemart_product_id']) {
		$quantitiyInCart += $cartProductData['quantity'];
	}
};
$maxOrder = $maxOrder - $quantitiyInCart;
$allowMore = $maxOrder > 0;

$maxOrder = ' max="' . $maxOrder . '" ';

$addtoCartButton = '';
if (!VmConfig::get('use_as_catalog', 0)) {
	if (!$product->addToCartButton and $product->addToCartButton !== '') {
		$addtoCartButton = self::renderVmSubLayout('addtocartbtn', array('orderable' => $product->orderable && $allowMore));
	} else {
		$addtoCartButton = $product->addToCartButton;
	}
}
$position = 'addtocart';

if ($product->min_order_level > 0) {
	$minOrderLevel = $product->min_order_level;
} else {
	$minOrderLevel = 1;
}


if (!VmConfig::get('use_as_catalog', 0)) {

	echo '<div class="is-addtocart-bar">';
	// Display the quantity box
	$stockhandle = VmConfig::get('stockhandle_products', false) && $product->product_stockhandle ? $product->product_stockhandle : VmConfig::get('stockhandle', 'none');
	if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and $in_stock < $minOrderLevel) {
		echo '<a href="' . JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id) . '" class="notify">' . vmText::_('COM_VIRTUEMART_CART_NOTIFY') . '</a>';
	} else {
		$tmpPrice = (float)$product->prices['costPrice'];
		if (!(VmConfig::get('askprice', true) and empty($tmpPrice))) {
			$editable = 'hidden';
			if ($product->orderable) {
				$editable = 'text';
			}

			if ($product->orderable && $allowMore) {
				echo '<span class="quantity-box"><input type="' . $editable . '" class="quantity-input js-recalculate" name="quantity[]" data-errStr="' . vmText::_('COM_VIRTUEMART_WRONG_AMOUNT_ADDED') . '" value="' . $init . '" init="' . $init . '" step="' . $step . '" ' . $maxOrder . ' /></span>';
				echo '<span class="quantity-controls js-recalculate"><button type="button" class="quantity-control quantity-plus"><span class=" fa fa-plus-circle fw"></span></button><button type="button" class="quantity-control quantity-minus"><span class="fa fa-minus-circle fw"></span></button></span>';
			}

			if (!empty($addtoCartButton)) {
				echo '<span class="addtocart-button">' . $addtoCartButton . '</span>';
			}


			echo '<input type="hidden" name="virtuemart_product_id[]" value="' . $product->virtuemart_product_id . '" />';
			echo '<noscript><input type="hidden" name="task" value="add" /></noscript>';
		}
	}

	echo '</div>';
	if (!$allowMore) {
		echo '<p class="nomore">' . vmText::_('IS_COM_VIRTUEMART_ALREADY_MAXEDOUT') . '</p>';
	}
}
