<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 9942 2018-09-27 11:54:06Z junstoppable $
 */

defined('_JEXEC') or die('Restricted access');

$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";
vmJsApi::addJScript('vm-hover', $js);


$j = 'jQuery(document).ready(function() {
	jQuery(".changeSendForm")
		.off("change",Virtuemart.sendCurrForm)
			.on("change",Virtuemart.sendCurrForm);
	})';

vmJsApi::addJScript('sendFormChange', $j);

if (!$this->category->virtuemart_category_id) {
  $jc = 'jQuery(document).ready(function() {
    jQuery("body").trigger("updateVirtueMartCartModule");
  })';

  vmJsApi::addJScript('updateCart', $jc);
}


if (vRequest::getInt('dynamic', false) and vRequest::getInt('virtuemart_product_id', false)) {
  if (!empty($this->products)) {
    if ($this->fallback) {
      $p = $this->products;
      $this->products = array();
      $this->products[0] = $p;
      vmdebug('Refallback');
    }

    echo shopFunctionsF::renderVmSubLayout($this->productsLayout, array('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $this->perRow, 'showRating' => $this->showRating));
  }

  return;
}

// print_r($this->category)
?>

<div class="is-category-view">
  <?php if ($this->show_store_desc and !empty($this->vendor->vendor_store_desc) and !$this->category->virtuemart_category_id) : ?>
    <div class="is-vendor-store-desc hidden-phone">
      <?php echo $this->vendor->vendor_store_desc; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($this->showcategory_desc) and empty($this->keyword)) : ?>
    <?php if (!empty($this->category)) : ?>
      <div class="is-category-description">
        <?php echo $this->category->category_description; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (!empty($this->manu_descr)) : ?>
    <div class="is-manufacturer-description">
      <?php echo $this->manu_descr; ?>
    </div>
  <?php endif; ?>

  <?php
  // Show child categories
  if ($this->showcategory and empty($this->keyword)) {
    if (!empty($this->category->haschildren)) {
      echo ShopFunctionsF::renderVmSubLayout('categories', array('categories' => $this->category->children, 'categories_per_row' => $this->categories_per_row));
    }
  } ?>

  <?php if (($this->category->virtuemart_category_id and !empty($this->products)) or ($this->showsearch or $this->keyword !== false)) { ?>
    <div class="is-browse-view">
      <?php if ($this->showsearch or $this->keyword !== false) {
        //id taken in the view.html.php could be modified
        $category_id  = vRequest::getInt('virtuemart_category_id', 0); ?>

        <!--BEGIN Search Box -->
        <div class="virtuemart_search">
          <form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0', false); ?>" method="get">

            <?php if (!empty($this->searchCustomList)) : ?>
              <div class="vm-search-custom-list">
                <?php echo $this->searchCustomList ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($this->searchCustomValues)) : ?>
              <div class="vm-search-custom-values">
                <?php
                echo ShopFunctionsF::renderVmSubLayoutAsGrid(
                  'searchcustomvalues',
                  array(
                    'searchcustomvalues' => $this->searchCustomValues,
                    'options' => array(
                      'items_per_row' => array(
                        'xs' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                      ),
                    ),
                  )
                );
                ?>
              </div>
            <?php endif; ?>

            <div class="vm-search-custom-search-input">
              <input name="keyword" class="inputbox" type="text" size="40" value="<?php echo $this->keyword ?>" />
              <input type="submit" value="<?php echo vmText::_('COM_VIRTUEMART_SEARCH') ?>" class="button" onclick="this.form.keyword.focus();" />
              <?php  ?>
              <span class="vm-search-descr"> <?php echo vmText::_('COM_VM_SEARCH_DESC') ?></span>
            </div>

            <!-- input type="hidden" name="showsearch" value="true"/ -->
            <input type="hidden" name="view" value="category" />
            <input type="hidden" name="option" value="com_virtuemart" />
            <input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>" />
            <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
          </form>
        </div>
        <!-- End Search Box -->
      <?php
    } ?>

      <?php  // Show child categories

      if (!empty($this->orderByList)) : ?>
        <div class="orderby-displaynumber">
          <div class="vm-order-list">
            <?php echo $this->orderByList['orderby']; ?>
            <?php echo $this->orderByList['manufacturer']; ?>
          </div>
          <div class="display-number">
            <?php echo $this->vmPagination->getResultsCounter(); ?><br /><?php echo $this->vmPagination->getLimitBox($this->category->limit_list_step); ?>
          </div>
        </div> <!-- end of orderby-displaynumber -->
      <?php endif; ?>
      <div class="vm-pagination vm-pagination-top">
        <?php echo $this->vmPagination->getPagesLinks(); ?>
        <span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter(); ?></span>
      </div>
      <?php if (!empty($this->category->category_name)) { ?>

        <h1>
          <?php echo vmText::_(mb_convert_case($this->category->category_name, MB_CASE_TITLE, "UTF-8")) ?>
          <?php echo '<small>[' . count($this->products) . ']</small>'; ?>
        </h1>
      <?php
    } ?>

      <?php
      if (!empty($this->products)) {
        //revert of the fallback in the view.html.php, will be removed vm3.2
        if ($this->fallback) {
          $p = $this->products;
          $this->products = array();
          $this->products[0] = $p;
          vmdebug('Refallback');
        }

        echo shopFunctionsF::renderVmSubLayout($this->productsLayout, array('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $this->perRow, 'showRating' => $this->showRating));

        if (!empty($this->orderByList)) { ?>
          <div class="vm-pagination vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks(); ?><span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter(); ?></span></div>
        <?php
      }
    } elseif ($this->keyword !== false) {
      echo vmText::_('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
    }
    ?>
    </div>

  <?php
} ?>
</div>

<?php
if (VmConfig::get('ajax_category', false)) {
  $j = "Virtuemart.container = jQuery('.category-view');
	Virtuemart.containerSelector = '.category-view';";

  vmJsApi::addJScript('ajax_category', $j);
  vmJsApi::jDynUpdate();
}
?>
<!-- end browse-view -->