<?php

/**
 *
 * Shows the products/categories of a category
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6104 2012-06-13 14:15:29Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$categories = $viewData['categories'];
$itemId = 131; // Catalog menu item

if ($categories) {

  $categories_per_row = !empty($viewData['categories_per_row']) ? $viewData['categories_per_row'] : VmConfig::get('categories_per_row', 3);
  if (empty($categories_per_row)) $categories_per_row = 3;

  // Category and Columns Counter
  $iCol = 1;
  $iCategory = 1;

  // Calculating Categories Per Row
  $category_cellwidth = floor(100 / $categories_per_row);

  // Separator

  $ajaxUpdate = '';
  if (VmConfig::get('ajax_category', false)) {
    $ajaxUpdate = 'data-dynamic-update="1"';
  }
  ?>

  <div class="category-view g-grid">

    <?php

    // Start the Output
    foreach ($categories as $category) {

      // Category Link
      $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id . '&Itemid=' . $itemId, false);
      ?>

      <div class="is-category g-block size-is size-<?php echo $category_cellwidth; ?>">
        <div class="is-spacer">
          <h2>
            <a href="<?php echo $caturl ?>" title="<?php echo vmText::_($category->category_name) ?>" <?php echo $ajaxUpdate ?>>
              <span class="is-image-wrapper"><?php echo $category->images[0]->displayMediaThumb('class="browseCategoryImage"', false); ?></span>
              <?php echo '<span class="button button-2 button-bevel button-xsmall button-block button-outline">' . vmText::_(mb_convert_case($category->category_name, MB_CASE_TITLE, "UTF-8")) . '</span>' ?>

            </a>
          </h2>
        </div>
      </div>


    <?php
    }
  }  ?>