<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v1.0
*/

mh_load_modules_language_files(MH_DIR_CONFIG, 'config_edit_promo', MH_FILE_EXTENSION);


$plugin_actions = ''; mb_admin_button( '', MAILBEEZ_BUTTON_CONFIG_EDIT_PROMO, '', 'link') . '';


//('http://www.mailbeez.com/documentation/configbeez/config_tmplmngr/' . MH_LINKID_1

$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . MAILBEEZ_CONFIG_EDIT_PROMO_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_CONFIG_EDIT_PROMO_TEXT . '</div>
        <div class="mb_action_box buttons"><div align="center">' . $plugin_actions . '</div></div>
        <div class="mb_action_box text">' . MAILBEEZ_CONFIG_EDIT_PROMO_TEXT_DESCRIPTION . '</div>
        </div>');




?>