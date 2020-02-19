<?php

/** This file is part of KCFinder project
 *
 * @desc Base configuration file
 * @package KCFinder
 * @version 2.52-dev
 * @author Pavel Tzonkov <pavelc@users.sourceforge.net>
 * @copyright 2010, 2011 KCFinder Project
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
 * @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
 * @link http://kcfinder.sunhater.com
 */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

/// adopted for MailBeez

$conf_file = '../../../common/templates_c/kcfinder_conf.php';;


if (isset($conf_file)) {
//    unset($_SESSION);
    $fingerprint = '';
    if (file_exists($conf_file)) {
        require($conf_file);
        $_SESSION['mh_fingerprint'] = $fingerprint;

//        if (!isset($_SESSION['KCFINDER']) && is_array($mh_conf)) {
        if ( is_array($mh_conf) ) {
            $_SESSION['KCFINDER'] = $mh_conf;
        }
    }
}

$_CONFIG = array(


// GENERAL SETTINGS

//    'disabled' => false,
    'disabled' => (isset($_SESSION['KCFINDER']['disabled'])) ? false : true,
    'theme' => "oxygen",
    'uploadURL' => "/upload",
    'uploadDir' => "",

    'types' => array(

        // (F)CKEditor types
        'files' => "",
        'flash' => "swf",
        'images' => "*img",

        // TinyMCE types
        'file' => "",
        'media' => "swf flv avi mpg mpeg qt mov wmv asf rm",
        'image' => "*img",
    ),


// IMAGE SETTINGS

    'imageDriversPriority' => "imagick gmagick gd",
    'jpegQuality' => 90,
    'thumbsDir' => ".thumbs",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 100,
    'thumbHeight' => 100,

    'watermark' => "",


// DISABLE / ENABLE SETTINGS

    'denyZipDownload' => false,
    'denyUpdateCheck' => false,
    'denyExtensionRename' => false,


// PERMISSION SETTINGS

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",


// MISC SETTINGS

    'filenameChangeChars' => array( /*
        ' ' => "_",
        ':' => "."
    */),

    'dirnameChangeChars' => array( /*
        ' ' => "_",
        ':' => "."
    */),

    'mime_magic' => "",

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',


// THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION SETTINGS

    '_check4htaccess' => false,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);
if ($_SESSION['mh_fingerprint'] != $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']) {
//    die("dead: " . print_r($_REQUEST, true) . print_r($_SESSION, true));
    die();
}


?>