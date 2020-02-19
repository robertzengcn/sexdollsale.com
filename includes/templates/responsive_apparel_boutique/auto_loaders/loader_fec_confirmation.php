<?php
/**
* @package Pages
* @copyright Copyright 2008-2009 RubikIntegration.com
* @copyright Copyright 2003-2006 Zen Cart Development Team
* @copyright Portions Copyright 2003 osCommerce
* @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
* @version $Id: link.php 149 2009-03-04 05:23:35Z yellow1912 $
*/                                             
                                                            
$loaders[] = array('conditions' => array('pages' => array(FILENAME_FEC_CONFIRMATION)),
										'jscript_files' => array(
										  'jquery/jquery-1.10.2.min.js' => 1,
										  'jquery/jquery-migrate-1.2.1.min.js' => 2,
                      'jquery/jquery_fec_confirmation.php' => 3										
                    ),
                    'css_files' => array(
                      'fec_global.css' => 1,
                      'fec_confirmation.css' => 2,
                      'auto_loaders/fec_confirmation_overrides.css' => 3
                    )
								);  