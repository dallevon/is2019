<?php
/**
 *
 * loads the add to cart button
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @version $Id: addtocartbtn.php 8024 2014-06-12 15:08:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


echo '<input type="submit" name="addtocart" class="addtocart-button button button-bevel button-xsmall" value="' . vmText::_('COM_VIRTUEMART_CART_ADD_TO') . '" title="' . vmText::_('COM_VIRTUEMART_CART_ADD_TO') . '" ' . ($viewData['orderable'] ? '' : 'disabled') . ' />';
