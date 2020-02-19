<?php
/**
 * Common Template
 *
 * outputs the html header. i,e, everything that comes before the \</head\> tag <br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Tue Jul 17 16:02:00 2012 -0400 Modified in v1.5.1 $
 */
/**
 * load the module for generating page meta-tags
 */
require(DIR_WS_MODULES . zen_get_module_directory('meta_tags.php'));
/**
 * output main page HEAD tag and related headers/meta-tags, etc
 */
 if(is_file($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php')){
    require_once($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo META_TAG_TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="keywords" content="<?php echo META_TAG_KEYWORDS; ?>" />
<meta name="description" content="<?php echo META_TAG_DESCRIPTION; ?>" />
<meta http-equiv="imagetoolbar" content="no" />


<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<?php if (defined('ROBOTS_PAGES_TO_SKIP') && in_array($current_page_base,explode(",",constant('ROBOTS_PAGES_TO_SKIP'))) || $current_page_base=='down_for_maintenance' || $robotsNoIndex === true) { ?>
<meta name="robots" content="noindex, nofollow" />
<?php } ?>
<?php if (defined('FAVICON')) { ?>
<link rel="icon" href="<?php echo FAVICON; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo FAVICON; ?>" type="image/x-icon" />
<?php } //endif FAVICON ?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_SERVER . DIR_WS_CATALOG ); ?>" />
<?php if (isset($canonicalLink) && $canonicalLink != '') { ?>
<link rel="canonical" href="<?php echo $canonicalLink; ?>" />
<?php } ?>

<?php
/**
* load the loader files
*/

if($RI_CJLoader->get('status') && (!isset($Ajax) || !$Ajax->status())){
	$RI_CJLoader->autoloadLoaders();
	$RI_CJLoader->loadCssJsFiles();
	$files = $RI_CJLoader->header();	
	
	foreach($files['css'] as $file)
		if($file['include']) {
      include($file['src']);
    } else if (!$RI_CJLoader->get('minify_css') || $file['external']) {
      echo '<link rel="stylesheet" type="text/css" href="'.$file['src'].'" />'."\n";
    } else {
      echo '<link rel="stylesheet" type="text/css" href="min/?f='.$file['src'].'&amp;'.$RI_CJLoader->get('minify_time').'" />'."\n";
    }
		
	foreach($files['jscript'] as $file)
		if($file['include']) {
      include($file['src']);
    } else if(!$RI_CJLoader->get('minify_js') || $file['external']) {
      echo '<script type="text/javascript" src="'.$file['src'].'"></script>'."\n";
    } else {
      echo '<script type="text/javascript" src="min/?f='.$file['src'].'&amp;'.$RI_CJLoader->get('minify_time').'"></script>'."\n";
    }
}

/**
 * load all site-wide jscript_*.php files from includes/templates/YOURTEMPLATE/jscript, alphabetically
 */
  $directory_array = $template->get_template_part($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'jscript'), '/^jscript_/', '.php');
  while(list ($key, $value) = each($directory_array)) {
/**
 * include content from all site-wide jscript_*.php files from includes/templates/YOURTEMPLATE/jscript, alphabetically.
 * These .PHP files can be manipulated by PHP when they're called, and are copied in-full to the browser page
 */
    require($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/' . $value); echo "\n";
  }
/**
 * include content from all page-specific jscript_*.php files from includes/modules/pages/PAGENAME, alphabetically.
 */
  $directory_array = $template->get_template_part($page_directory, '/^jscript_/');
  while(list ($key, $value) = each($directory_array)) {
/**
 * include content from all page-specific jscript_*.php files from includes/modules/pages/PAGENAME, alphabetically.
 * These .PHP files can be manipulated by PHP when they're called, and are copied in-full to the browser page
 */
    require($page_directory . '/' . $value); echo "\n";
  }

// DEBUG: echo '<!-- I SEE cat: ' . $current_category_id . ' || vs cpath: ' . $cPath . ' || page: ' . $current_page . ' || template: ' . $current_template . ' || main = ' . ($this_is_home_page ? 'YES' : 'NO') . ' -->';

if (COLUMN_WIDTH == '0') {
    echo '';
} else {
    echo '<link rel="stylesheet" type="text/css" href="' . $template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css') . '/' . 'responsive.css' . '" />';
}
?>

<script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/css_browser_selector.js' ?>" type="text/javascript"></script>


</head>
<?php // NOTE: Blank line following is intended: ?>

