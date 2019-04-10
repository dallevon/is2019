<?php
/**
 *
 * Layout for the shopping cart and the mail
 * shows the chosen adresses of the shopper
 * taken from the cart in the session
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

echo '<div class="billto-shipto">';

echo '<div class="output-billto">';
echo '<h2><span class="fa fa-home fw"></span>&nbsp;' . vmText::_('COM_VIRTUEMART_USER_FORM_BILLTO_LBL') . '</h2>';
echo '<div>';
$cartfieldNames = array();
foreach ($this->userFieldsCart['fields'] as $fields) {
  $cartfieldNames[] = $fields['name'];
}

foreach ($this->cart->BTaddress['fields'] as $item) {
  if (in_array($item['name'], $cartfieldNames)) continue;
  if (!empty($item['value'])) {
    if ($item['name'] === 'agreed') {
      $item['value'] = ($item['value'] === 0) ? vmText::_('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO') : vmText::_('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
    }
    // echo '<span class="titles">' . $item['title'] . '</span>';
    echo '<span class="values vm2-' . $item['name'] . '">' . $item['value'] . '</span>';
  }
}
echo '</div>';

if ($this->pointAddress) {
  $this->pointAddress = 'required invalid';
}


echo '<a class="details button button-bevel button-outline button-xsmall ' . $this->pointAddress . '" href="' . JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', $this->useXHTML, $this->useSSL) . '" rel="nofollow">' . vmText::_('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL') . '</a>';

echo '<input type="hidden" name="billto" value="' . $this->cart->lists['billTo'] . '" />';
echo '</div>';

echo '<div class="output-shipto">';
echo '<h2><span class="fa fa-envelope fw"></span>&nbsp;' . vmText::_('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL') . '</h2>';
echo  '<div>';
if ($this->cart->user->virtuemart_user_id == 0) {

  echo vmText::_('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
  echo VmHtml::checkbox('STsameAsBT', $this->cart->STsameAsBT, 1, 0, 'id="STsameAsBTjs" data-dynamic-update=1') . '<br />';
} else if (!empty($this->cart->lists['shipTo'])) {
  echo $this->cart->lists['shipTo'];
}

if (empty($this->cart->STsameAsBT) and !empty($this->cart->ST) and !empty($this->cart->STaddress['fields'])) {
  echo  '<div id="output-shipto-display">';
  foreach ($this->cart->STaddress['fields'] as $item) {

    if ($item['name'] == 'shipto_address_type_name') continue;
    if (!empty($item['value'])) {
      echo '<span class="titles">' . $item['title'] . '</span>';
      if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') {
        echo '<span class="values<-' . $item['name'] . '">' . $item['value'] . '</span>';
      } else {
        echo '<span class="values">' . $item['value'] . '</span>';
      }
    }
  }
  echo '</div>';
}
echo '</div>';
if (!isset($this->cart->lists['current_id'])) {
  $this->cart->lists['current_id'] = 0;
}
echo '<a class="details button button-bevel button-outline button-xsmall" href="' . JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&virtuemart_user_id[]=' . $this->cart->lists['current_id'], $this->useXHTML, $this->useSSL) . '" rel="nofollow">' . vmText::_('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL') . '</a>';

echo '</div>';
echo '</div>';
echo '<p class="alert alert-info">' . vmText::_('IS_BT_ADDRESS_SUFFIX') . '</p>';
