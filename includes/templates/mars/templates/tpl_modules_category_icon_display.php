<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_category_icon_display.php 6216 2007-04-17 06:09:57Z ajeh $
 */
  require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CATEGORY_ICON_DISPLAY));

$category_icon_display_image = str_replace("<br />", "&nbsp;", $category_icon_display_image);
?>

<div align="<?php echo $align; ?>" id="categoryIcon" class="categoryIcon"><?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT, 'cPath=' . $_GET['cPath'], 'NONSSL') . '"><p>'. $category_icon_display_name . '</p>' . $category_icon_display_image . '</a>'; ?></div>