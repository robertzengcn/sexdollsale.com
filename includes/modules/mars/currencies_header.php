<?php
/**
 * currencies header - allows customer to select from available currencies
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: currencies.php 2834 2006-01-11 22:16:37Z birdbrain $
 */

  $sql = "select configuration_value as value
          from " . TABLE_CONFIGURATION . "
          where configuration_key ='HEADER_CURRENCY_DISPLAY' ";

  $currency_header_status = $db->Execute($sql);    
  if ($currency_header_status->RecordCount() != 0) {
    if($currency_header_status->fields['value'] != 0){
      $show_currency_header = true;
    }

    if ($show_currency_header == true) {
      if (isset($currencies) && is_object($currencies)) {

        reset($currencies->currencies);
        $currencies_array = array();          
        while (list($key, $value) = each($currencies->currencies)) {                   
          //$currencies_array[] = array('id' => $key, 'text' => $value['title']); // Print it if you need the currency title not the code
          $currencies_array[] = array('id' => $key, 'text' => $key);
        }

        $hidden_get_variables = '';
        reset($_GET);
        while (list($key, $value) = each($_GET)) {
          if ( ($key != 'currency') && ($key != zen_session_name()) && ($key != 'x') && ($key != 'y') ) {
            $hidden_get_variables .= zen_draw_hidden_field($key, $value);
          }
        }

        require($template->get_template_dir('tpl_currencies_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_currencies_header.php');
        $title =  BOX_HEADING_CURRENCIES;
        $title_link = false;
        $text = 'Currencies:';
        require($template->get_template_dir('tpl_box_header.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_box_header.php');
      }
    }  
  }
   
  
?>