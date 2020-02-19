<?php
/**
  Facebookall comments sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_comments {
    var $title, $output;
	
    function fball_comments() {
	  $this->code = 'fball_comments';
      $this->title = MODULE_FBALL_COMMENTS_TITLE;
      $this->description = MODULE_FBALL_COMMENTS_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_COMMENTS_SORT_ORDER;
      $this->height = MODULE_FBALL_COMMENTS_NUMPOST;
      $this->width = MODULE_FBALL_COMMENTS_WIDTH;
      $this->color = MODULE_FBALL_COMMENTS_COLOR;
      $this->output = array();
    }

   function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_COMMENTS_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_COMMENTS_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_COMMENTS_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of posts', 'MODULE_FBALL_COMMENTS_NUMPOST', '2', 'number of posts to display', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Comments width', 'MODULE_FBALL_COMMENTS_WIDTH', '300', 'Enter width of comments box', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Color scheme', 'MODULE_FBALL_COMMENTS_COLOR', 'light', 'select color scheme for comments.', '6', '1', 'zen_cfg_select_option(array(\'light\', \'dark\'), ', now())");
    }

   function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_FBALL_COMMENTS_STATUS','MODULE_FBALL_COMMENTS_SORT_ORDER','MODULE_FBALL_COMMENTS_NUMPOST','MODULE_FBALL_COMMENTS_WIDTH', 'MODULE_FBALL_COMMENTS_COLOR');
    }
  }
?>