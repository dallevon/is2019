<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 9821 2018-04-16 18:04:39Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

$productModel = VmModel::getModel('product');
$user = JFactory::getUser();
$user_id = ($user->get('id'));
$user_registered = $user_id !== 0;

$app = JFactory::getApplication();
$path = JURI::base(true) . '/templates/' . $app->getTemplate() . '/';
$document = JFactory::getDocument();
$document->addScript($path . 'js/vm-helpers.js');
$document->addScript($path . 'js/owl.carousel.min.js');



echo shopFunctionsF::renderVmSubLayout('askrecomjs', array('product' => $this->product));



if (vRequest::getInt('print', false)) {
	echo '<body onload="javascript:print();">';
}

echo '<div class="is-product-container is-productdetails-view is-productdetails">';
// Product Header
echo '<div class="is-product-navigation">';
// Product Navigation
if (VmConfig::get('product_navigation', 1)) {
	echo '<div class="is-product-neighbours is-previous-page">';
	if (!empty($this->product->neighbours['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours['previous'][0]['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, false);
		echo '<a href="' . $prev_link . '" rel="prev" class="is-previous-page-link" data-dynamic-update="1"><i class="fa fa-arrow-left fa-fw"></i><span class="product-name">' . $this->product->neighbours['previous'][0]['product_name'] . '</span></a>';
		// echo JHtml::_('link', $prev_link, $this->product->neighbours['previous'][0]['product_name'], array('rel' => 'prev', 'class' => 'previous-page fa fa-arrow-left', 'data-dynamic-update' => '1'));
	}
	echo '</div>';
	echo '<div class="is-product-neighbours is-next-page">';
	if (!empty($this->product->neighbours['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours['next'][0]['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, false);
		echo '<a href="' . $next_link . '" rel="next" class="is-next-page-link" data-dynamic-update="1"><span class="product-name">' . $this->product->neighbours['next'][0]['product_name'] . '</span><i class="fa fa-arrow-right fa-fw"></i></a>';
		// echo JHtml::_('link', $next_link, $this->product->neighbours['next'][0]['product_name'], array('rel' => 'next', 'class' => 'next-page', 'data-dynamic-update' => '1'));
	}
	echo '</div>';
}
// Product Navigation END

// Back To Category Button
if ($this->product->virtuemart_category_id) {
	$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='  . $this->product->virtuemart_category_id, false);
	$categoryName = vmText::_($this->product->category_name);
} else {
	$catURL =  JRoute::_('index.php?option=com_virtuemart');
	$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME');
}
echo '<div class="is-back-to-category"><a href="' . $catURL . '" class="is-back-to-product-category button button-bevel button-xsmall" title="' . $categoryName . '">' . vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO', $categoryName) . '</a></div>';

echo '</div>';
// Product Header END


echo '<h1 class="is-product-name">' . $this->product->product_name . ' ';
// Product Short Description
if (!empty($this->product->product_s_desc) && ($this->product->product_parent_id != 0)) {
	echo '<span class="is-product-short-description">' . nl2br($this->product->product_s_desc) . '</span>';
} // Product Short Description END
echo '</h1>';
// afterDisplayTitle Event
echo $this->product->event->afterDisplayTitle;


// Product Edit Link
echo $this->edit_link;
// Product Edit Link END

// PDF - Print - Email Icon
if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
	echo '<div class="icons">';
	$link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

	echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
	//echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
	echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon', false, true, false, 'class="printModal"');
	$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
	echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false, true, false, 'class="recommened-to-friend"');
	echo '</div>';
}


echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'ontop'));


echo '<div class="is-vm-product-container">';

// Product Media Container
echo '<div class="is-vm-product-media-container">' . $this->loadTemplate('images');
$count_images = count($this->product->images);
if ($count_images > 1) {
	echo $this->loadTemplate('images_additional');
}
echo '</div>';
// Product Media Container END

echo '<div class="is-vm-product-details-container vm-product-details-container">';

// Product Description
if (!empty($this->product->product_desc)) {
	echo '<div class="is-product-description"><span class="title">' . vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') . ': </span>' .  trim($this->product->product_desc, ':') . '</div>';
} // Product Description END

echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'normal'));


$in_stock = $this->product->product_in_stock - $this->product->product_ordered;

