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

if (!function_exists('cleanUpCustomFieldDisplay')) {
	function cleanUpCustomFieldDisplay($field)
	{
		// print_r($field);
		switch ($field->field_type) {
			case 'S': {
					switch ($field->virtuemart_custom_id) {
						case 3:
							return '<span class="composition">' . preg_replace('/^[A-Z]-/', '', $field->display) . '</span>';

						default:
							return $field->display;
					}
				}
				break;
			default:
				return $field->display;
		}
	}
}

if (isset($viewData['class'])) {
	$class = $viewData['class'];
} else {
	$class = 'is-product-fields';
}
if (!empty($product->customfieldsSorted[$position])) {
	echo '<div class="' . $class . '">';
	if ($customTitle and isset($product->customfieldsSorted[$position][0])) {
		$field = $product->customfieldsSorted[$position][0];
		echo '<span class="is-product-fields-title">' . vmText::_($field->custom_title) . '</span>';
		if ($field->custom_tip) {
			echo JHtml::tooltip(vmText::_($field->custom_tip), vmText::_($field->custom_title), 'tooltip.png');
		}
	}
	$custom_title = null;
	foreach ($product->customfieldsSorted[$position] as $field) {
		if ($field->is_hidden || empty($field->display)) {
			continue;
		}
		// if ($position == 'addtocart') {
		// 	print_r($field);
		// echo $position;
		// };
		//OSP http://forum.virtuemart.net/index.php?topic=99320.0
		echo '<div class="is-product-field is-product-field-type-' . $field->field_type . '">';
		if (!$hideTitle and !$customTitle and $field->custom_title != $custom_title and $field->show_title) {
			echo '<span class="is-product-fields-title">' . vmText::_($field->custom_title) . ': </span>';
		}
		if (!empty($field->display)) {
			echo '<span class="is-product-field-display">' . cleanUpCustomFieldDisplay($field) . '</span>';
		}
		if (!empty($field->custom_desc)) {
			echo '<span class="is-product-field-desc">' . vmText::_($field->custom_desc) . '</span>';
		}
		echo '</div>';
		$custom_title  = $field->custom_title;
	}
	echo '</div>';
}
