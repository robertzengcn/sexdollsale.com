<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6.3
 */


///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
if ($app_action == 'inline') {
    $back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=config_simulation&restart=ok');

    // clear simulation data
    // redirect to back_url
    mh_simulation_restart();
    mh_redirect($back_url);

} elseif ($app_action == 'restart') {
    mh_simulation_restart();
    mh_define('MH_POPUP', true);
    mh_load_modules_language_files(MH_DIR_CONFIG, 'config_simulation', MH_FILE_EXTENSION);
}
?>
<div style="text-align: center">
    <?php echo MAILBEEZ_SIMULATION_RESTARTED_MSG;?>
    <script type="text/javascript">
        window.setTimeout("mbCloseBox()", 1500);
        function mbCloseBox() {
            parent.$.fn.ceebox.closebox();
        }
    </script>
</div>