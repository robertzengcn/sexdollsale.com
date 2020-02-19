<?php
/**
  Facebookall fanbox  sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_fanbox {
    var $title, $output;
	
    function fball_fanbox() {
	  $this->code = 'fball_fanbox';
      $this->title = MODULE_FBALL_FANBOX_TITLE;
      $this->description = MODULE_FBALL_FANBOX_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_FANBOX_SORT_ORDER;
      $this->pageurl = MODULE_FBALL_FANBOX_PAGEURL;
      $this->height = MODULE_FBALL_FANBOX_HEIGHT;
      $this->width = MODULE_FBALL_FANBOX_WIDTH;
      $this->faces = MODULE_FBALL_FANBOX_FACES;
      $this->stream = MODULE_FBALL_FANBOX_STREAM;
      $this->fbheader = MODULE_FBALL_FANBOX_HEADER;
	  $this->color = MODULE_FBALL_FANBOX_COLOR;
      $this->custom_title = MODULE_FBALL_FANBOX_CUSTOM_TITLE;
      $this->output = array();
    }

   function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_FANBOX_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_FANBOX_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_FANBOX_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_FBALL_FANBOX_CUSTOM_TITLE', 'Facebook All fanbox', 'Enter the Module Title of your choice', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facebook page url', 'MODULE_FBALL_FANBOX_PAGEURL', '', 'Enter the facebook page url', '6', '0', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fanbox height', 'MODULE_FBALL_FANBOX_HEIGHT', '300', 'Enter height of fanbox', '6', '0', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fanbox width', 'MODULE_FBALL_FANBOX_WIDTH', '140', 'Enter width of fanbox', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show faces', 'MODULE_FBALL_FANBOX_FACES', 'YES', 'select yes if you want show faces of likes', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show stream', 'MODULE_FBALL_FANBOX_STREAM', 'NO', 'select yes if you want show stream.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show header', 'MODULE_FBALL_FANBOX_HEADER', 'YES', 'select yes if you want show header.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Color scheme', 'MODULE_FBALL_FANBOX_COLOR', 'light', 'select color scheme for fanbox.', '6', '1', 'zen_cfg_select_option(array(\'light\', \'dark\'), ', now())");
    }

   function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_FBALL_FANBOX_STATUS','MODULE_FBALL_FANBOX_SORT_ORDER','MODULE_FBALL_FANBOX_PAGEURL','MODULE_FBALL_FANBOX_HEIGHT','MODULE_FBALL_FANBOX_WIDTH','MODULE_FBALL_FANBOX_FACES','MODULE_FBALL_FANBOX_STREAM','MODULE_FBALL_FANBOX_HEADER','MODULE_FBALL_FANBOX_CUSTOM_TITLE', 'MODULE_FBALL_FANBOX_COLOR');
    }
  }
?>