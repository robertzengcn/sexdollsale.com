<?php
/**
 * Module Template - categories_tabs
 *
 * Template stub used to display categories-tabs output
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_categories_tabs.php 3395 2006-04-08 21:13:00Z ajeh $
 */

 /* loading the UL-generator class and produce the menu list dynamically from there */
 require_once (DIR_WS_CLASSES . CLASS_CATEGORIES_UL_GENERATOR);
 $zen_ul_category = new zen_categories_ul_generator;
 $mainMenu = $zen_ul_category->buildTree(true);

if (CATEGORIES_TABS_STATUS == '1' && sizeof($mainMenu) >= 1) { ?>
<div class="navigation">	
	<div class="container">		
		<nav class="nav">			
	    	<a class="mobileNavBtn" href="#"><span>Menu</span></a>						
			<?php echo $mainMenu; ?>			
		</nav>		
	</div>	
</div>
<?php } ?>