<?php
/**
  Facebookall recommendations sidebox by sourceaddons 
  @copyright Copyright sourceaddons, 2012
  @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
**/

  class fball_recommend {
    var $title, $output;
	
    function fball_recommend() {
	  $this->code = 'fball_recommend';
      $this->title = MODULE_FBALL_RECOMMEND_TITLE;
      $this->description = MODULE_FBALL_RECOMMEND_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_RECOMMEND_SORT_ORDER;
      $this->pageurl = MODULE_FBALL_RECOMMEND_PAGEURL;
      $this->height = MODULE_FBALL_RECOMMEND_HEIGHT;
      $this->width = MODULE_FBALL_RECOMMEND_WIDTH;
      $this->fbheader = MODULE_FBALL_RECOMMEND_HEADER;
	  $this->color = MODULE_FBALL_RECOMMEND_COLOR;
      $this->custom_title = MODULE_FBALL_RECOMMEND_CUSTOM_TITLE;
	  $this->output = array();
    }

   function process() {}

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_RECOMMEND_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }
	
    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_RECOMMEND_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_RECOMMEND_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");

	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title', 'MODULE_FBALL_RECOMMEND_CUSTOM_TITLE', 'Facebook All recommendations', 'Title will be show above the box', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Domain', 'MODULE_FBALL_RECOMMEND_PAGEURL', '', 'Enter your domain name/url', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('recommend height', 'MODULE_FBALL_RECOMMEND_HEIGHT', '300', 'Enter height of recommend box', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('recommend width', 'MODULE_FBALL_RECOMMEND_WIDTH', '140', 'Enter width of recommend box', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show header', 'MODULE_FBALL_RECOMMEND_HEADER', 'YES', 'select yes if you want show header.', '6', '1', 'zen_cfg_select_option(array(\'YES\', \'NO\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Color scheme', 'MODULE_FBALL_RECOMMEND_COLOR', 'light', 'select color scheme for recommendations box.', '6', '1', 'zen_cfg_select_option(array(\'light\', \'dark\'), ', now())");
    }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
	
    function keys() {
      return array('MODULE_FBALL_RECOMMEND_STATUS','MODULE_FBALL_RECOMMEND_SORT_ORDER','MODULE_FBALL_RECOMMEND_PAGEURL','MODULE_FBALL_RECOMMEND_HEIGHT','MODULE_FBALL_RECOMMEND_WIDTH','MODULE_FBALL_RECOMMEND_HEADER','MODULE_FBALL_RECOMMEND_CUSTOM_TITLE','MODULE_FBALL_RECOMMEND_COLOR');
    }
  }
?>