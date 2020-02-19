<?php
/**
 * Facebookall activity feed sidebox by sourceaddons
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: fball_activity.php 3133 2012-21-09 23:39:02Z soureceaddons $
 *
 * @contribution: Facebook activity feed sidebox
 * @author: sourceaddons
 * @version $Id: fball_activity.php 2012-21-09 $
 */
 
 // Getting custom title.
      $title_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_ACTIVITY_CUSTOM_TITLE'";
      $title_array = $db->Execute($title_query);
      $custom_title = $title_array->fields['configuration_value'];

    require($template->get_template_dir('tpl_fball_activity.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_fball_activity.php');
    $title =  $custom_title;
    $title_link = false;
    require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
?>
