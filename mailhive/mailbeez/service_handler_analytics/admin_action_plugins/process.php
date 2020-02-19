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
$dequeue_action = mb_admin_button(MAILBEEZ_MAILHIVE_URL_DIRECT . '?m=service_handler_analytics&ma=tracking_process', BUTTON_TRACKING_PROCESS);


$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_TRACKING_PROCESS_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_TRACKING_PROCESS_TEXT . '</div>
        <div class="mb_action_box buttons"><div align="center">' . $dequeue_action . '</div>
        </div></div>');

?>