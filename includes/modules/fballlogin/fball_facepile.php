<?php
/**
  Facebookall facepile  sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_facepile {
    var $title, $output;
	
    function fball_facepile() {
	  $this->code = 'fball_facepile';
      $this->title = MODULE_FBALL_FACEPILE_TITLE;
      $this->description = MODULE_FBALL_FACEPILE_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_FACEPILE_SORT_ORDER;
      $this->pageurl = MODULE_FBALL_FACEPILE_PAGEURL;
      $this->numrows = MODULE_FBALL_FACEPILE_NUMROWS;
      $this->width = MODULE_FBALL_FACEPILE_WIDTH;
      $this->custom_title = MODULE_FBALL_FACEPILE_CUSTOM_TITLE;
	  $this->output = array();
    }

    function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_FACEPILE_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
	  global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_FACEPILE_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_FACEPILE_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_FBALL_FACEPILE_CUSTOM_TITLE', 'Facebook All facepile', 'Enter the Module Title of your choice', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Page url', 'MODULE_FBALL_FACEPILE_PAGEURL', '', 'To display users who have liked your page, specify the URL of your page', '6', '0', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Num rows', 'MODULE_FBALL_FACEPILE_NUMROWS', '1', 'Enter number of rows you want to display', '6', '0', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facepile box width', 'MODULE_FBALL_FACEPILE_WIDTH', '140', 'Enter width of facepile box', '6', '0', now())");
     
    }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_FBALL_FACEPILE_STATUS','MODULE_FBALL_FACEPILE_SORT_ORDER','MODULE_FBALL_FACEPILE_PAGEURL','MODULE_FBALL_FACEPILE_NUMROWS','MODULE_FBALL_FACEPILE_WIDTH','MODULE_FBALL_FACEPILE_CUSTOM_TITLE');
    }
  }
?>