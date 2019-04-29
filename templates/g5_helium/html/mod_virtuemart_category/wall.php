<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$categoryModel->addImages($categories);
$categories_per_row = vmConfig::get('categories_per_row');
$col_width = floor(100 / $categories_per_row);
$itemId = 165; // Dummy child of Catalog menu item

?>

<div class="is-category-view <?php echo $class_sfx ?>">
  <div class="category-view g-grid">
    <?php foreach ($categories as $category) : ?>
      <?php
      $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id . '&Itemid=' . $itemId);
      $catname = vmText::_(mb_convert_case($category->category_name, MB_CASE_TITLE, "UTF-8"));;
      ?>
      <div class="is-category g-block size-<?php echo $col_width; ?>">
        <div class="is-spacer">
          <h2>
            <a href="<?php echo $caturl; ?>" title="<?php echo $category->category_name; ?> ">
              <span class="is-image-wrapper "><?php echo $category->images[0]->displayMediaThumb('class="browseCategoryImage"', false) ?></span>
              <span class="button button-2 button-bevel button-xsmall button-block button-outline"><?php echo $catname; ?></span>
            </a>
          </h2>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>