<?php
/**
* @package Pages
* @copyright Copyright 2008-2009 RubikIntegration.com
* @copyright Copyright 2003-2006 Zen Cart Development Team
* @copyright Portions Copyright 2003 osCommerce
* @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
* @version $Id: link.php 149 2009-03-04 05:23:35Z yellow1912 $
*/                                             
                                                            
$loaders[] = array('conditions' => array('pages' => array(FILENAME_CREATE_ACCOUNT, FILENAME_LOGIN, FILENAME_NO_ACCOUNT)),
										'jscript_files' => array(
										  'jquery/jquery-1.10.2.min.js' => 1,
										  'jquery/jquery-migrate-1.2.1.min.js' => 2,
                      'jquery/jquery_create_account.js' => 3,
                      'jquery/jquery_addr_pulldowns.php' => 4,
                      'jquery/jquery_form_check.php' => 4										
                    ),
                    'css_files' => array(
                      'fec_global.css' => 1
                    )
								);  