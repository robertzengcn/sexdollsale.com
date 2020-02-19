<?php

// define('STRICT_ERROR_REPORTING', true); // zencart
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set('display_errors', 1);


if (!defined('MAILBEEZ_MAILHIVE_STATUS')) {
    if (!class_exists('Smarty')) {
        // first run - take default settings.
        define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH', 'Smarty_2.6.26');
    }
}


require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');


//if (!defined('MAILBEEZ_MAILHIVE_STATUS') || (int)MAILBEEZ_VERSION < 2) {
if (!mh_template_check_writeable()) {
    echo MAILBEEZ_INSTALL_WARNING_TEMPLATE_C;
    exit();
}
//}


// include the modules language translations
// load language file for modules.php
if (MH_PLATFORM == 'zencart') {
    $current_page = basename(FILENAME_MODULES . '.php');
    if (file_exists(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $current_page)) {
        include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $current_page);
    }
} elseif (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {
    $current_page = basename(FILENAME_MODULES);
    if (file_exists(DIR_FS_LANGUAGES . $_SESSION['language'] . '/admin/' . $current_page)) {
        include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/admin/' . $current_page);
    }
} elseif (defined('MH_PLATFORM_OSC_WPOS')) {
    // WP online store
    if (file_exists(MH_DIR_FS_CATALOG . DIR_WS_INCLUDES . DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES)) {
        include(MH_DIR_FS_CATALOG . DIR_WS_INCLUDES . DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES);
    }

} else {
    if (file_exists(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES)) {
        include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES);
    }
}

$request_profiler->restart('top_load_languages');

$set = (isset($_GET['set']) ? $_GET['set'] : '');

$module_type = '';


$trustpilot_evaluate = (isset($trustpilot_evaluate)) ? $trustpilot_evaluate
    : 'http://www.trustpilot.com/evaluate/www.mailbeez.com';

$action = (isset($_GET['action']) ? $_GET['action'] : '');

$custom_app_include = '';
$config_cache_detected = false;
$config_cache_refreshed = false;
$config_updated = false;
$default_action = '';

if (file_exists(MH_DIR_CONFIG . 'config_queen.php')) {
    if (!mh_class_exists('config_queen')) {
        include_once(MH_DIR_CONFIG . 'config_queen.php');
        $config = new config_queen();
    }
} else {
    echo "fatal error: can't find config_queen.php";
}

if (file_exists(MH_DIR_CONFIG . 'config_template_selector.php')) {
    include_once(MH_DIR_CONFIG . 'config_template_selector.php');
}


if (mh_not_null($action)) {
    $config_cache_detected = mh_reset_config_cache();

    if ($config_cache_detected == true) {
        $default_action .= '&action=update_cache';
    }

    switch ($action) {
        case 'save':
            while (list($key, $value) = each($_POST['configuration'])) {
                if (is_array($value)) {
                    $value = implode(", ", $value);
                    $value = preg_replace("/, --none--/", "", $value);
                }
                //mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
                mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . mh_add_magic_slashes($value) . "' where configuration_key = '" . $key . "'");
            }


            $class = basename($_GET['module']);
            if (file_exists(MH_DIR_MODULE . $class . $file_extension)) {
                include_once(MH_DIR_MODULE . $class . $file_extension);
            } elseif (file_exists(MH_DIR_CONFIG . $class . $file_extension)) {
                include_once(MH_DIR_CONFIG . $class . $file_extension);
            } elseif (file_exists(MH_DIR_FILTER . $class . $file_extension)) {
                include_once(MH_DIR_FILTER . $class . $file_extension);
            } elseif (file_exists(MH_DIR_REPORT . $class . $file_extension)) {
                include_once(MH_DIR_REPORT . $class . $file_extension);
            } elseif (file_exists(MH_DIR_DASHBOARD . $class . $file_extension)) {
                include_once(MH_DIR_DASHBOARD . $class . $file_extension);
            }

            if (mh_class_exists($class)) {
                $module = new $class;
                if ($module->on_cfg_save_clear_template_c == true) {
                    mh_smarty_clear_compile_dir();
                }
                $module->onSave();
            }

            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'] . $default_action));
            break;
        case 'install':
        case 'remove':
            if (isset($PHP_SELF) && $PHP_SELF != '') {
                // oscommerce, zencart
                $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
            } else {
                // xtc
                $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
            }

            $class = basename($_GET['module']);
            if (file_exists(MH_DIR_MODULE . $class . $file_extension)) {
                include_once(MH_DIR_MODULE . $class . $file_extension);
            } elseif (file_exists(MH_DIR_CONFIG . $class . $file_extension)) {
                include_once(MH_DIR_CONFIG . $class . $file_extension);
            } elseif (file_exists(MH_DIR_FILTER . $class . $file_extension)) {
                include_once(MH_DIR_FILTER . $class . $file_extension);
            } elseif (file_exists(MH_DIR_REPORT . $class . $file_extension)) {
                include_once(MH_DIR_REPORT . $class . $file_extension);
            } elseif (file_exists(MH_DIR_DASHBOARD . $class . $file_extension)) {
                include_once(MH_DIR_DASHBOARD . $class . $file_extension);
            }

            if (mh_class_exists($class)) {
                $module = new $class;
                if ($action == 'install') {
                    $module->install();
                } elseif ($action == 'remove') {
                    $module->remove();
                }
            }
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $class . $default_action));
            break;
        case 'update_cache':
            // reload to make updated cache visible
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'] . '&action=config_cache_refreshed'));
            break;
        case 'config_cache_refreshed':
            // show message
            $config_cache_refreshed = true;
            break;
        case 'config_update_ok':
            // show message
            $config_updated = true;
            break;
    }
}

