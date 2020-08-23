<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 9821 2018-04-16 18:04:39Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


$imageJS = '
jQuery(document).ready(function() {
	Virtuemart.updateImageEventListeners()
});
Virtuemart.updateImageEventListeners = function() {
	
	jQuery(".is-additional-images a.is-product-image.image-0").removeAttr("rel");
	jQuery(".is-additional-images img.is-product-image").click(function() {
		jQuery(".is-additional-images a.is-product-image").attr("rel","is-vm-additional-images" );
		jQuery(this).parent().children("a.is-product-image").removeAttr("rel");
		var src = jQuery(this).parent().children("a.is-product-image").attr("href");
		jQuery(".is-main-image img").attr("src",src);
		jQuery(".is-main-image img").attr("alt",this.alt );
		jQuery(".is-main-image a").attr("href",src );
		jQuery(".is-main-image a").attr("title",this.alt );
	}); 
}
';

vmJsApi::addJScript('imageswap', $imageJS);

if (!empty($this->product->images)) {
	$image = $this->product->images[0];
	?>
<div class="is-main-image">
  <?php
		$width = VmConfig::get('img_width_full', 0);
		$height = VmConfig::get('img_height_full', 0);
		if (!empty($width) or !empty($height)) {
			echo $image->displayMediaThumb("", true, "rel='is-vm-main-image'", true, false, false, $width, $height);
		} else {
			echo $image->displayMediaFull("", false, "rel='is-vm-main-image'", false);
		}
		?>
</div>
<?php

}
?>
