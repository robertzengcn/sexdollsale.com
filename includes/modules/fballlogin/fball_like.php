<?php
/**
  Facebookall like  sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_like {
    var $title, $output;
	
    function fball_like() {
	  $this->code = 'fball_like';
      $this->title = MODULE_FBALL_LIKE_TITLE;
      $this->description = MODULE_FBALL_LIKE_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_LIKE_SORT_ORDER;
      $this->pageurl = MODULE_FBALL_LIKE_PAGEURL;
      $this->width = MODULE_FBALL_LIKE_WIDTH;
      $this->faces = MODULE_FBALL_LIKE_FACES;
      $this->send = MODULE_FBALL_LIKE_SEND;
      $this->profileurl = MODULE_FBALL_LIKE_PROURL;
      $this->custom_title = MODULE_FBALL_LIKE_CUSTOM_TITLE;
	  $this->output = array();
	}

    function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LIKE_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_LIKE_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_LIKE_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_FBALL_LIKE_CUSTOM_TITLE', 'Facebook All like', 'Enter the Module Title of your choice', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Url to like', 'MODULE_FBALL_LIKE_PAGEURL', '', 'Enter the url to like', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Profile url for subscribe button', 'MODULE_FBALL_LIKE_PROURL', '', 'Enter the profile url to subscribe', '6', '0', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('like width', 'MODULE_FBALL_LIKE_WIDTH', '140', 'Enter width of like button', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show faces', 'MODULE_FBALL_LIKE_FACES', 'YES', 'select yes if you want show faces of likes', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Send button', 'MODULE_FBALL_LIKE_SEND', 'YES', 'select yes if you want show send button.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
    }

   function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
	
    function keys() {
      return array('MODULE_FBALL_LIKE_STATUS','MODULE_FBALL_LIKE_SORT_ORDER','MODULE_FBALL_LIKE_PAGEURL','MODULE_FBALL_LIKE_WIDTH','MODULE_FBALL_LIKE_FACES','MODULE_FBALL_LIKE_SEND','MODULE_FBALL_LIKE_PROURL','MODULE_FBALL_LIKE_CUSTOM_TITLE');
    }
  }
?>