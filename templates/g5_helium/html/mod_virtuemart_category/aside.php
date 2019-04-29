<?php // no direct access
defined('_JEXEC') or die('Restricted access');
//JHTML::stylesheet ( 'menucss.css', 'modules/mod_virtuemart_category/css/', false );

$itemId = 165; // Dummy child of Catalog menu item
?>

<ul class="VMmenu <?php echo $class_sfx ?>">
	<?php foreach ($categories as $category) {
		$active_menu = 'class="VmClose"';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id . '&Itemid=' . $itemId);
		$cattext = vmText::_(mb_convert_case($category->category_name, MB_CASE_TITLE, "UTF-8"));
		//if ($active_category_id == $category->virtuemart_category_id) $active_menu = 'class="active"';
		if (in_array($category->virtuemart_category_id, $parentCategories)) $active_menu = 'class="VmOpen"';

		?>

		<li <?php echo $active_menu ?>>
			<div>
				<?php
				if ($active_menu == 'class="VmOpen"') {
					echo '<span class="button button-block  button-bevel button-xsmall button-outline" > ' . $cattext . ' </span> ';
				} else {
					echo '<a href="' . $caturl . '" class = "button button-block  button-bevel button-xsmall"><span class="wrapper"><span class="title">' . $cattext . '</span><span class="fa fa-arrow-right"></span></span></a>';
					// echo JHTML::link($caturl, $cattext, ' class = "button button-block center button-bevel button-xsmall" ');
				}
				?>
				<?php if (isset($category->childs)) {
					?>
					<span class="VmArrowdown"> </span>
				<?php
			}
			?>
			</div>
			<?php if (!empty($category->childs)) { ?>
				<ul class="menu<?php echo $class_sfx; ?>">
					<?php
					foreach ($category->childs as $child) {

						$active_child_menu = ' class = "VmClose" ';
						$caturl = JRoute::_(' index . php ? option = com_virtuemart &view = category &virtuemart_category_id = ' . $child->virtuemart_category_id . ' &Itemid = ' . $itemId);
						$cattext = vmText::_(mb_convert_case($category->category_name, MB_CASE_TITLE, "UTF-8"));
						if ($child->virtuemart_category_id == $active_category_id) $active_child_menu = ' class = "VmOpen"';
						?>
						<li <?php echo $active_child_menu ?>>
						<li>
							<div><?php echo JHTML::link($caturl, $cattext); ?></div>
						</li>
					<?php		} ?>
				</ul>
			<?php 	} ?>
		</li>
	<?php
} ?>
</ul>