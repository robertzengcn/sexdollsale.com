<?php
/**
 * custom sidebox - display your custom content
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: categories.php 2718 2005-12-28 06:42:39Z drbyte $
 */
    
    echo '<section class="sideBoxContainer" id="custom_sidebox_2">';        
        require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_CUSTOM_SIDEBOX_2, 'false'));
    echo '</section>';
?>