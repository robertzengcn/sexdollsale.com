<?php
/**
 *  fball login module
 *
 * @package fball login
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: fballlogin.php 6725 2012-09-19 03:48:28Z sourecaddons $
 */
  class fballlogin {
    var $title, $output;
    function fballlogin() {
      $this->code = 'fballlogin';
      $this->title = MODULE_FBALL_LOGIN_TITLE;
      $this->description = MODULE_FBALL_LOGIN_DESCRIPTION;
      $this->sort_order = MODULE_FBALL_LOGIN_SORT_ORDER;
	  $this->api_key = MODULE_FBALL_LOGIN_API_KEY;
	  $this->api_secret_key = MODULE_FBALL_LOGIN_API_SECRET_KEY;
	  $this->subtitle = MODULE_FBALL_LOGIN_SUBTITLE;
	  $this->useapi = (MODULE_FBALL_LOGIN_USEAPI == 'CURL');
	  $this->link_account = (MODULE_FBALL_LOGIN_LINKACCOUNT == 'True');
	  $this->post = (MODULE_FBALL_LOGIN_POST == 'False');
	  $this->posttitle = MODULE_FBALL_LOGIN_POST_TITLE;
	  $this->posturl = MODULE_FBALL_LOGIN_POST_URL;
	  $this->postmsg = MODULE_FBALL_LOGIN_POST_MSG;
	  $this->postpic = MODULE_FBALL_LOGIN_POST_PIC;
	  $this->postdesc = MODULE_FBALL_LOGIN_POST_DESC;
      $this->output = array();
    }

    function process() {
     
    }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_STATUS'");
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }

    function keys() {
	  return array('MODULE_FBALL_LOGIN_STATUS', 'MODULE_FBALL_LOGIN_SORT_ORDER','MODULE_FBALL_LOGIN_API_KEY','MODULE_FBALL_LOGIN_API_SECRET_KEY','MODULE_FBALL_LOGIN_SUBTITLE','MODULE_FBALL_LOGIN_USEAPI','MODULE_FBALL_LOGIN_LINKACCOUNT','MODULE_FBALL_LOGIN_POST','MODULE_FBALL_LOGIN_POST_TITLE','MODULE_FBALL_LOGIN_POST_URL','MODULE_FBALL_LOGIN_POST_MSG','MODULE_FBALL_LOGIN_POST_PIC','MODULE_FBALL_LOGIN_POST_DESC');
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_FBALL_LOGIN_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FBALL_LOGIN_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facebook API ID', 'MODULE_FBALL_LOGIN_API_KEY', '', 'Paste facebook API ID here', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facebook API Secret', 'MODULE_FBALL_LOGIN_API_SECRET_KEY', '', 'Paste facebook API Secret here', '6', '0', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SubTitle', 'MODULE_FBALL_LOGIN_SUBTITLE', 'please login with', 'This text will be displyed above the facebook login button', '6', '0', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('API Connection Handler', 'MODULE_FBALL_LOGIN_USEAPI', 'CURL', 'To Communicate with API', '6', '1', 'zen_cfg_select_option(array(\'CURL\', \'FSCKOPEN\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Link to existing account', 'MODULE_FBALL_LOGIN_LINKACCOUNT', 'True', 'Link verified facebook profile to existing account.', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Post to new users wall', 'MODULE_FBALL_LOGIN_POST', 'False', 'Post on new users wall after register.', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
	   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Post Title', 'MODULE_FBALL_LOGIN_POST_TITLE', '', 'enter title value for post', '6', '0', now())");
       $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Post link url', 'MODULE_FBALL_LOGIN_POST_URL', '', 'enter url for post', '6', '0', now())");
       $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Post Message', 'MODULE_FBALL_LOGIN_POST_MSG', '', 'enter post message', '6', '0', now())");
       $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Post Picture Url', 'MODULE_FBALL_LOGIN_POST_PIC', '', 'enter a full url of picyure', '6', '0', now())");
       $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Post Description', 'MODULE_FBALL_LOGIN_POST_DESC', '', 'enter a full description to show on post', '6', '0', now())");


	  $db->Execute("CREATE TABLE IF NOT EXISTS ".DB_PREFIX.'fball_customer'." (customers_id int(11), customer_fbid varchar(255) NULL, customer_fb_avatar varchar(255) NULL)");
    }
	
	function remove() {
      global $db, $messageStack;
     $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	 $db->Execute("DROP TABLE ".DB_PREFIX.'fball_customer'."");
    }
  }
?>