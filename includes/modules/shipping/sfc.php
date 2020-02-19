<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Josh Dechant                                      |
// |                                                                      |
// | Portions Copyright (c) 2004 The zen-cart developers                  |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: sfc.php, v1.3 2011-08-10 Jack Huang $
//

  class sfc {
    var $code, $title, $description, $icon, $enabled, $num_zones, $num_tables, $delivery_geozone, $order_total;

    function sfc() {
      global $db, $order;

      $this->code = 'sfc';
      $this->title = MODULE_SHIPPING_SFC_TEXT_TITLE;

      $check_cny = $db->Execute("SELECT code FROM " . TABLE_CURRENCIES . " WHERE code = 'CNY'");
      if (!$check_cny->RecordCount()) {
        $this->title .=  '<span class="alert">' . MODULE_SHIPPING_SFC_TEXT_ALERT_CNY . '</span>';
      }

      if (SHIPPING_MAX_WEIGHT == 0) {
        $this->title .=  '<br /><span class="alert">' . MODULE_SHIPPING_SFC_TEXT_ALERT_WEIGHT . '</span>';
      }

      $this->description = MODULE_SHIPPING_SFC_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_SFC_SORT_ORDER;
      $this->tax_class = MODULE_SHIPPING_SFC_TAX_CLASS;
      $this->tax_basis = MODULE_SHIPPING_SFC_TAX_BASIS;

      // disable only when entire cart is free shipping
      if (zen_get_shipping_enabled($this->code)) {
        $this->enabled = ((MODULE_SHIPPING_SFC_STATUS == 'True') ? true : false);
      }

      if ($this->enabled == true) {
        $this->enabled = false;

        foreach($this->keys() as $key) {
          if (constant($key) == 'True' ) {
            $this->enabled = true;
            break;
          }
        }
      }
    }

// class methods
    function quote($method = '') {
      global $db, $order, $shipping_weight, $shipping_num_boxes, $template, $currencies;

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_SFC_TEXT_TITLE,
                            'methods' => array());

      // $geozone_mode = $this->determineTableMethod(constant('MODULE_SHIPPING_SFC_CODE_' . $this->delivery_geozone . '_MODE'));  
      $countryname = getCountryName($order->delivery['country']['id']);

      // Construct the GetShippingRate call
      if ((TEXT_SHIPPING_WEIGHT == '克') || (TEXT_SHIPPING_WEIGHT == 'g')) {
        $profile = '?country=' . urlencode($countryname) . '&weight=' . $shipping_weight / 1000 * $shipping_num_boxes;
      } else {
        $profile = '?country=' . urlencode($countryname) . '&weight=' . $shipping_weight * $shipping_num_boxes;
      }

      if ($method) {
        //Construct the GetShippingRate By mode
        $profile .= '&mode=' . $method;
      } else {
        $profile .= '&';
      }

      $sfc_code_query = $db->Execute("SELECT configuration_value, configuration_key FROM " . TABLE_CONFIGURATION . " WHERE configuration_key like 'MODULE_SHIPPING_SFC_%_CODE' AND configuration_value = 'True'");

      while (!$sfc_code_query->EOF) {
        $sfc_code_string = substr($sfc_code_query->fields['configuration_key'],20,strlen($sfc_code_query->fields['configuration_key'])-25);
        $sfc_code[$sfc_code_string] = $sfc_code_query->fields['configuration_value'];
        $sfc_discount_query = $db->Execute("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_SHIPPING_SFC_" . $sfc_code_string . "_DISCOUNT'");
        $sfc_discount[$sfc_code_string] = $sfc_discount_query->fields['configuration_value'];
        $sfc_code_query->MoveNext();
      }

      $ch = curl_init(REQUIRERATE . $profile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // set proxy options if configured
      if (MODULE_SHIPPING_SFC_CURL_PROXY_REQUIRED == 'True' && MODULE_SHIPPING_SFC_CURL_PROXY_SERVER_DETAILS != '') {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, MODULE_SHIPPING_SFC_CURL_PROXY_SERVER_DETAILS);
      }
      $content = curl_exec($ch);
      curl_close($ch);
      $doc = new DOMDocument();
      $doc->loadXML($content);

      // Load the call and capture the document returned by sendfromchina.com API
      $dataset = $doc->getElementsByTagName( "rate" );

      // Check to see if the response was loaded, else print an error
      if ( @$dataset->item(0)->nodeValue ) {

        // For each Shippinh Rate Item node, and display all of them

        foreach( $dataset as $row ) {
          $totalfees = $row->getElementsByTagName( "totalfee" );
          $totalfee = $totalfees->item(0)->nodeValue;
          $deliverytimes = $row->getElementsByTagName( "deliverytime" );
          $deliverytime = $deliverytimes->item(0)->nodeValue;
          $iftrackings = $row->getElementsByTagName( "iftracking" );
          $iftracking = $iftrackings->item(0)->nodeValue;
          $shiptypecodes = $row->getElementsByTagName( "shiptypecode" );
          $shiptypecode = $shiptypecodes->item(0)->nodeValue;
          $byvolumes = $row->getElementsByTagName( "byvolume" );
          $byvolume = $byvolumes->item(0)->nodeValue;
          $shiptypes = $row->getElementsByTagName( "shiptypename" );
          $shiptypename = $shiptypes->item(0)->nodeValue;

          if (substr_count($sfc_discount[$shiptypecode], '%') > 0) {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') * (str_replace('%', '', $sfc_discount[$shiptypecode])) / 100, 2);
          } elseif (substr_count($sfc_discount[$shiptypecode], '％') > 0) {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') * (str_replace('％', '', $sfc_discount[$shiptypecode])) / 100, 2);
          } else {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') + str_replace('$', '', $sfc_discount[$shiptypecode]), 2);
          }

          if ($totalfee < 0) { $totalfee = 0; }

          if ($sfc_code[$shiptypecode] == 'True') {
            $this->quotes['methods'][] = array('id' => $shiptypecode,
                                             'title' => $shiptypename . ' (' . $deliverytime . ' days)',
                                              'cost' => $totalfee);
          }
        }
      } else {
        // display error message
        $errors = $doc->getElementsByTagName( "errors" );
        $error = $errors->item(0)->nodeValue;
        // echo $error;
      }

      // show boxes if weight
      switch (SHIPPING_BOX_WEIGHT_DISPLAY) {
        case (0):
          $show_box_weight = '';
          break;
        case (1):
          $show_box_weight = ' (' . $shipping_num_boxes . ' ' . TEXT_SHIPPING_BOXES . ')';
          break;
        case (2):
          $show_box_weight = ' (' . number_format($shipping_weight,2) . TEXT_SHIPPING_WEIGHT . ')';
          break;
        default:
          $show_box_weight = ' (' . $shipping_num_boxes . ' x ' . number_format($shipping_weight,2) . TEXT_SHIPPING_WEIGHT . ')';
          break;
      }

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      return $this->quotes;
    }  

    function check() {
      global $db;

      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_SFC_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;

      if (!defined('MODULE_SHIPPING_SFC_TEXT_CONFIG_1_1')) include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/shipping/' . $this->code . '.php');

      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_1_1 . "', 'MODULE_SHIPPING_SFC_STATUS', 'True', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_1_2 . "', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_2_1 . "', 'MODULE_SHIPPING_SFC_TAX_CLASS', '0', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_2_2 . "', '6', '2', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_3_1 . "', 'MODULE_SHIPPING_SFC_TAX_BASIS', 'Shipping', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_3_2 . "', '6', '3', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_4_1 . "', 'MODULE_SHIPPING_SFC_SORT_ORDER', '0', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_4_2 . "', '6', '4', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_5_1 . "', 'MODULE_SHIPPING_SFC_SKIPPED', '', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_5_2 . "', '6', '5', 'zen_cfg_textarea(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_7_1 . "', 'MODULE_SHIPPING_SFC_CURL_PROXY_REQUIRED', 'False', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_7_2 . "', '6', '6', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_8_1 . "', 'MODULE_SHIPPING_SFC_CURL_PROXY_SERVER_DETAILS', '114.113.147.143:3128', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_8_2 . "', '6', '7', '', now())");

      // 不通过代理服务器安装的代码
      $doc = new DOMDocument();
      $doc->load( REQUIRESHIPTYPE );
      $dataArr = $doc->getElementsByTagName( "shiptype" );

      // 通过代理服务器安装的代码
      // $ch = curl_init(REQUIRESHIPTYPE);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
      // curl_setopt($ch, CURLOPT_PROXY, '114.113.147.143:3128');
      // $content = curl_exec($ch);
      // curl_close($ch);
      // $doc = new DOMDocument();
      // $doc->loadXML($content);
      // $dataArr = $doc->getElementsByTagName( "shiptype" );

      $s_order = 0;
      foreach( $dataArr as $row ){
        $en_names = $row->getElementsByTagName( "en_name" );
        $en_name = $en_names->item(0)->nodeValue;
        $method_codes = $row->getElementsByTagName( "method_code" );
        $method_code = $method_codes->item(0)->nodeValue;

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_SFC_TEXT_CONFIG_9_1 . "{$en_name}', 'MODULE_SHIPPING_SFC_{$method_code}_CODE', 'False', '" . MODULE_SHIPPING_SFC_TEXT_CONFIG_9_2 . "{$en_name} ?', '6', '{$s_order}', 'zen_cfg_select_option(array(\'False\', \'True\'), ', now())");
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('{$en_name} " . MODULE_SHIPPING_SFC_TEXT_CONFIG_6_1 . "', 'MODULE_SHIPPING_SFC_{$method_code}_DISCOUNT', '100%', '{$en_name} " . MODULE_SHIPPING_SFC_TEXT_CONFIG_6_2 . "', '6', '{$s_order}', now())");
        $s_order ++;
      }

      $db->Execute("update " . TABLE_COUNTRIES . " set countries_name='United States Of America' where countries_iso_code_2='US'");

    }

    function remove() {
      global $db;

      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_SFC_STATUS', 'MODULE_SHIPPING_SFC_TAX_CLASS', 'MODULE_SHIPPING_SFC_TAX_BASIS', 'MODULE_SHIPPING_SFC_SORT_ORDER', 'MODULE_SHIPPING_SFC_SKIPPED', 'MODULE_SHIPPING_SFC_CURL_PROXY_REQUIRED', 'MODULE_SHIPPING_SFC_CURL_PROXY_SERVER_DETAILS');

      $query = "SELECT configuration_key FROM " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE_SHIPPING_SFC_%'";
      $result = mysql_query($query);
      // sort ($result, SORT_NUMERIC); 

      while ( $row = mysql_fetch_array($result, MYSQL_NUM) ) {
        $keys[] = $row[0];
      } 

      return $keys;
    }

  }

    function getCountryName($country_id) {
      global $db;

      switch ($country_id){
        case 44: 
          $countryname = 'China';
          break;
        case 101:
          $countryname = 'Iran'; 
          break; 
        case 112:
          $countryname = 'North Korea'; 
          break; 
        case 113:
          $countryname = 'Korea'; 
          break; 
        case 176:
          $countryname = 'Russia'; 
          break; 
        case 223:
          $countryname = 'United States Of America'; 
          break;
        case 230:
          $countryname = 'Vietnam'; 
          break; 
        case 231:
          $countryname = 'British Virgin Islands'; 
          break; 
        case 232:
          $countryname = 'US Virgin Islands'; 
          break; 
        default:
          $countryname_query = $db->Execute("SELECT countries_name FROM " . TABLE_COUNTRIES . " WHERE countries_id = '" . (int)$country_id . "'");
          $countryname = $countryname_query->fields['countries_name'];
          break;
      }

      return $countryname;
    }

?>
