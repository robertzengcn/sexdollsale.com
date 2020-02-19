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


mh_define('MH_POPUP', true);

$cert_key = $_GET['cert_key'];
$cert_module = $_GET['cert_module'];


?>
<div style="background-color: #F8FCFF;
    border: 1px solid #DDEDF3; padding: 20px;">
    <?php echo mh_draw_form('modules', FILENAME_MAILBEEZ, '&module=' . $cert_module . '&action=save', 'post', 'target="_parent"')?>

    <?php echo mh_image(MH_ADMIN_DIR_WS_IMAGES .'cert.png', '', '59', '66', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"'); ?>
    <h1><?php echo TABLE_HEADING_CERTIFICATE_INFO;?></h1>
    <?php echo TABLE_HEADING_CERTIFICATE_HELP_TOP;?>



    <?php echo mh_draw_textarea_field('configuration[' . $cert_key . ']', 'on', '40', '10', constant($cert_key), 'style="width: 400px; height: 100px"');?>
    <br/>
    <br/>
    <?php
    if (MH_PLATFORM_OSC_23) {
        echo  tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary');
    } else {
        echo mh_image_submit('button_update.gif', IMAGE_UPDATE);
    }

    ?>

</div>