$request_profiler->restart('top_after_actions');

$tab = defined('MAILBEEZ_CONFIG_DASHBOARD_START') ? MAILBEEZ_CONFIG_DASHBOARD_START : 'home';

// tab controll
if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
} elseif (preg_match('/^config/', $_GET['module'])) {
    $tab = 'configbeez';
} elseif (preg_match('/^filter/', $_GET['module'])) {
    $tab = 'filterbeez';
} elseif (preg_match('/^report/', $_GET['module'])) {
    $tab = 'reportbeez';
} elseif (preg_match('/^dashboard/', $_GET['module'])) {
    $tab = 'dashboardbeez';
} elseif (isset($_GET['module'])) {
    $tab = 'mailbeez';
}
// set modul paths
switch ($tab) {
    case 'mailbeez':
        $module_key_current = $module_key;
        $module_version_key_current = $module_version_key;
        $module_directory_current = MH_DIR_MODULE;
        $module_directory_current_ws = $module_directory_ws;
        break;
    case 'configbeez':
        $module_key_current = $config_module_key;
        $module_version_key_current = $config_module_version_key;
        $module_directory_current = MH_DIR_CONFIG;
        $module_directory_current_ws = $config_module_directory_ws;
        break;
    case 'filterbeez':
        $module_key_current = $filter_module_key;
        $module_version_key_current = $filter_module_version_key;
        $module_directory_current = MH_DIR_FILTER;
        $module_directory_current_ws = $filter_module_directory_ws;
        break;
    case 'reportbeez':
        $module_key_current = $report_module_key;
        $module_version_key_current = $report_module_version_key;
        $module_directory_current = MH_DIR_REPORT;
        $module_directory_current_ws = $report_module_directory_ws;
        break;
    case 'dashboardbeez':
    case 'home':
        $module_key_current = $dashboard_module_key;
        $module_version_key_current = $dashboard_module_version_key;
        $module_directory_current = MH_DIR_DASHBOARD;
        $module_directory_current_ws = $dashboard_module_directory_ws;
        break;
}

define('MH_DIR_CURRENT', $module_directory_current);

$app_include = (isset($_GET['app']) ? $_GET['app'] : '');

if ($app_include == 'load_app') {
    $custom_app_include = (isset($_GET['app_path'])) ? $_GET['app_path'] : '';
    $class = basename($_GET['module']);

    if (file_exists($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension)) {
        // try to load language file
        include_once($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension);
    } elseif (file_exists($module_directory_current . $class . '/languages/english' . $file_extension)) {
        // .. or english file as default if available
        include_once($module_directory_current . $class . '/languages/english' . $file_extension);
    } else {
        // no language file found!
    }
}

$request_profiler->restart('top_after_load_apps');


if (!function_exists('sortbyintvalue')) {
    function sortbyintvalue($a, $b)
    {
        $aint = (int)$a;
        $bint = (int)$b;

        //echo "$aint $bint<br>";

        if ($aint == $bint)
            $r = 0;
        if ($aint < $bint)
            $r = -1;
        if ($aint > $bint)
            $r = 1;
        return $r;
    }
}

