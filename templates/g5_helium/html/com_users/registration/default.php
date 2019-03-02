<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

/*** Begin Registration Form Override ***/

$doc = JFactory::getDocument();
$js = "
	jQuery(document).ready(function($){
		// Define the variables
		const regForm = $('#member-registration');
		const username = regForm.find('#jform_username');
		// const name = regForm.find('#jform_name');
		const password = regForm.find('#jform_password1');
		const password2 = regForm.find('#jform_password2');
		const email = regForm.find('#jform_email1');
		const email2 = regForm.find('#jform_email2');
		 
		
		// Hide the required field, star, username, name, confirm pass and confirm email and exclude from validation
		regForm.find('.spacer').parents('.control-group').hide();
		regForm.find('.star').hide();
		username.parents('.control-group').hide();
		username.addClass('novalidate');
		// name.parents('.control-group').hide();
		// name.addClass('novalidate');
		password2.parents('.control-group').hide();
		password2.addClass('novalidate');
		email2.parents('.control-group').hide();
		email2.addClass('novalidate');
		
		(email.parents('.control-group')).after(password.parents('.control-group'));
		
		// Add a default value to the name field
		// name.val('IS2109_DEFAULT_USER_NAME');

		
		// Clone password and email values to the confirm fields and username
		email.on('keyup', function() {
				username.val( this.value );
				email2.val( this.value );
		});
		password.on('keyup', function() {
				password2.val( this.value );
		});
	});    
";
$doc->addScriptDeclaration($js);

/*** Finish Registration Form Override ***/

?>
<div class="registration<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate well" enctype="multipart/form-data">
		<?php // Iterate through the form fieldsets and display each one. ?>
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<?php $fields = $this->form->getFieldset($fieldset->name); ?>
			<?php if (count($fields)) : ?>
				<fieldset>
					<?php // If the fieldset has a label set, display it as the legend. ?>
					<?php if (isset($fieldset->label)) : ?>
						<legend><?php echo JText::_($fieldset->label); ?></legend>
					<?php endif; ?>
					<?php echo $this->form->renderFieldset($fieldset->name); ?>
				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate">
					<?php echo JText::_('JREGISTER'); ?>
				</button>
				<a class="btn" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
