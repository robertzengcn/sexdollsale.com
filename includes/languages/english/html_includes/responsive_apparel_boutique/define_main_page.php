<?php
if ($this_is_home_page) {
?>
 <?php
    if (RAP_SLIDES_STATUS == 'true') {
?>
      <?php require($template->get_template_dir('tpl_home_slider.php',DIR_WS_TEMPLATE, $current_page_base,'common')
		    . '/tpl_home_slider.php');?>
<?php
    } ?>
<?php
    } ?>

<br class="clearBoth">
<br class="clearBoth">