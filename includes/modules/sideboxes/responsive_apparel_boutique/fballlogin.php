<?php
/**
 * facebook all login - facebook all enables facebook sociallogin for zencart websites.
 *   Created by sourceaddons 
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

  // test if box should display
  $show_blank_sidebox = true;

  if ($show_blank_sidebox == true) {
      require($template->get_template_dir('tpl_fballlogin.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_fballlogin.php');
      $title =  BOX_HEADING_FBALL_LOGIN_SIDEBOX;
      $title_link = false;
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 }

 
