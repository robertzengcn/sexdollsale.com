<?php
/**
 * languages header - allows customer to select from available languages installed on your site
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: languages.php 2718 2005-12-28 06:42:39Z drbyte $
 */

  $sql = "select configuration_value as value
          from " . TABLE_CONFIGURATION . "
          where configuration_key ='HEADER_LANGUAGE_DISPLAY' ";

  $language_header_status = $db->Execute($sql);  

  if ($language_header_status->RecordCount() != 0) {
    if($language_header_status->fields['value'] != 0){
      $show_language_header = true;
    }
     
    if ($show_language_header == true) {
  	 if (!isset($lng) || (isset($lng) && !is_object($lng))) {
        $lng = new language;
      }

      reset($lng->catalog_languages);
      require($template->get_template_dir('tpl_languages_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_languages_header.php');

      $title = BOX_HEADING_LANGUAGES;
      $title_link = false;
      require($template->get_template_dir('tpl_box_header.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_box_header.php');
    }
  }


?>