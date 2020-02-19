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



if ($certification_check_status === true) {
    // valid
    $status_img = 'check_32.png';
    $status_css = 'color: green';
    $status_txt = MAILBEEZ_CERTIFICATE_STATUS_VALID;

} elseif (constant($cert_key) == '' ) {
    // enter certificate
    $status_img = 'attention_32.png';
    $status_css = 'color: orange';
    $status_txt = MAILBEEZ_CERTIFICATE_STATUS_ENTER;

} else {
    // invalid
    $status_img = 'block_32.png';
    $status_css = 'color: red';
    $status_txt = MAILBEEZ_CERTIFICATE_STATUS_INVALID;
}



// mh_image(MH_ADMIN_DIR_WS_IMAGES .'cert_32.png', '', '32', '32', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"')

$certificate_status_plugin = '
        <div class="mb_action_box">' . '' . '
        <div class="mb_action_box headline">' . TABLE_HEADING_CERTIFICATE_INFO . '</div>
        <div class="mb_action_box text" style="text-align:center">' . mh_image(MH_ADMIN_DIR_WS_IMAGES . $status_img, '', '32', '32', 'align="absmiddle" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '<span style="font-size: 14px; font-weight: bold;' . $status_css . '">' . $status_txt . '</span>' . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $plugin_actions . '</div></div>
        </div>';

?>