<?php  // no direct access
defined('_JEXEC') or die('Restricted access');

// $s =   JFactory::getSession();
// print_r($s->getCart());

// $session = JFactory::getSession();
// $cartSession = $session->get('vmcart', 0, 'vm');
// if (!empty($cartSession)) {
//   $sessionCart = (object)json_decode($cartSession, true);

//   print_r($sessionCart);
// }

// $cart = VirtueMartCart::getCart();
// // print_r($cart->cartProductsData);


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

$cartBillTotal = '<strong class="total">';
if ($data->totalProduct and $show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) {
  $cartBillTotal .= $data->billTotal;
}
$cartBillTotal .= '</strong>';


$linkIcon = 'fa-shopping-cart';
$cartTotalProducts = '<strong class="total_products">' . ($data->totalProduct == 0 ? $data->totalProductTxt : $data->totalProduct) . '</strong>';


echo '<div class="is-show_cart">';
$taskRoute = '';
$link = JRoute::_("index.php?option=com_virtuemart&view=cart&Itemid=" . $menuItem->id . $taskRoute, vmURI::useSSL());
if ($data->totalProduct == 0) {
  $linkIcon = 'fa-cart-arrow-down';
}
echo '<a href="' . $link . '" rel="nofollow"><span class="total_products-wrapper">' . $cartTotalProducts . '<span class="fa ' . $linkIcon . '"></span></span>' . $cartBillTotal . '</a>';
echo '</div>';


$view = vRequest::getCmd('view');
if ($view != 'cart' and $view != 'user') {
  echo '<div class="payments-signin-button"></div>';
}
echo '<noscript>' . vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') . '</noscript>';

echo '</div>';
