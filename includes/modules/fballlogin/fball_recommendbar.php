<?php
/**
  Facebookall recommendations bar sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_recommendbar {
    var $title, $output;
	
    function fball_recommendbar() {
	  $this->code = 'fball_recommendbar';
      $this->title = MODULE_FBALL_RECBAR_TITLE;
      $this->description = MODULE_FBALL_RECBAR_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_RECBAR_SORT_ORDER;
      $this->pageurl = MODULE_FBALL_RECBAR_DOMAIN;
	  $this->appid = MODULE_FBALL_RECBAR_APPID;
      $this->height = MODULE_FBALL_RECBAR_READTIME;
      $this->width = MODULE_FBALL_RECBAR_VERB;
      $this->faces = MODULE_FBALL_RECBAR_SIDE;
      $this->output = array();
    }

   function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_RECBAR_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_RECBAR_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_RECBAR_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('App id', 'MODULE_FBALL_RECBAR_APPID', '', 'your app id', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Domain', 'MODULE_FBALL_RECBAR_DOMAIN', '', 'Domain which to show bar', '6', '0', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Read time', 'MODULE_FBALL_RECBAR_READTIME', '30', 'The number of seconds before the plugin will expand.', '6', '0', now())");
	   
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Verb to display', 'MODULE_FBALL_RECBAR_VERB', 'like', 'The verb display in the plugin', '6', '1', 'zen_cfg_select_option(array(\'like\', \'recommend\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Side', 'MODULE_FBALL_RECBAR_SIDE', 'left', 'the side of the screen where the plugin will be displayed.', '6', '1', 'zen_cfg_select_option(array(\'left\', \'right\'), ', now())");
    }

   function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_FBALL_RECBAR_STATUS','MODULE_FBALL_RECBAR_SORT_ORDER','MODULE_FBALL_RECBAR_DOMAIN','MODULE_FBALL_RECBAR_READTIME','MODULE_FBALL_RECBAR_VERB','MODULE_FBALL_RECBAR_SIDE','MODULE_FBALL_RECBAR_APPID');
    }
  }
?>