if (MAILBEEZ_MAILHIVE_MODE == 'simulate') {
    $messageStack->reset();
    $messageStack->add(WARNING_SIMULATE, 'warning');
    mh_setDefaultMessage($messageStack);
}

if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
    $messageStack->reset();
    //$messageStack->add(WARNING_OFFLINE, 'warning');
    mh_setDefaultMessage($messageStack);
}
if ($config_cache_refreshed == true) {
    $messageStack->add('config cache refreshed', 'success');
    mh_setDefaultMessage($messageStack);
}

if ($config_updated == true) {
    $messageStack->add('MailBeez updated!', 'success');
    mh_setDefaultMessage($messageStack);
}

mh_update_reminder_timestamp();

$request_profiler->restart('top_before_smarty');


$smarty_admin = new Smarty;
$smarty_admin->caching = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING;
$smarty_admin->template_dir = MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/templates/'; // root dir to templates
$smarty_admin->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
$smarty_admin->config_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR;
$smarty_admin->compile_check = true;
$smarty_admin->compile_id = 'admin_main' . $_SESSION['language'];

$ADMIN_TEMPLATE_TOP = '';
if (MH_PLATFORM_OSC_23) {
    ob_start();
    require(DIR_WS_INCLUDES . 'template_top.php');
    $ADMIN_TEMPLATE_TOP = ob_get_contents();
    ob_end_clean();
}

ob_start();
require(DIR_WS_INCLUDES . 'header.php');
$ADMIN_HEADER = ob_get_contents();
ob_end_clean();


if (MH_PLATFORM != 'zencart' && MH_PLATFORM != 'digistore' && !MH_PLATFORM_OSC_23 && !MH_PLATFORM_XTC_ECB) {
    // no column left
    ob_start();
    require(DIR_WS_INCLUDES . 'column_left.php');
    $ADMIN_COLUMN_LEFT = ob_get_contents();
    ob_end_clean();
}

$smarty_admin->assign(array('trustpilot_evaluate' => $trustpilot_evaluate));
$smarty_admin->assign(array('MH_CATALOG_URL' => MH_CATALOG_SERVER . MH_DIR_WS_CATALOG,
    'MH_ADMIN_DIR_WS_IMAGES' => MH_ADMIN_DIR_WS_IMAGES,
    'MH_RATE_TRUSTPILOT_LINK' => MH_RATE_TRUSTPILOT_LINK));
$smarty_admin->assign(array('SHOW_HBND' => ($_SESSION['language'] == 'german') ? true : false));
$smarty_admin->assign(array('SHOW_HBND_EN' => ($_SESSION['language'] != 'german') ? true : false));
$MAILBEEZ_FOOTER = $smarty_admin->fetch('main_footer.tpl'); // smarty template


