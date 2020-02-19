<?php
/*
 MailBeez Automatic Trigger Email Campaigns
 http://www.mailbeez.com

 Copyright (c) 2010 - 2012 MailBeez

 inspired and in parts based on
 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License

 v2.6
*/


mh_define('MH_POPUP', true);

mh_load_modules_language_files(MH_DIR_CONFIG, 'config_analytics', MH_FILE_EXTENSION);
$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
if ($app_action == 'activate') {
    mh_insert_config_value(array('configuration_title' => 'Enable MailBeez Analytics',
        'configuration_key' => 'MAILBEEZ_ANALYTICS_STATUS',
        'configuration_value' => 'True'
    ), true);

    mh_insert_config_value(array('configuration_title' => 'MailBeez Analytics Beginning of Time',
        'configuration_key' => 'MAILBEEZ_ANALYTICS_BEGIN_OF_TIME',
        'configuration_value' => date('Y-m-d H:i:s')
    ), true);

    mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'tab=home'));
    //exit();
}


?>
<div style="text-align: center">

    <?php echo MAILBEEZ_ANALYTICS_STATUS_WARNING;?>
    <br/>
    <br/>

    <div style="border: 1px solid #c0c0c0; text-align: left; padding: 10px;">
        <code style="font-family: courier;">
            // MailBeez Click and Order tracker
            <br>
            require(DIR_FS_CATALOG . 'mailhive/includes/clicktracker.php');
        </code>
    </div>

    <br/>
    <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'module=config_analytics&app=load_app&app_path=config_analytics/admin_application_plugins/confirm.php&app_action=activate'), MAILBEEZ_ANALYTICS_STATUS_BUTTON_ACTIVATE, '', 'link', 'button', 'target="_parent"', 'parent.document');
    ?>
</div>
