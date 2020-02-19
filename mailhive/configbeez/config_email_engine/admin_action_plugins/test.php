<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  Licensing Terms & Other Conditions of this
  Mailbeez PRO Module:

  Unless you have our prior written consent, you must NOT directly or indirectly license,
  sub-license, sell, resell, or provide for free or make an offer to do any of these things.
  All of these things are strictly prohibited with this MailBeez Pro Module.

  One Purchase for one Shop - please contact cord@mailbeez.com for volume discounts and
  reseller agreements.

  OpenSource but not free - please respect this license agreement.

 */


$common_plugin_actions = mb_admin_button(MAILBEEZ_MAILHIVE_URL . 'test&module=mailbeez_phpmailer_test', MAILBEEZ_BUTTON_SEND_TESTMAIL);

$contents[] = array('text' => '<div class="mb_action_box">' . mh_image(MH_ADMIN_DIR_WS_IMAGES .'icon_test_32.png', '', '32', '32', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_SEND_TESTMAIL_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_SEND_TESTMAIL_TEXT . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $common_plugin_actions . '</div></div>
        </div>');


?>