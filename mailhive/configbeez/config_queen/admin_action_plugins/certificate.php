<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6.5
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

$plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_queen/admin_application_plugins/certificate.php&popup=true&cert_key=' . $cert_key . '&cert_module=' . $cert_module), MAILBEEZ_CERTIFICATE_BUTTON, '', 'popup', 'button', '', 'document', 'iframe width:500 height:300');

$certificate_plugin = '
        <div class="mb_action_box">' . mh_image(MH_ADMIN_DIR_WS_IMAGES .'cert.png', '', '59', '66', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '
        <div class="mb_action_box headline">' . TABLE_HEADING_CERTIFICATE_INFO . '</div>
        <div class="mb_action_box text">' . TABLE_HEADING_CERTIFICATE_HELP_TOP . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $plugin_actions . '</div></div>
        </div>';

?>