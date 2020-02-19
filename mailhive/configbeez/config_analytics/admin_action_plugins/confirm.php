<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  Licensing Terms & Other Conditions of this
  Mailbeez Module:

  Unless you have our prior written consent, you must NOT directly or indirectly license,
  sub-license, sell, resell, or provide for free or make an offer to do any of these things.
  All of these things are strictly prohibited with this MailBeez Pro Module.

  One Purchase for one Shop - please contact cord@mailbeez.com for volume discounts and
  reseller agreements.

  OpenSource but not free - please respect this license agreement.

 */

mh_load_modules_language_files(MH_DIR_CONFIG, 'config_analytics', MH_FILE_EXTENSION);

$plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_analytics/admin_application_plugins/confirm.php&popup=true'), MAILBEEZ_ANALYTICS_STATUS_BUTTON, '', 'popup', 'button', '', 'document', 'iframe width:450 height:300');



$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . TABLE_HEADING_ANALYTICS_INFO . '</div>
        <div class="mb_action_box text">' . TABLE_HEADING_ANALYTICS_HELP_TOP . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $plugin_actions . '</div></div>
        </div>');

?>