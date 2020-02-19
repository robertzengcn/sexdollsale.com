<?php
/**
 * @package admin
 * @copyright Copyright 2003-2013 Zen Cart Development Team
 * @copyright Portions Copyright (c) 2011 Eric Hynds
 * Adapted from concepts shared at http://www.erichynds.com/jquery/a-new-and-improved-jquery-idle-timeout-plugin/
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id$
 */

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

if (!defined('TEXT_KEEPALIVE_BUTTON_YES')) define('TEXT_KEEPALIVE_BUTTON_YES', 'Yes, Keep Working');
if (!defined('TEXT_KEEPALIVE_BUTTON_NO')) define('TEXT_KEEPALIVE_BUTTON_NO', 'No, Logoff');
if (!defined('TEXT_KEEPALIVE_WARNING_PREFIX')) define('TEXT_KEEPALIVE_WARNING_PREFIX', 'Warning: %s seconds until log out | ');
if (!defined('TEXT_KEEPALIVE_EXPIRED_PREFIX')) define('TEXT_KEEPALIVE_EXPIRED_PREFIX', '!!Expired Session');
if (!defined('TEXT_KEEPALIVE_SESSION_EXPIRED_HEADER')) define('TEXT_KEEPALIVE_SESSION_EXPIRED_HEADER', 'Your session has expired');
if (!defined('TEXT_KEEPALIVE_SESSION_EXPIRED_MESSAGE')) define('TEXT_KEEPALIVE_SESSION_EXPIRED_MESSAGE', '<p class="ui-state-error-text"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>Sorry, you have been logged out due to inactivity.</p><p>Any unsaved work was lost. If you were composing content which you wish to save, click Close and copy that content to your clipboard. Then refresh the page to login again.</p><p>To continue, please login again.</p>');
if (!defined('TEXT_KEEPALIVE_SERVER_UNREACHABLE_HEADER')) define('TEXT_KEEPALIVE_SERVER_UNREACHABLE_HEADER', 'Problem connecting to the server');
if (!defined('TEXT_KEEPALIVE_SERVER_UNREACHABLE_MESSAGE')) define('TEXT_KEEPALIVE_SERVER_UNREACHABLE_MESSAGE', '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>We are unable to connect to the server. Possible causes:<ul><li>Server is down</li><li>You have been logged out due to inactivity</li><li>Problems with your internet connection</li></ul>Your work may be lost. Please review your work and perhaps copy information to your clipboard if you had any work in progress which you do not wish to lose.</p>');
if (!defined('TEXT_KEEPALIVE_SERVER_UNREACHABLE_MESSAGE1')) define('TEXT_KEEPALIVE_SERVER_UNREACHABLE_MESSAGE1', 'We are unable to connect to the server. Your work may be lost. Please review your work and perhaps copy information to your clipboard if you had any work in progress which you do not wish to lose.');
if (!defined('TEXT_KEEPALIVE_BUTTON_CLOSE')) define('TEXT_KEEPALIVE_BUTTON_CLOSE', 'Close');
if (!defined('TEXT_KEEPALIVE_BUTTON_LOGIN')) define('TEXT_KEEPALIVE_BUTTON_LOGIN', 'Login');
if (!defined('TEXT_KEEPALIVE_MESSAGE_YOU_WILL_LOG_OFF')) define('TEXT_KEEPALIVE_MESSAGE_YOU_WILL_LOG_OFF', 'You will be logged off in ');
if (!defined('TEXT_KEEPALIVE_MESSAGE_MINUTES')) define('TEXT_KEEPALIVE_MESSAGE_MINUTES', 'minutes');
if (!defined('TEXT_KEEPALIVE_MESSAGE_ASK_CONTINUE')) define('TEXT_KEEPALIVE_MESSAGE_ASK_CONTINUE', 'Do you want to continue your session?');
?>
<!--  BOF: Keepalive for Session -->
<!-- timeout warning alert -->
<div id="keepalivetimer" title="Your session is about to expire!" style="display: none">
    <p class="ui-state-error-text">
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
        <?php echo TEXT_KEEPALIVE_MESSAGE_YOU_WILL_LOG_OFF; ?> <span id="keepalivetimer-countdown" style="font-weight:bold"></span> <?php echo TEXT_KEEPALIVE_MESSAGE_MINUTES?>.
    </p>

    <p><?php echo TEXT_KEEPALIVE_MESSAGE_ASK_CONTINUE;?></p>