ob_start();
if (!defined('MAILBEEZ_MAILHIVE_STATUS')) {
    // install screen
    $MAILBEEZ_TABS = '';
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/install.php');
} else {
    // start of admin screen main area
    // $_GET['module']
    if ($custom_app_include != '') {
        // load custom application
        switch ($_GET['module']) {
            case (preg_match('/^config/', $_GET['module']) > 0):
                require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/' . $custom_app_include);
                break;
            case (preg_match('/^filter/', $_GET['module']) > 0):
                require_once(MH_DIR_FS_CATALOG . 'mailhive/filterbeez/' . $custom_app_include);
                break;
            case (preg_match('/^report/', $_GET['module']) > 0):
                require_once(MH_DIR_FS_CATALOG . 'mailhive/reportbeez/' . $custom_app_include);
                break;
            default:
                require_once(MH_DIR_FS_CATALOG . 'mailhive/mailbeez/' . $custom_app_include);
                break;
        }
    } else {
        switch ($tab) {
            case 'mailbeez':
            case 'configbeez':
            case 'filterbeez':
            case 'reportbeez':
                $MAILBEEZ_TABS = mh_tabs(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez.php');
                break;
            case 'dashboardbeez':
                $MAILBEEZ_TABS = mh_tabs(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/back_dashboardbeez.php', $tab);
                require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez.php');
                break;

            case 'about':
                $MAILBEEZ_TABS = mh_tabs(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez_' . $tab . '.php');
                break;
            default:
                $MAILBEEZ_TABS = mh_tabs(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez_' . $tab . '.php');
                break;
        }
    }
}

$MAILBEEZ_MAIN_CONTENT = ob_get_contents();
ob_end_clean();

if (defined('MAILBEEZ_MAILHIVE_STATUS')) {
   if (!MH_PLATFORM_OSC_23) {

        ob_start();
        if ($app_include != '') {
            define('MAILBEEZ_CUSTOMER_INSIGHT_LOADED', true);
        }

        require(DIR_WS_INCLUDES . 'footer.php');
        $ADMIN_FOOTER = ob_get_contents();
        ob_end_clean();
    }

    ob_start();
    if ($app_include == '') {
// autoload customer insight if available but not installed
        if (!defined('MAILBEEZ_CUSTOMER_INSIGHT_LOADED')) {
            define('MAILBEEZ_CUSTOMER_INSIGHT_NOT_INSTALLED', true);
            if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_customer_insight/includes/admin_footer_include.php')) {
                require(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_customer_insight/includes/admin_footer_include.php');
            }
        }
    }
}

$ADMIN_FOOTER_INSIGHT_CODE = ob_get_contents();
ob_end_clean();


$ADMIN_TEMPLATE_BOTTOM = '';
if (MH_PLATFORM_OSC_23) {
    ob_start();
    require(DIR_WS_INCLUDES . 'template_bottom.php');
    $ADMIN_TEMPLATE_BOTTOM = ob_get_contents();
    ob_end_clean();
    $ADMIN_TEMPLATE_BOTTOM .= $ADMIN_FOOTER_INSIGHT_CODE;
} else {
    $ADMIN_FOOTER .= $ADMIN_FOOTER_INSIGHT_CODE;
}

ob_start();
require(DIR_WS_INCLUDES . 'application_bottom.php');
$ADMIN_APPLICATION_BOTTOM = ob_get_contents();
ob_end_clean();


$smarty_admin->assign(array('MAILBEEZ_MAILHIVE_POPUP_MODE' => MAILBEEZ_MAILHIVE_POPUP_MODE,
    'MAILBEEZ_MAILHIVE_STATUS' => MAILBEEZ_MAILHIVE_STATUS,
    'HTML_PARAMS' => HTML_PARAMS,
    'TITLE' => TITLE,
    'HEADING_TITLE' => MH_HEADING_TITLE,
    'MAILBEEZ_VERSION' => MAILBEEZ_VERSION_DISPLAY,
    'MAILBEEZ_VERSION_CHECK_URL' => MAILBEEZ_VERSION_CHECK_URL,
    'MAILBEEZ_MAILHIVE_MODE' => MAILBEEZ_MAILHIVE_MODE,
    'MAILBEEZ_MAILHIVE_MODE_TEXT' => (MAILBEEZ_MAILHIVE_MODE == 'simulate')
            ? MAILBEEZ_MODE_SET_SIMULATE_TEXT : MAILBEEZ_MODE_SET_PRODUCTION_TEXT,
    'MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT' => MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT,
    'MAILBEEZ_MAILHIVE_MODE_SWITCH_URL' => mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../common/admin_application_plugins/toggle_mode.php'),
    'MAILBEEZ_CIS_URL' => MH_CIS_URL,
    'MAILBEEZ_MAILHIVE_MODE_SIM_RESTART' => mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_simulation/admin_application_plugins/simulation_restart.php&app_action=restart&popup=true'), MAILBEEZ_MAILHIVE_MODE_SIM_RESTART_BUTTON, '', 'popup', 'link', '', 'document', 'iframe width:300 height:200')
));


$MAILBEEZ_UPDATE_REMINDER = false;
if (MAILBEEZ_MAILHIVE_UPDATE_REMINDER == 'True' && MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP < time()) {
    // fire updatereminder
    $MAILBEEZ_UPDATE_REMINDER = true;
}

$smarty_admin->assign(array('MAILBEEZ_FOOTER' => $MAILBEEZ_FOOTER));

$smarty_admin->assign(array('MAILBEEZ_MAIN_CONTENT' => $MAILBEEZ_MAIN_CONTENT,
        'ADMIN_TEMPLATE_TOP' => $ADMIN_TEMPLATE_TOP,
        'ADMIN_TEMPLATE_BOTTOM' => $ADMIN_TEMPLATE_BOTTOM,
        'MAILBEEZ_VERSION_CHECK_BUTTON' => mb_admin_button(MAILBEEZ_VERSION_CHECK_URL, MH_BUTTON_VERSION_CHECK, 'mbUpd'),
        'ADMIN_BOX_WIDTH' => BOX_WIDTH,
        'ADMIN_HEADER' => $ADMIN_HEADER,
        'ADMIN_COLUMN_LEFT' => $ADMIN_COLUMN_LEFT,
        'ADMIN_PAGE_HEADING_SEPARATOR' => mh_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT),
        'ADMIN_APPLICATION_BOTTOM' => $ADMIN_APPLICATION_BOTTOM,
        'ADMIN_FOOTER' => $ADMIN_FOOTER,
        'GAMBIO_SCREEN' => $_SESSION['screen_width'],
        'SESSION_CHARSET' => (isset($_SESSION['language_charset']) ? $_SESSION['language_charset']
                : CHARSET),
        'GAMBIO_COUNTER_ACTION_LINK' => mh_href_link('gm_counter_action.php'),
        'MH_PLATFORM_XTCMODIFIED' => MH_PLATFORM_XTCM)
);

$admin_template = 'main_osc.tpl';
$admin_template_popup = 'popup.tpl';
mh_define('MH_BYPASS_TEMPLATE', false); // might have been set in admin_application_plugins
mh_define('MH_POPUP', false); // might have been set in admin_application_plugins

switch (MH_PLATFORM) {
    case 'oscommerce':
        if (MH_PLATFORM_OSC_23) {
            $admin_template = 'main_osc23.tpl';
        }
        if (MH_PLATFORM_OSCMAX_25) {
            $admin_template = 'main_oscmax25.tpl';
        }
        if (MH_PLATFORM_TRUELOADED) {
            $admin_template = 'main_trueloaded.tpl';
        }
        break;
    case 'creloaded':
        if (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) {
            $admin_template = 'main_creloaded_62.tpl';
        } else {
            $admin_template = 'main_creloaded.tpl';
        }
        break;
    case 'digistore':
        $admin_template = 'main_digistore.tpl';
        break;
    case 'zencart':
        $admin_template = 'main_zc.tpl';
        break;
    case 'mercari':
        $admin_template = 'main_mercari.tpl';
        ob_start();
        require(DIR_WS_INCLUDES . 'metatag.php');
        $MERCARI_METATAG = ob_get_contents();
        ob_end_clean();
        $smarty_admin->assign(array('MERCARI_METATAG' => $MERCARI_METATAG));
        break;
    case 'xtc':
        // if (MH_PLATFORM_XTCM) {}
        $admin_template = 'main_xtc.tpl';

        if (MH_PLATFORM_XTC_SEO) {
            $admin_template = 'main_xtc_seo.tpl';

            ob_start();
            require(DIR_WS_INCLUDES . 'metatag.php');
            $CSEO_METATAG = ob_get_contents();
            ob_end_clean();
            $smarty_admin->assign(array('CSEO_METATAG' => $CSEO_METATAG));
        }

        if (MH_PLATFORM_XTC_ECB) {
            $admin_template = 'main_xtc_ecb.tpl';
        }
        break;
    case 'gambio':
        if (MH_PLATFORM_GAMBIO == 1) {
            $admin_template = 'main_gambiogx.tpl';
        } elseif (MH_PLATFORM_GAMBIO == 2) {
            $admin_template = 'main_gambiogx2.tpl';
        }
        break;
    default:
}

if (MH_BYPASS_TEMPLATE) {
    $admin_template = 'main_direct.tpl';
}

if (MH_POPUP) {
    if (defined('MH_PLATFORM_OSC_WPOS')) {
        $smarty_admin->assign(array('MH_ADMIN_CSS_PATH' => site_url() . '/wp-content/plugins/' . WPOLS_PLUGINS_DIR . '/admin/'));
    } else {
        $smarty_admin->assign(array('MH_ADMIN_CSS_PATH' => ''));
    }
    echo $smarty_admin->fetch($admin_template_popup); // smarty template
} else {
    $smarty_admin->assign(array('MH_ADMIN_CSS_PATH' => ''));
    echo $smarty_admin->fetch($admin_template); // smarty template
}


if (isset($request_profiler)) {
    $request_profiler->stop('bottom_end');
}

if (DISPLAY_REQUEST_PROFILER == 'true') {
    if (isset($request_profiler)) {
        $request_profiler->output();
    }
}
// end of admin screen main area

// WPOLS popup screen support
if (defined('MH_PLATFORM_OSC_WPOS') && isset($_GET['noheader'])) {
    mh_exit();
}
?>