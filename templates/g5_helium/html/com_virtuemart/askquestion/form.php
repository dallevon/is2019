<?php
/**
 *TODO Improve the CSS , ADD CATCHA ?
 * Show the form Ask a Question
 *
 * @package	VirtueMart
 * @subpackage
 * @author Kohl Patrick, Maik Künnemann
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2018 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: form.php 9927 2018-09-10 STS $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$min = VmConfig::get('asks_minimum_comment_length', 50);
$max = VmConfig::get('asks_maximum_comment_length', 2000);
vmJsApi::JvalideForm();
vmJsApi::addJScript('askform', '
	jQuery(function($){
			$("#askform").validationEngine("attach");
			$("#comment").keyup(function () {
				var result = $(this).val();
				$("#counter").val(result.length);
			});
	});
');
/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo $this->continue_link_html;
} else {
	$session = JFactory::getSession();
	$sessData = $session->get('askquestion', 0, 'vm');
	if (!empty($this->login)) {
		echo $this->login;
	}
	if (empty($this->login) or VmConfig::get('recommend_unauth', false)) {
		echo '<div class="is-ask-a-question-view">';

		echo '<h1>' . vmText::_('COM_VIRTUEMART_PRODUCT_ASK_QUESTION') . '</h1>';
		$action = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', false);

		echo '<form method="post" class="form-validate" action="' . $action . '" name="askform" id="askform" class="askform">';
		echo '<div class="is-product-header">';
		echo '<div class="is-product-name">';
		echo '<h2>' . $this->product->product_name;
		if (!empty($this->product->product_s_desc)) {
			echo '&nbsp;<small class="short-description">' . $this->product->product_s_desc . '</small>';
		}
		echo '</h2>';

		echo '<label for="name">' . vmText::_('COM_VIRTUEMART_USER_FORM_NAME') . ': </label>';
		echo '<input type="text" class="validate[required,minSize[3],maxSize[64]]" value="' . ($this->user->name ? $this->user->name : $sessData['name']) . '" name="name" id="name" validation="required name" />';

		echo '<label for="email">' . vmText::_('COM_VIRTUEMART_USER_FORM_EMAIL') . ': </label>';
		echo '<input type="text" class="validate[required,custom[email]]" value="' . ($this->user->email ? $this->user->email   : $sessData['email']) . '" name="email" id="email" validation="required email" />';
		echo '</div>';
		// Product Image
		echo '<div class="is-product-image">' . $this->product->images[0]->displayMediaThumb('class="product-image"', false) . '</div>';
		echo '</div>';

		echo '<div class="is-form-fields">';
		echo '<textarea title="' . vmText::sprintf('COM_VIRTUEMART_ASK_COMMENT',  $min, $max) . '" class="validate[required, minSize [' . $min . '] , maxSize[' . $max . ']] field" id="comment" name="comment" rows="8" placeholder="' . vmText::sprintf('COM_VIRTUEMART_ASK_COMMENT',  $min, $max) . '">' . $sessData['comment'] . '</textarea>';

		echo $this->captcha;

		echo '<div class="is-submit">';
		echo '<input class="highlight-button button" type="submit" name= "submit_ask" title="' . vmText::_('COM_VIRTUEMART_ASK_SUBMIT') . '" value="' . vmText::_('COM_VIRTUEMART_ASK_SUBMIT') . '" />';
		echo '</div>';

		echo '<div class="is-counter">';
		echo '<label  for="counter">' . vmText::_('COM_VIRTUEMART_ASK_COUNT') . '</label>';
		echo '<input type="text" value="0" size="4" class="counter" id="counter" name="counter" maxlength="4" readonly="readonly" />';
		echo '</div>';

		echo '<input type="hidden" name="virtuemart_product _ id" value="' . vRequest::getInt('virtuemart_product_id ', 0) . '" /><input type="hidden" name="tmpl" value="component" /><input type="hidden" name="view" value="productdetails" /><input type="hidden" name="option" value="com_virtuemart" /><input type="hidden" name="virtuemart_category _ id" value="' . vRequest::getInt('virtuemart_category_id') . '" /><input type="hidden" name="task" value="mailAskquestion" />';

		echo JHTML::_('form.token');
		echo '</div>';
		echo '</form>';
		echo '</div>';
	}
}
