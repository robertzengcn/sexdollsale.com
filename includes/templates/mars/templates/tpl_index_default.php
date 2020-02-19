<?php
/**
 * Page Template
 *
 * Main index page<br />
 * Displays greetings, welcome text (define-page content), and various centerboxes depending on switch settings in Admin<br />
 * Centerboxes are called as necessary
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_index_default.php 3464 2006-04-19 00:07:26Z ajeh $
 */
?>
<div class="centerColumn" id="indexDefault">
<?php if($homepage_options->fields['homepage_index_heading_display'] == 1){ ?>
<h2 id="indexDefaultHeading"><?php echo HEADING_TITLE; ?></h2>
<?php } if (SHOW_CUSTOMER_GREETING == 1) { ?>
<h3 class="greeting"><?php echo zen_customer_greeting(); ?></h3>
<?php } ?>

<?php
 	// deprecated - to use uncomment this section
	/*if (TEXT_MAIN) { 
		<div id="" class="content"><?php echo TEXT_MAIN; ?></div>
	 } */
?>
<?php
	// deprecated - to use uncomment this section
	/*if (TEXT_INFORMATION) { ?>
		<div id="" class="content"><?php echo TEXT_INFORMATION; ?></div>
	} */
?>

<?php if (DEFINE_MAIN_PAGE_STATUS >= 1 and DEFINE_MAIN_PAGE_STATUS <= 2) { ?>
<?php
/**
 * get the Define Main Page Text
 */

 /* <div id="indexDefaultMainContent" class="content"><?php //require($define_page); ?></div> */
 } ?>

<?php

/*
----- Custom Content -----
1 = New Products 
2 = Featured Products 
3 = Special Products 
4 = Upcoming Products 
----- Custom Content -----
5 = Custom Content 1
6 = Custom Content 2 
7 = Custom Content 3 
8 = Custom Content 4 
*/

foreach ($homepage_content_order as $order_nr) {
	/* display the New Products Center Box */		 
	if(isset($order_nr) and $order_nr == 1) require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php');

	/* display the Featured Products Center Box */		 
	if(isset($order_nr) and $order_nr == 2) require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php');

	/* display the Special Products Center Box */		 
	if(isset($order_nr) and $order_nr == 3) require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php');

	/* display the Upcoming Products Center Box */		 
	if(isset($order_nr) and $order_nr == 4) include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS));

	/* display the Custom Content 1 Box */
	if(isset($order_nr) and $order_nr == 5) { echo '<div class="centerBoxWrapper" id="custom1Default">'; require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMEPAGE_CUSTOM_CONTENT_1, 'false')); echo "</div>"; }

	/* display the Custom Content 2 Box */
	if(isset($order_nr) and $order_nr == 6) { echo '<div class="centerBoxWrapper" id="custom2Default">'; require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMEPAGE_CUSTOM_CONTENT_2, 'false')); echo "</div>"; }           

	/* display the Custom Content 3 Box */
	if(isset($order_nr) and $order_nr == 7) { echo '<div class="centerBoxWrapper" id="custom3Default">'; require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMEPAGE_CUSTOM_CONTENT_3, 'false')); echo "</div>"; }          

	/* display the Custom Content 4 Box */
	if(isset($order_nr) and $order_nr == 8) { echo '<div class="centerBoxWrapper" id="custom4Default">'; require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMEPAGE_CUSTOM_CONTENT_4, 'false')); echo "</div>"; }          
}

?>
</div>