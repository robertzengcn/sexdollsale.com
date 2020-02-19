<?php
/**
  Facebookall activity  sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_activity {
     var $title, $output;
	
    function fball_activity() {
	  $this->code = 'fball_activity';
      $this->title = MODULE_FBALL_ACTIVITY_TITLE;
      $this->description = MODULE_FBALL_ACTIVITY_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_ACTIVITY_SORT_ORDER;
      $this->domain = MODULE_FBALL_ACTIVITY_DOMAIN;
      $this->height = MODULE_FBALL_ACTIVITY_HEIGHT;
      $this->width = MODULE_FBALL_ACTIVITY_WIDTH;
      $this->appid = MODULE_FBALL_ACTIVITY_APPID;
      $this->recommend = MODULE_FBALL_ACTIVITY_RECOMMEND;
      $this->actheader = MODULE_FBALL_ACTIVITY_HEADER;
      $this->custom_title = MODULE_FBALL_ACTIVITY_CUSTOM_TITLE;
	  $this->color = MODULE_FBALL_ACTIVITY_COLOR;
	  $this->output = array();
    }

    function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_ACTIVITY_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_ACTIVITY_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_ACTIVITY_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_FBALL_ACTIVITY_CUSTOM_TITLE', 'Facebook All activity feed', 'Enter the Module Title of your choice', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Domain', 'MODULE_FBALL_ACTIVITY_DOMAIN', '', 'Enter your domain name', '6', '0', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your app id', 'MODULE_FBALL_ACTIVITY_APPID', '', 'Enter your app id', '6', '0', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Feed box height', 'MODULE_FBALL_ACTIVITY_HEIGHT', '300', 'Enter height of feedbox', '6', '0', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Feed box width', 'MODULE_FBALL_ACTIVITY_WIDTH', '140', 'Enter width of feedbox', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show recommendations', 'MODULE_FBALL_ACTIVITY_RECOMMEND', 'YES', 'select yes if you want show recommendations.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show header', 'MODULE_FBALL_ACTIVITY_HEADER', 'YES', 'select yes if you want show header.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Color scheme', 'MODULE_FBALL_ACTIVITY_COLOR', 'light', 'select color scheme for activity box.', '6', '1', 'zen_cfg_select_option(array(\'light\', \'dark\'), ', now())");
    }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_FBALL_ACTIVITY_STATUS','MODULE_FBALL_ACTIVITY_SORT_ORDER','MODULE_FBALL_ACTIVITY_DOMAIN','MODULE_FBALL_ACTIVITY_APPID','MODULE_FBALL_ACTIVITY_WIDTH','MODULE_FBALL_ACTIVITY_HEIGHT','MODULE_FBALL_ACTIVITY_RECOMMEND','MODULE_FBALL_ACTIVITY_HEADER','MODULE_FBALL_ACTIVITY_CUSTOM_TITLE','MODULE_FBALL_ACTIVITY_COLOR');
    }
  }
?>