if ($user_registered) {
	$stock = $productModel->getStockIndicator($this->product);
	if (VmConfig::get('display_stock', 1)) {
		echo	'<div class="is-stock-level">';
		echo '<span class="is-vmicon is-vm2-' . $stock->stock_level . '" title="' . $stock->stock_tip . '"></span>';
		echo '<p class="is-stock-level-description">' . $stock->stock_tip . '</p>';
		echo '</div>';

		echo shopFunctionsF::renderVmSubLayout('stockhandle', array('product' => $this->product));
	}

	if ($in_stock > 0) {
		echo '<p><strong>' . vmText::sprintf('COM_VIRTUEMART_STOCK_QUANTITY_PRODUCT_IN_STOCK', $in_stock) . '</strong></p>';
	} else {
		// do nothing
		echo '<p><strong>' . vmText::_('IS_STOCK_HANDLE_OUT_OFF_STOCK_MESSAGE') . '</strong></p>';
	}
	//In case you are not happy using everywhere the same price display fromat, just create your own layout
	//in override /html/fields and use as first parameter the name of your file
	echo shopFunctionsF::renderVmSubLayout('prices', array('product' => $this->product, 'currency' => $this->currency));
}

echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $this->product, 'authorized' => $user_registered, 'in_stock' => $in_stock));




// Product Raiting
echo '<div class="is-product-rating">';
echo shopFunctionsF::renderVmSubLayout('rating', array('showRating' => $this->showRating, 'product' => $this->product));
foreach ($this->productDisplayTypes as $type => $productDisplayType) {
	foreach ($productDisplayType as $productDisplay) {
		foreach ($productDisplay as $virtuemart_method_id => $productDisplayHtml) {
			echo '<div class="is ' . substr($type, 0, -1) . substr($type, 0, -1) . '-' . $virtuemart_method_id / '">' . $productDisplayHtml . '</div>';
		}
	}
}
echo '</div>';
// Product Raiting END

// Ask a question about this product
if (VmConfig::get('ask_question', 0) == 1) {
	$askquestion_url =  JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id  . '&tmpl=component', false);
	echo '<div class="is-ask-a-question"><a class="is-ask-a-question-link-- button button-outline button-xsmall button-bevel" target="new" href=".' . $askquestion_url . '" rel="nofollow">' . vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') . '</a></div>';
}
// Ask a question about this product END

// Manufacturer of the Product
if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
	echo $this->loadTemplate('manufacturer');
}
// Manufacturer of the Product END

// event onContentBeforeDisplay
echo $this->product->event->beforeDisplayContent;



// Product Packaging
$product_packaging =  '';
if ($this->product->product_box) {
	echo '<div class="is-product-box">' . vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') . $this->product->product_box . '</div>';
}
// Product Packaging END

echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'onbot'));

echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'related_products', 'class' => 'product-related-products', 'customTitle' => true));

echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'related_categories', 'class' => 'product-related-categories'));

echo $this->product->event->afterDisplayContent;

echo  $this->loadTemplate('reviews');

echo '</div></div></div>';

// Show child categories
if ($this->cat_productdetails) {
	echo $this->loadTemplate('showcategory');
}

$j = 'jQuery(document).ready(function($) {
	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

if (VmConfig::get('jdynupdate', true)) {

	/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
	$j = "Virtuemart.container = jQuery('.is-productdetails-view');
Virtuemart.containerSelector = '.is-productdetails-view';
Virtuemart.recalculate = true;	//Activate this line to recalculate your product after ajax
";

	vmJsApi::addJScript('ajaxContent', $j);

	$j = "jQuery(document).ready(function($) {
	Virtuemart.stopVmLoading();
	var msg = '';
	$('a[data-dynamic-update=\"1\"]').off('click', Virtuemart.startVmLoading).on('click', {msg:msg}, Virtuemart.startVmLoading);
	$('[data-dynamic-update=\"1\"]').off('change', Virtuemart.startVmLoading).on('change', {msg:msg}, Virtuemart.startVmLoading);
});";

	vmJsApi::addJScript('vmPreloader', $j);
}

$j = "jQuery(document).ready(function($) {
	$(document).ready(function () {
		$('.is-product-field-type-A .controls').addClass('owl-carousel owl-theme').owlCarousel({
			items: 4,
			nav: !0,
			autoplay: true

		});
	});
});";
// vmJsApi::addJScript('childProductsCarousel', $j);

echo vmJsApi::writeJS();

if ($this->product->prices['salesPrice'] > 0) {
	echo shopFunctionsF::renderVmSubLayout('snippets', array('product' => $this->product, 'currency' => $this->currency, 'showRating' => $this->showRating));
}
echo '</div>';
