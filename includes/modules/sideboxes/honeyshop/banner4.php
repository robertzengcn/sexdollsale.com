<?php

 $define_leftbanner4_page=zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', 'define_page_4.php', 'false');

    require($template->get_template_dir('tpl_box_default_left2.php', DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . 'tpl_box_default_left2.php');
?>