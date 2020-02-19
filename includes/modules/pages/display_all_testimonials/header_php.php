<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
  * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v1.5.2 4-16-2010 Clyde Jones $
 */
 
  require(DIR_WS_MODULES . 'require_languages.php');
  $breadcrumb->add(NAVBAR_TITLE);
  
  $testimonials_query_raw = "select * from " . TABLE_TESTIMONIALS_MANAGER . " where status = 1 and language_id = '" . (int)$_SESSION['languages_id'] . "' order by date_added DESC, testimonials_title";

  $testimonials_split = new splitPageResults($testimonials_query_raw, MAX_DISPLAY_TESTIMONIALS_MANAGER_ALL_TESTIMONIALS);
//EOF 