<?php
/**
 * sublayout products
 *
 * @package    VirtueMart
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];
$position = $viewData['position'];
$customTitle = isset($viewData['customTitle']) ? $viewData['customTitle'] : false;
$hideTitle = isset($viewData['hideTitle']) ? $viewData['hideTitle'] : false;

if (isset($viewData['class'])) {
	$class = $viewData['class'];
} else {
	$class = 'is-product-fields';
}
if (!empty($product->customfieldsSorted[$position])) {
	echo '<div class="' . $class . '">';
	if ($customTitle and isset($product->customfieldsSorted[$position][0])) {
		$field = $product->customfieldsSorted[$position][0];
		echo '<div class="is-product-fields-title-wrapper">';
		echo '<span class="is-product-fields-title"><strong>' . vmText::_($field->custom_title) . '</strong></span>';
		if ($field->custom_tip) {
			echo JHtml::tooltip(vmText::_($field->custom_tip), vmText::_($field->custom_title), 'tooltip.png');
		}
		echo '</div>';
	}
	$custom_title = null;
	foreach ($product->customfieldsSorted[$position] as $field) {
		if ($field->is_hidden || empty($field->display)) {
			continue;
		}
		//OSP http://forum.virtuemart.net/index.php?topic=99320.0
		echo '<div class="is-product-field is-product-field-type-' . $field->field_type . '">';
		if (!$hideTitle and !$customTitle and $field->custom_title != $custom_title and $field->show_title) {
			echo '<span class="is-product-fields-title-wrapper">';
			echo '<span class="is-product-fields-title"><strong>' . vmText::_($field->custom_title) . '</strong></span>';
			echo '</span>';
		}
		if (!empty($field->display)) {
			echo '<div class="is-product-field-display">' . $field->display . '</div>';
		}
		if (!empty($field->custom_desc)) {
			echo '<div class="is-product-field-desc">' . vmText::_($field->custom_desc) . '</div>';
		}
		echo '</div>';
		$custom_title  = $field->custom_title;
	}
	echo '</div>';
}
