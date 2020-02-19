<?php
/**
 * @package edit_orders
 * @copyright Copyright (C) 2008 office andplus
 * @copyright Portions Copyright (C) 2008 Zen Cart.JP
 * @copyright Portions Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @author mmochi
 * @version $Id: edit_orders.php v3.0.1$
 */


  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_get_country_id
  //
  // Arguments   : country_name		country name string
  //
  // Return      : country_id
  //
  // Description : Function to retrieve the country_id based on the country's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function zen_get_country_id($country_name) {

   global $db;
    $country_id_query = $db -> Execute("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");

    if (!$country_id_query->RecordCount()) {
      return 0;
    }
    else {
      return $country_id_query->fields['countries_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_get_country_iso_code_2
  //
  // Arguments   : country_id		country id number
  //
  // Return      : country_iso_code_2
  //
  // Description : Function to retrieve the country_iso_code_2 based on the country's id
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function zen_get_country_iso_code_2($country_id) {
      global $db;
    $country_iso_query = $db -> Execute("select * from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");

    if (!zen_db_num_rows($country_iso_query)) {
      return 0;
    }
    else {
      $country_iso_row = zen_db_fetch_array($country_iso_query);
      return $country_iso_row['countries_iso_code_2'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_get_zone_id
  //
  // Arguments   : country_id		country id string
  //               zone_name		state/province name
  //
  // Return      : zone_id
  //
  // Description : Function to retrieve the zone_id based on the zone's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function zen_get_zone_id($country_id, $zone_name) {
      global $db;
    $zone_id_query = $db -> Execute("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");

    if (!$zone_id_query->RecordCount()) {
      return 0;
    }
    else {
      return $zone_id_query->fields['zone_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_field_exists
  //
  // Arguments   : table	table name
  //               field	field name
  //
  // Return      : true/false
  //
  // Description : Function to check the existence of a database field
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 
 * 

  function zen_field_exists($table,$field) {
   global $db;

    $describe_query = $db -> Execute("describe $table");
    while (!$describe_query -> EOF){
      if ($d_row["Field"] == "$field") {
         return true;
      }
      $describe_query -> MoveNext();
    }
    return false;
  }
*/
  function zen_field_exists($table,$field) {
   global $db;

    $describe_query = $db -> Execute("describe $table");
    while (!$describe_query -> EOF){
      if ($describe_query->fields["Field"] == "$field") {
         return true;
      }
      $describe_query -> MoveNext();
    }
    return false;
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_html_quotes
  //
  // Arguments   : string	any string
  //
  // Return      : string with single quotes converted to html equivalent
  //
  // Description : Function to change quotes to HTML equivalents for form inputs.
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function zen_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : zen_html_unquote
  //
  // Arguments   : string	any string
  //
  // Return      : string with html equivalent converted back to single quotes
  //
  // Description : Function to change HTML equivalents back to quotes
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function zen_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }


  function zen_get_paymod_name($modules) {
	$MODULE_PAYMENT_INSTALLED = MODULE_PAYMENT_INSTALLED;
	$paymod = split(';',ereg_replace("(.php)","",$MODULE_PAYMENT_INSTALLED));
	$modules_array = '';
	for($i =0, $n=sizeof($paymod); $i<$n; $i++){
		require_once(DIR_FS_CATALOG_MODULES . 'payment/' . $paymod[$i] . '.php');
		require_once(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/payment/' . $paymod[$i] . '.php');
			$module[$i] = new $paymod[$i];
			$modules_array = array($paymod[$i]=>$module[$i]->title);
	
		if(MODULE_ORDER_TOTAL_GV_STATUS || MODULE_ORDER_TOTAL_COUPON_STATUS){
			$modules_array2 = array( 'GV/DC' => EO_PAYMOD_GIFT_COUPON );
			$modules_array = array_merge($modules_array,$modules_array2);
		}
	
		if(MODULE_ORDER_TOTAL_POINTS_USE_STATUS){
			$modules_array3 = array( 'POINTS' => EO_PAYMOD_POINTS );
			$modules_array = array_merge($modules_array,$modules_array3);
		}
		while(list ($key, $val) = each($modules_array)){
			if($modules == $key){
				$paymod_name = $val;
			}
		}
	}
    return $paymod_name;
  }

  function zen_get_payment_modules_values($modules = '') {

    if (empty($modules)) return false;

    $modules_array = split(';', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }
    return $count;
  }


  function zen_draw_pull_down_payments($name, $values, $default = '', $parameters = '') {

    $field = '<select name="' . zen_output_string($name) . '"';

    if (zen_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>' . "\n";

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '  <option value="' . zen_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . zen_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>' . "\n";
    }
    $field .= '</select>' . "\n";


    return $field;
  }
  function selection() {

    $selection_array = array();

    if (is_array($this->modules)) {
      reset($this->modules);
      while (list(, $value) = each($this->modules)) {
        $class = substr($value, 0, strrpos($value, '.'));
        if ($GLOBALS[$class]->enabled) {
          $selection = $GLOBALS[$class]->selection();
          if (is_array($selection)) $selection_array[] = $selection;
        }
      }
    }

    return $selection_array;
  }
?>