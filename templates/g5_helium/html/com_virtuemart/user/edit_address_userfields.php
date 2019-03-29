<?php

/**
 *
 * Modify user form view, User info
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk, Eugen Stranz
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_address_userfields.php 9831 2018-05-07 13:45:33Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();

$hiddenFields = '';

// Output: Userfields
echo '<fieldset class="adminForm user-details">';
foreach ($this->userFields['fields'] as $field) {

	if ($field['hidden'] == true) {
		// We collect all hidden fields
		// and output them at the end
		$hiddenFields .= $field['formcode'] . "\n";
	}

	$descr = empty($field['description']) ? $field['title'] : $field['description'];
	// Output: Userfields
	echo 		'<div title="' . strip_tags($descr) . '">';
	echo '<label class="key ' . $field['name'] . '" for="' . $field['name'] . '_field">';
	echo $field['title'] . ($field['required'] ? ' <span class="asterisk">*</span>' : '');
	echo '</label>';
	echo $field['formcode'];
	echo '</div>';
}

echo 	'</fieldset>';

// Output: Hidden Fields
echo $hiddenFields;

vmJsApi::addJScript('is-ContactName', "
				jQuery(document).ready(function($) {
					const contactName = $('#contact_name_field');
					if(!contactName.val()) {
						contactName.val('$user->name');
					}
				});
			");
