<?php
/**
 * sublayout products
 *
 * @package	VirtueMart
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');

$user   = JFactory::getUser();
$user_id = ($user->get('id'));
$user_registered =  $user_id !== 0;

$products_per_row = empty($viewData['products_per_row']) ? 1 : $viewData['products_per_row'];
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
echo shopFunctionsF::renderVmSubLayout('askrecomjs');

$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if (!empty($Itemid)) {
  $ItemidStr = '&Itemid=' . $Itemid;
}

$dynamic = false;

if (vRequest::getInt('dynamic', false) and vRequest::getInt('virtuemart_product_id', false)) {
  $dynamic = true;
}

foreach ($viewData['products'] as $type => $products) {

  if ($dynamic) {
    $rowsHeight[$row]['product_s_desc'] = 1;
    $rowsHeight[$row]['price'] = 1;
    $rowsHeight[$row]['customfields'] = 1;
    $col = 2;
    $nb = 2;
  } else {
    if ((!empty($type) and count($products) > 0) or (count($viewData['products']) > 1 and count($products) > 0)) {
      $productTitle = vmText::_('COM_VIRTUEMART_' . strtoupper($type) . '_PRODUCT');
      echo '<div class="' . $type . '-view">';
      echo '<h4>' . $productTitle . '</h4>';
      echo '</div >';
    }
  }

  // Calculating Products Per Row
  $cellwidth = floor(100 / $products_per_row);

  echo '<div class="g-grid is-product">';

  foreach ($products as $product) {
    if (!is_object($product) or empty($product->link)) {
      vmdebug('$product is not object or link empty', $product);
      continue;
    }
    echo '<div class="is-product g-block  size-' . $cellwidth . '">';
    echo '<div class="is-spacer is-product-container" data-vm="product-container">';
    echo '<div class="is-product-wrapper">';

    echo '<div class="is-vm-product-media-container">';
    echo '<a title="' . $product->product_name . '" href="' . JRoute::_($product->link . $ItemidStr) . '">';
    echo '<span class="is-image-wrapper">';
    echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false);
    echo '</span>';
    echo '</a>';
    echo '</div>';

    echo '<div class="is-product-info-wrapper">';
    echo '<div class="is-vm-product-descr-container">' . '<h2>' . JHtml::link($product->link . $ItemidStr, $product->product_name) . '</h2>';
    if (!empty($product->product_s_desc) && $product->product_parent_id != 0) {
      echo shopFunctionsF::limitStringByWord($product->product_s_desc, 60, ' ...');
    }
    echo '</div>';

    echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $product, 'position' => 'normal', 'hideTitle' => true));

    if ($user_registered) {
      if (VmConfig::get('display_stock', 1)) {
        echo '<div class="is-stock-level">';
        echo '<div class="is-vmicon is-vm2-' . $product->stock->stock_level . '" title="' . $product->stock->stock_tip . '"></div>';
        echo '<div class="is-stock-level-tip">' . $product->stock->stock_tip . '</div>';
        echo shopFunctionsF::renderVmSubLayout('stockhandle', array('product' => $product));
        echo '</div>';
      }
      echo shopFunctionsF::renderVmSubLayout('prices', array('product' => $product, 'currency' => $currency));
    }

    // echo '<div class="is-vm-details-button">';
    // $link = empty($product->link) ? $product->canonical : $product->link;
    // echo JHtml::link($link . $ItemidStr, vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name, 'class' => 'is-product-details button button-small'));
    // echo '</div>';

    echo '</div>';

    if ($dynamic) {
      echo vmJsApi::writeJS();
    }



    echo '</div>';
    echo '</div>';
    echo '</div>';
  }

  echo '</div>';
}
