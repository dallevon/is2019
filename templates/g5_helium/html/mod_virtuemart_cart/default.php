<?php  // no direct access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$menu = $app->getMenu();
$menuItem = $menu->getItems('link', 'index.php?option=com_virtuemart&view=cart', true);


echo '<div class="vmCartModule ' . $params->get('moduleclass_sfx') . '" id="vmCartModule' . $params->get('moduleid_sfx') . '">';

echo '<div class="hiddencontainer" style=" display: none; "><div class="vmcontainer"><div class="product_row">';

echo '<span class="quantity"></span><span class="product_name"></span>';

if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) {
  echo '<div class="subtotal_with_tax"></div>';
}
echo '<div class="customProductData"></div>';

echo '</div></div></div>';


if ($show_product_list) {
  echo '<div class="vm_cart_products"><div class="vmcontainer">';

  foreach ($data->products as $product) {
    echo '<div class="product_row">';

    echo '<span class="quantity">' . $product['quantity'] . '</span><span class="product_name">' . $product['product_name'] . '</span>';
    if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) {
      echo '<div class="subtotal_with_tax">' . $product['subtotal_with_tax'] . '</div>';
    }
    if (!empty($product['customProductData'])) {
      echo '<div class="customProductData">' . $product['customProductData'] . '</div>';
    }

    echo '</div>';
  }

  echo '</div></div>';
}

echo '<div class="total">';
if ($data->totalProduct and $show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) {
  echo $data->billTotal;
}
echo '</div>';

echo '<div class="total_products">' . $data->totalProductTxt . '</div>';

echo '<div class="show_cart">';
if ($data->totalProduct) {
  if (false && $data->dataValidated == true) {
    $taskRoute = '&task=confirm';
    $linkName = vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
  } else {
    $taskRoute = '';
    $linkName = vmText::_('COM_VIRTUEMART_CART_SHOW');
  }
  echo '<a href="' . JRoute::_("index.php?option=com_virtuemart&view=cart&Itemid=" . $menuItem->id . $taskRoute, vmURI::useSSL()) . '" rel="nofollow" >' . $linkName . '</a>';
}
echo '</div>';


$view = vRequest::getCmd('view');
if ($view != 'cart' and $view != 'user') {
  echo '<div class="payments-signin-button"></div>';
}
echo '<noscript>' . vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') . '</noscript>';

echo '</div>';
