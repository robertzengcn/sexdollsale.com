<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5
 */


// this page is not in the context of the shopping system
// you can't use the shop systems functions
//
// if you would like to redirect to a shop page
// please specify you url here

/*
  $url = 'http://www.yourshop.com/noblock_confirmation_page.php'; // example
  header('Location: ' . $url);
  exit();
 */


?>
<html>
<head>
    <title>MailHive Block</title>
</head>

<style>

    .mailbeez_message {
        margin: auto;
        width: 500px;
        padding: 30px;
        font-family: arial;
        border: 1px solid red;

    }

</style>

<body>

<div class="mailbeez_message">
    Dear Customer,<br />
    <br />
    You can not block this module
    <br />
    <br />
    <br />
    <br />
    <!-- please keep this link - or donate on www.mailbeez.com -->
    <img src="../common/images/been_tiny.gif" border="0" hspace="0" vspace="0" width="33" height="28" align="absmiddle">
    powered by <a href="http://www.MailBeez.com" target="_blank">MailBeez.com</a>

    <div>

</body>
</html>