</div>
<link href="includes/javascript/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
  if (typeof jQuery == "undefined") {//no jquery yet
    document.write('<scr'+'ipt type="text/javascript" src="includes/javascript/jquery.min.js">');
    document.write('</scr' + 'ipt>');
  }
</script>
<script type="text/javascript">
  if (!jQuery.ui) {
    document.write('<scr'+'ipt type="text/javascript" src="includes/javascript/jquery-ui.min.js">');
    document.write('</scr' + 'ipt>');
  }
</script>
<script src="includes/javascript/jquery.idletimer.js<?php echo '?t='.time();?>" type="text/javascript"></script>
<script src="includes/javascript/jquery.idletimeout.js<?php echo '?t='.time();?>" type="text/javascript"></script>
<style type="text/css">
  a.ui-dialog-titlebar-close {display:none;}
  .ui-widget-overlay { background: green; opacity: .40;filter:Alpha(Opacity=40); }
</style>
<script type="text/javascript">
//setup the dialog
$("#keepalivetimer").dialog({
  autoOpen: false,
  modal: true,
  width: 430,
  height: 250,
  closeOnEscape: false,
  draggable: false,
  resizable: false,
  position: "top",
  buttons: {
    '<?php echo TEXT_KEEPALIVE_BUTTON_YES;?>': function(){
      $(this).dialog('close');
    },
    '<?php echo TEXT_KEEPALIVE_BUTTON_NO;?>': function(){
      $.idleTimeout.options.onLogoffClick.call(this);
    }
  }
});

// start the idle timer monitor
var $countdown = $("#keepalivetimer-countdown");
$.idleTimeout('#keepalivetimer', 'div.ui-dialog-buttonpane button:first', {
  idleAfter: 600, // 600 user is considered idle after 10 minutes of no movement in this browser window/tab
  warningLength: 250, // 250 countdown timer starts at this many seconds (250sec=4:50min)
  pollingInterval: 60, //60  check for server connection every minute; if it fails or user is logged out, keepalive scripts will abort
  keepAliveURL: 'keepalive.php', serverResponseEquals: 'OK',
  titleMessage: '<?php echo TEXT_KEEPALIVE_WARNING_PREFIX;?>',
  onTimeout: function(){
    document.title = '<?php echo TEXT_KEEPALIVE_EXPIRED_PREFIX;?>';
    $(this).html('<?php echo TEXT_KEEPALIVE_SESSION_EXPIRED_MESSAGE;?>');
    $(this).dialog("option", "title", '<?php echo TEXT_KEEPALIVE_SESSION_EXPIRED_HEADER;?>');
    $(this).dialog("option", "minWidth", "450");
    $(this).dialog("option", "buttons", {'<?php echo TEXT_KEEPALIVE_BUTTON_CLOSE;?>': function(){$(this).dialog('close');},'<?php echo TEXT_KEEPALIVE_BUTTON_LOGIN;?>': function(){window.location.reload();}});
    //$(this).dialog("option", "buttons", {'<?php echo TEXT_KEEPALIVE_BUTTON_LOGIN;?>': function(){window.location.reload();}    });
  },
  onAbort: function(){
    // TODO: another modal dialog would be more friendly
    alert('<?php echo TEXT_KEEPALIVE_SERVER_UNREACHABLE_MESSAGE1;?>');
  },
  onIdle: function(){
    $(this).dialog("open");
  },
  onLogoffClick: function(){
    window.location = "logoff.php";
  },
  onCountdown: function(counter){
    var sec = counter % 60;
    var min = Math.floor(counter/60);
    if (sec < 0) {
      sec = 59;
      min = min - 1;
    }
    if (sec<=9) { sec = "0" + sec; }
    var time = (min<=9 ? "0" + min : min) + ":" + sec;
    $countdown.html(time);
  }
});
</script>
<!--  EOF: Keepalive for Session -->
