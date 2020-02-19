<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.8.10
 */

///////////////////////////////////////////////////////////////////////////////
///                                                                          //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


if (!defined('MH_REQUEST_TYPE')) {
    // set the type of request (secure or not)
    define('MH_REQUEST_TYPE', (getenv('HTTPS') == 'on' || ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != '') ) ? 'SSL' : 'NONSSL');
}


// test
function __mh_log_query()
{
    return false;
}

function mh_define($const, $value)
{
    if (!defined($const))
        define($const, $value);
}


if (defined('DIR_WS_HTTP_CATALOG')) {
    // oscmax, cre
    mh_define('MH_DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
}


if (substr(DIR_FS_CATALOG, -1) != '/') {
    mh_define('MH_DIR_FS_CATALOG', DIR_FS_CATALOG . '/');
} else {
    mh_define('MH_DIR_FS_CATALOG', DIR_FS_CATALOG);
}

if (substr(DIR_WS_CATALOG, -1) != '/') {
    mh_define('MH_DIR_WS_CATALOG', DIR_WS_CATALOG . '/');
} else {
    mh_define('MH_DIR_WS_CATALOG', DIR_WS_CATALOG);
}

if (isset($_GET['PROFILER'])) {
    define('DISPLAY_REQUEST_PROFILER', 'true');
}

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/request_profiler.php');

global $request_profiler;
$request_profiler = new request_profiler;
$request_profiler->start();


// include local configuration if available
$local_conf_dir = MH_DIR_FS_CATALOG . 'mailhive/common/local/';
if ($dir = @dir($local_conf_dir)) {
    while ($local_conf_file = $dir->read()) {
        if (!is_dir($local_conf_dir . $local_conf_file)) {
            if (preg_match('/\.php$/', $local_conf_file) > 0) {
                require_once($local_conf_dir . $local_conf_file);
            }
        }
    }
    $dir->close();
}

mh_define('FILENAME_MAILBEEZ_BLOCKGUI', 'mailhive/gui/mailhive_block_gui.php'); // can be overwritten by local config file
mh_define('FILENAME_MAILBEEZ_UNBLOCKGUI', 'mailhive/gui/mailhive_unblock_gui.php'); // can be overwritten by local config file
mh_define('FILENAME_MAILBEEZ_BLOCK_ALL_GUI', 'mailhive/gui/mailhive_block_all_gui.php'); // can be overwritten by local config file
mh_define('FILENAME_MAILBEEZ_UNBLOCK_ALL_GUI', 'mailhive/gui/mailhive_unblock_all_gui.php'); // can be overwritten by local config file

mh_define('MH_AUTOINSTALL', false);
mh_define('MH_SHOWMORELINK', true);

mh_define('MH_ADMIN_DIR_WS_PRE', '');
mh_define('MH_ADMIN_DIR_WS_IMAGES', MH_ADMIN_DIR_WS_PRE . MH_DIR_WS_CATALOG . 'mailhive/common/images/');

mh_define('DB_PREFIX', ''); //  zencart

mh_define('TABLE_MAILBEEZ_TRACKING', DB_PREFIX . 'mailbeez_tracking');
mh_define('TABLE_MAILBEEZ_BLOCK', DB_PREFIX . 'mailbeez_block');
mh_define('TABLE_MAILBEEZ_PROCESS', DB_PREFIX . 'mailbeez_process');
mh_define('TABLE_MAILBEEZ_BOUNCE', DB_PREFIX . 'mailbeez_bounces');
mh_define('TABLE_MAILBEEZ_BOUNCE_MSG_LOG', DB_PREFIX . 'mailbeez_bounces_msg_log');
mh_define('TABLE_MAILBEEZ_OPENS_LOG', DB_PREFIX . 'mailbeez_opens_log');
mh_define('TABLE_MAILBEEZ_TRACKING_CLICKS', DB_PREFIX . 'mailbeez_tracking_clicks');
mh_define('TABLE_MAILBEEZ_TRACKING_ORDERS', DB_PREFIX . 'mailbeez_tracking_orders');

mh_define('FILENAME_MAILBEEZ', 'mailbeez.php');
mh_define('FILENAME_HIVE', 'mailhive.php');

mh_define('MAILBEEZ_CHECK_CACHE', true);

$module_directory = MH_DIR_FS_CATALOG . 'mailhive/mailbeez/';
$module_directory_ws = MH_DIR_WS_CATALOG . 'mailhive/mailbeez/';
$config_module_directory = MH_DIR_FS_CATALOG . 'mailhive/configbeez/';
$config_module_directory_ws = MH_DIR_WS_CATALOG . 'mailhive/configbeez/';
$filter_module_directory = MH_DIR_FS_CATALOG . 'mailhive/filterbeez/';
$filter_module_directory_ws = MH_DIR_WS_CATALOG . 'mailhive/filterbeez/';
$report_module_directory = MH_DIR_FS_CATALOG . 'mailhive/reportbeez/';
$report_module_directory_ws = MH_DIR_WS_CATALOG . 'mailhive/reportbeez/';
$dashboard_module_directory = MH_DIR_FS_CATALOG . 'mailhive/dashboardbeez/';
$dashboard_module_directory_ws = MH_DIR_WS_CATALOG . 'mailhive/dashboardbeez/';

define('MH_DIR_MODULE', $module_directory);
define('MH_DIR_CONFIG', $config_module_directory);
define('MH_DIR_FILTER', $filter_module_directory);
define('MH_DIR_REPORT', $report_module_directory);
define('MH_DIR_DASHBOARD', $dashboard_module_directory);

$module_key = 'MAILBEEZ_INSTALLED';
$module_version_key = 'MAILBEEZ_INSTALLED_VERSIONS';
$config_module_key = 'MAILBEEZ_CONFIG_INSTALLED';
$config_module_version_key = 'MAILBEEZ_CONFIG_INSTALLED_VERSIONS';
$filter_module_key = 'MAILBEEZ_FILTER_INSTALLED';
$filter_module_version_key = 'MAILBEEZ_FILTER_INSTALLED_VERSIONS';
$report_module_key = 'MAILBEEZ_REPORT_INSTALLED';
$report_module_version_key = 'MAILBEEZ_REPORT_INSTALLED_VERSIONS';
$dashboard_module_key = 'MAILBEEZ_DASHBOARD_INSTALLED';
$dashboard_module_version_key = 'MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS';


// check if called in admin or storefront context

if (defined('DIR_WS_ADMIN')) {
    if (preg_match('#' . DIR_WS_ADMIN . '#', $_SERVER['PHP_SELF'])
        ||
        preg_match('#wp-admin#', $_SERVER['PHP_SELF'])
        // wp online store

    ) {
        mh_define('MH_CONTEXT', 'ADMIN');
    } else {
        mh_define('MH_CONTEXT', 'STORE');
    }
} else {
    mh_define('MH_CONTEXT', 'STORE');
}


// zencart, xtcommerce
mh_define('DIR_WS_HTTP_CATALOG', MH_DIR_WS_CATALOG);
mh_define('DIR_WS_HTTPS_CATALOG', MH_DIR_WS_CATALOG);


mh_define('HTTP_CATALOG_SERVER', HTTP_SERVER);
mh_define('HTTPS_CATALOG_SERVER', HTTPS_SERVER);
mh_define('PROJECT_VERSION', ''); // if not available

mh_define('MH_CATALOG_SERVER', (MH_REQUEST_TYPE == 'SSL') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);


/*
if (defined('ENABLE_SSL_ADMIN')) {
    // by default zencart
    mh_define('MH_CATALOG_SERVER', (ENABLE_SSL_ADMIN == 'true') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);
} elseif (MH_CONTEXT == 'ADMIN') {
    mh_define('MH_CATALOG_SERVER', (ENABLE_SSL == 'true') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);
} else {
    mh_define('MH_CATALOG_SERVER', ($_SERVER["HTTPS"] == "on") ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);
//    mh_define('MH_CATALOG_SERVER', HTTP_CATALOG_SERVER);
}
*/

if (function_exists('zen_redirect')) {
    define('MH_PLATFORM', 'zencart');
    // sorry zencart - didn't had the time to migrate everything to your DB-Class (might come later - its cool)
    // http://www.zen-cart.com/wiki/index.php/Developers_-_Porting_modules_from_osC
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/osc_database.php');
} elseif (function_exists('gm_get_conf')) {
    define('MH_PLATFORM', 'gambio');
    if (!function_exists('xtc_date_short')) {
        require_once(DIR_FS_INC . 'xtc_date_short.inc.php');
    }
    if (!function_exists('xtc_parse_input_field_data')) {
        require_once(DIR_FS_INC . 'xtc_parse_input_field_data.inc.php');
    }
    include_once(MH_DIR_FS_CATALOG . 'release_info.php');
    //echo $gx_version;
    define('MH_PLATFORM_GAMBIO', substr($gx_version, 1, 1));

} elseif (defined('PROJECT_VERSION_PLAIN')) {
    define('MH_PLATFORM', 'mercari');
    if (!function_exists('date_short')) {
        require_once(DIR_FS_INC . 'inc.date_short.php');
    }

    if (!function_exists('parse_input_field_data')) {
        require_once(DIR_FS_INC . 'inc.parse_input_field_data.php');
    }
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/osc_database.php');
    $db_link = tep_db_connect();
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/oscommerce/split_page_results.php');
    define('MH_PLATFORM_MERCARI', PROJECT_VERSION_TYPE . ' ' . PROJECT_VERSION_PLAIN);

    if (is_object($message_stack)) {
        $messageStack = $message_stack;
    }

} elseif (function_exists('xtc_redirect')) {
    define('MH_PLATFORM', 'xtc');
    if (!function_exists('xtc_date_short')) {
        require_once(DIR_FS_INC . 'xtc_date_short.inc.php');
    }
    if (!function_exists('xtc_parse_input_field_data')) {
        require_once(DIR_FS_INC . 'xtc_parse_input_field_data.inc.php');
    }

    // matches xtcModfied and modified
    define('MH_PLATFORM_XTCM', preg_match('/odified/', PROJECT_VERSION));
    define('MH_PLATFORM_XTC_SEO', preg_match('/commerce:SEO/', PROJECT_VERSION));
    define('MH_PLATFORM_XTC_ECB', preg_match('/eComBASE/', PROJECT_VERSION));
} elseif (defined('FILENAME_ADVANCED_MENU')) {
    define('MH_PLATFORM', 'digistore');
} elseif (preg_match('/CRE Loaded/', PROJECT_VERSION) || preg_match('/Loaded/', PROJECT_VERSION)) {
    // CRE Loaded PCI B2B
    define('MH_PLATFORM', 'creloaded');

    if (preg_match('/CRE Loaded PCI B2B/', PROJECT_VERSION) || preg_match('/Loaded Commerce B2B/', PROJECT_VERSION)) {
        define('MH_PLATFORM_CRE', 'B2B');
    } else {
        define('MH_PLATFORM_CRE', '');
    }

} else {
    define('MH_PLATFORM', 'oscommerce');

    if (function_exists('tep_get_version')) {
        define('MH_PLATFORM_OSC', (float)tep_get_version());
    } else {
        define('MH_PLATFORM_OSC', '2.2');
    }

    if (MH_PLATFORM_OSC > 2.2) {
        mh_define('MH_PLATFORM_OSC_23', true);
    }
    define('MH_PLATFORM_OSCMAX_25', preg_match('/osCmax v2.5/', PROJECT_VERSION));

    define('MH_PLATFORM_TRUELOADED', preg_match('/Trueloaded/', PROJECT_VERSION));

    // WP Online Store
    if (defined('WPOLS_PLUGINS_DIR')) {
        define('MH_PLATFORM_OSC_WPOS', PROJECT_VERSION);
        define('MH_FORM_METHOD', 'post');
        define('MH_PAGE_NAME', 'pages');

        $post = MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID;
        if (MH_CONTEXT == 'STORE') {
            $GLOBALS['post'] = & get_post($post);
        } else {
            if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {
                $_GET = $_REQUEST;
            }
        }
    }
}

mh_define('MH_SPLITPAGE_NUM', MAILBEEZ_ANALYTICS_SPLITPAGE_NUM);
mh_define('MH_PAGE_NAME', 'page');

mh_define('MH_PLATFORM_OSC', false);
mh_define('MH_PLATFORM_OSC_23', false);
mh_define('MH_PLATFORM_OSCMAX_25', false);
mh_define('MH_PLATFORM_TRUELOADED', false);
mh_define('MH_PLATFORM_GAMBIO', false);
mh_define('MH_PLATFORM_XTCM', false);
mh_define('MH_PLATFORM_XTC_SEO', false);
mh_define('MH_PLATFORM_XTC_ECB', false);

mh_define('MH_ID', MH_PLATFORM);
mh_define('MH_LINKID_1', '?a=' . MH_ID);
mh_define('MH_LINKID_2', '&a=' . MH_ID);

mh_define('MAILBEEZ_MAILHIVE_URL', MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . FILENAME_HIVE . '?' . MAILBEEZ_MAILHIVE_TOKEN . '=');
mh_define('MAILBEEZ_MAILHIVE_URL_DIRECT', MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . FILENAME_HIVE);
// adjustments need to be done in mailbeez_check.php / versioncheck.php as well

//define('MAILBEEZ_VERSION_CHECK_SERVER', 'http://127.0.0.1/wordpress_mailbeez');

mh_define('MAILBEEZ_VERSION_CHECK_SERVER', 'http://www.mailbeez.com');

// set based on configuration (email engine PHPMailer...)
mh_define('MH_BOUNCEHANDLING_ENABLED', defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True' && preg_match('/PHPMailer/', MAILBEEZ_CONFIG_EMAIL_ENGINE));
mh_define('MH_OPENTRACKER_ENABLED', MAILBEEZ_ANALYTICS_STATUS == 'True');
mh_define('MH_CLICKTRACKER_ENABLED', MAILBEEZ_ANALYTICS_STATUS == 'True');
mh_define('MH_ORDERTRACKER_ENABLED', MAILBEEZ_ANALYTICS_STATUS == 'True');


// Gambio
if (!defined('DIR_WS_THUMBNAIL_IMAGES') && defined('DIR_WS_CATALOG_THUMBNAIL_IMAGES')) {
    define('DIR_WS_THUMBNAIL_IMAGES', DIR_WS_CATALOG_THUMBNAIL_IMAGES);
}

switch ($_SESSION['language']) {
    case "german":
        $lng_param = 'de';
        break;
    default:
        $lng_param = 'en';
}

mh_define('MAILBEEZ_VERSION_CHECK_URL', MAILBEEZ_VERSION_CHECK_SERVER . '/downloads/version_check_v2/?v=' . MAILBEEZ_VERSION . '&m=' . (defined('MAILBEEZ_INSTALLED_VERSIONS')
        ? MAILBEEZ_INSTALLED_VERSIONS : '') . '&c=' . (defined('MAILBEEZ_CONFIG_INSTALLED_VERSIONS')
        ? MAILBEEZ_CONFIG_INSTALLED_VERSIONS : '') . '&f=' . (defined('MAILBEEZ_FILTER_INSTALLED_VERSIONS')
        ? MAILBEEZ_FILTER_INSTALLED_VERSIONS : '') . '&r=' . (defined('MAILBEEZ_REPORT_INSTALLED_VERSIONS')
        ? MAILBEEZ_REPORT_INSTALLED_VERSIONS : '') . '&d=' . (defined('MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS')
        ? MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS
        : '') . MH_LINKID_2 . '&lang=' . $lng_param . '&p=' . urlencode(MH_PLATFORM . ' - ' . PROJECT_VERSION));


mh_define('MAILBEEZ_CONTACT_EMAIL', 'support-' . MH_PLATFORM . '@mailbeez.com');

if (isset($PHP_SELF) && $PHP_SELF != '') {
    // oscommerce, zencart
    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
} else {
    // xtc
    $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
}

// override
$file_extension = '.php';

$file_extension = ($file_extension == '') ? '.php' : $file_extension;

mh_define('MH_FILE_EXTENSION', $file_extension);


$GLOBALS["file_extension"] = MH_FILE_EXTENSION;


if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/pro/pro.php')) {
    include_once(MH_DIR_FS_CATALOG . 'mailhive/pro/pro.php');
}

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/colorcodes.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/advanced_simulations.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/class_loader.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/email_engine.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/event_log.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/price.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/template_engine.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/update.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/versioncheck.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/systemcheck.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/logger.php');

// support for json on servers w/o json extension
if (!function_exists('json_encode')) {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/json_fallback.php');
}

// load common language resources
if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/common/languages/' . $_SESSION['language'] . MH_FILE_EXTENSION)) {
    include_once(MH_DIR_FS_CATALOG . 'mailhive/common/languages/' . $_SESSION['language'] . MH_FILE_EXTENSION);
} elseif (file_exists(MH_DIR_FS_CATALOG . 'mailhive/common/languages/english' . MH_FILE_EXTENSION)) {
    include_once(MH_DIR_FS_CATALOG . 'mailhive/common/languages/english' . MH_FILE_EXTENSION);
}

// include the list of functions plugins
$function_plugins_dir = MH_DIR_FS_CATALOG . 'mailhive/common/functions/function_plugins/';
if ($dir = @dir($function_plugins_dir)) {
    while ($function_plugins_file = $dir->read()) {
        if (!is_dir($function_plugins_dir . $function_plugins_file)) {
            if (preg_match('/\.php$/', $function_plugins_file) > 0) {
                require_once($function_plugins_dir . $function_plugins_file);
            }
        }
    }
    $dir->close();
}

function mh_reset_config_cache()
{
    $config_cache_detected = false;
    if (function_exists('tep_reset_config_cache_block')) {
        tep_reset_config_cache_block('includes/config-cache.php');
        $config_cache_detected = true;
    }

    if (function_exists('updateConfiguration')) {
        updateConfiguration();
        $config_cache_detected = true;
    }

    if (file_exists('includes/configuration_cache.php')) {
        global $osC_Cache, $config_cache_file;
        if (!function_exists('count_products_in_category')) {
            require_once('includes/configuration_cache.php');
        }
        $config_cache_detected = true;
    }

    return $config_cache_detected;
}


function mh_db_fetch_array()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_fetch_array', makeValuesReferenced($args));
            break;
        case 'zencart':
        case 'mercari':
            return call_user_func_array('tep_db_fetch_array', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            $args[0] = & $args[0];
            return call_user_func_array('xtc_db_fetch_array', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_query()
{
    $query_start_time = microtime(true);
    if (defined('DISPLAY_REQUEST_PROFILER') && (DISPLAY_REQUEST_PROFILER == 'true')) {
        $GLOBALS['START_TIME_DB_QUERY'][] = $query_start_time;
    }
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            $result = call_user_func_array('tep_db_query', makeValuesReferenced($args));
            break;
        case 'zencart':
        case 'mercari':
            $result = call_user_func_array('tep_db_query', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            $result = call_user_func_array('xtc_db_query', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
    $query_end_time = microtime(true);
    if (defined('DISPLAY_REQUEST_PROFILER') && (DISPLAY_REQUEST_PROFILER == 'true')) {
        $GLOBALS['END_TIME_DB_QUERY'][] = $query_end_time;
    }

    $query_time = $query_end_time - $query_start_time;

    mh_log_query($query_time, $args[0]);

    return $result;
}

function mh_db_perform()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_perform', makeValuesReferenced($args));
            break;
        case 'zencart':
        case 'mercari':
            return call_user_func_array('tep_db_perform', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_perform', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_num_rows()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_num_rows', makeValuesReferenced($args));
            break;
        case 'zencart':
        case 'mercari':
            return call_user_func_array('tep_db_num_rows', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_num_rows', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_insert_id()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_insert_id', makeValuesReferenced($args));
            break;
        case 'zencart':
        case 'mercari':
            return call_user_func_array('tep_db_insert_id', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_insert_id', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_prepare_input()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'mercari':
            if (defined('MH_PLATFORM_OSC_WPOS')) {
                $args[0] = stripslashes($args[0]);
            }

            return call_user_func_array('tep_db_prepare_input', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_db_prepare_input', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_prepare_input', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_input()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'mercari':
            return call_user_func_array('tep_db_input', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_db_input', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_input', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_output_string()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_output_string', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_output_string', makeValuesReferenced($args));
            break;
        case 'mercari':
            $args[1] = array('"' => '&quot;');
            return call_user_func_array('parse_input_field_data', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            $args[1] = array('"' => '&quot;');
            return call_user_func_array('xtc_parse_input_field_data', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_get_languages_directory($code)
{
    $language_query = mh_db_query("select languages_id, directory, name from " . TABLE_LANGUAGES . " where code = '" . mh_db_input($code) . "'");
    if (mh_db_num_rows($language_query)) {
        $language = mh_db_fetch_array($language_query);
        $languages_id = $language['languages_id'];
        return array("directory" => $language['directory'],
            "name" => $language['name'],
            "languages_id" => (int)$languages_id);
    } else {
        return false;
    }
}

function mh_get_languages()
{
    $languages_query = mh_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = mh_db_fetch_array($languages_query)) {
        $languages_array[] = array('id' => $languages['languages_id'],
            'name' => $languages['name'],
            'code' => $languages['code'],
            'image' => $languages['image'],
            'directory' => $languages['directory']);
    }

    return $languages_array;
}

function mh_get_customers_language_id()
{
    $args = func_get_args();
    if (function_exists('mh_lng_get_id')) {
        ob_start();
        $language_id = mh_lng_get_id($args[0], $args[1], $args[2]);
        ob_end_clean();
        return $language_id;
    } else {
        return $_SESSION['languages_id'];
    }
}

if (!function_exists('mh_href_link')) {
    function mh_href_link()
    {
        $args = func_get_args();
        switch (MH_PLATFORM) {
            case 'oscommerce':
            case 'creloaded':
            case 'digistore':
                // support for popups on WPOLS
                if (defined('MH_PLATFORM_OSC_WPOS')) {
                    if (stristr($args[1], 'popup=true')) {
                        $args[1] .= '&noheader=1';
                    }
                }
                return call_user_func_array('tep_href_link', makeValuesReferenced($args));
                break;
            case 'mercari':
                return call_user_func_array('href_link', makeValuesReferenced($args));
                break;
            case 'zencart':
                $args[3] = true;
                $args[4] = true;
                $args[5] = true; // set static link
                return call_user_func_array('zen_href_link', makeValuesReferenced($args));
                break;
            case 'xtc':
            case 'gambio':
                return call_user_func_array('xtc_href_link', makeValuesReferenced($args));
                break;
            default:
                echo 'platform not supported';
        }
    }
}


function mh_href_link_mailhive($param_string = '')
{
    // form urls in mailhive.php

    // todo
    // refactoring

    switch (MH_PLATFORM) {
        case 'xtc':
        case 'gambio':
            $link_out = mh_href_link(FILENAME_HIVE, $param_string, MH_REQUEST_TYPE, false, false);
            break;
        default:
            $get_param = '';

            if (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'STORE') {
                // wp online store
                if (substr($param_string, 0, 1) != '?') {
                    $get_param = '?';
                }
                return MAILBEEZ_MAILHIVE_URL_DIRECT . $get_param . tep_output_string($param_string);
            } else {
                return mh_href_email_link(FILENAME_HIVE, $get_param . $param_string, true);
            }
    }


    return $link_out;


}

function mh_href_link_plain($link, $param_string = '')
{
    // just an alias to keep things clean
    return mh_href_email_link($link, $param_string, true);
}

function mh_href_email_link($link, $param_string = '', $static = false)
{

    // output links
    switch (MH_PLATFORM) {
        case 'zencart':
            $link_out = zen_href_link($link, $param_string, MH_REQUEST_TYPE, false, true, $static);
            break;
        case 'xtc':
        case 'gambio':
            $link_out = mh_href_link($link, $param_string, MH_REQUEST_TYPE, false, false);
            break;
        default:
            $link_out = mh_href_link($link, $param_string, MH_REQUEST_TYPE, false);
            break;
    }
    return $link_out;
}

function mh_urlencode($url)
{
    // replace / with --
    //return urlencode($url);
    return $url;
}

function mh_urldecode($url)
{
    // replace -- with /
    //return urldecode($url);
    return $url;
}

function mh_draw_form()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_form', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('draw_form', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_form', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_form', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_hidden_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_hidden_field', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('draw_hidden_field', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_hidden_field', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_hidden_field', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_input_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_input_field', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('draw_input_field', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_input_field', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_input_field', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_draw_textarea_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
            if (MH_PLATFORM_OSCMAX_25) {
                return tep_draw_textarea_field($args[0], $args[2], $args[3], $args[4], $args[5], $args[6]);
                break;
            }
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_textarea_field', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('draw_textarea_field', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_textarea_field', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_textarea_field', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

if (!function_exists('mh_image_submit')) {
    function mh_image_submit()
    {
        $args = func_get_args();
        switch (MH_PLATFORM) {
            case 'oscommerce':
            case 'creloaded':
            case 'digistore':
                return call_user_func_array('tep_image_submit', makeValuesReferenced($args));
                break;
            case 'mercari':
                $button_text = 'Submit';
                if ($args[0] == 'button_update.gif')
                    $button_text = BUTTON_UPDATE;
                return '<input type="submit" class="button" onClick="this.blur();" value="' . $button_text . '"/>';
                break;
            /*

            if (!function_exists('image_submit')) {
                require_once(DIR_FS_INC . 'inc.image_submit.php');
            }
            return call_user_func_array('image_submit', makeValuesReferenced($args));
            break;
            */
            case 'zencart':
                return call_user_func_array('zen_image_submit', makeValuesReferenced($args));
                break;
            case 'xtc':
            case 'gambio':
                $button_text = 'Submit';
                if ($args[0] == 'button_update.gif')
                    $button_text = BUTTON_UPDATE;
                return '<input type="submit" class="button" onClick="this.blur();" value="' . $button_text . '"/>';
                break;
            default:
                echo 'platform not supported';
        }
    }
}

if (!function_exists('mh_image_button')) {
    function mh_image_button()
    {
        $args = func_get_args();
        switch (MH_PLATFORM) {
            case 'oscommerce':
            case 'creloaded':
            case 'digistore':
                return call_user_func_array('tep_image_button', makeValuesReferenced($args));
                break;
            case 'mercari':
                if (!function_exists('image_button')) {
                    require_once(DIR_FS_INC . 'inc.image_button.php');
                }
                return call_user_func_array('image_button', makeValuesReferenced($args));
                break;
            case 'zencart':
                return call_user_func_array('zen_image_button', makeValuesReferenced($args));
                break;
            case 'xtc':
            case 'gambio':
                if ($args[0] == 'button_module_install.gif')
                    return BUTTON_MODULE_INSTALL;
                if ($args[0] == 'button_module_remove.gif')
                    return BUTTON_MODULE_REMOVE;
                if ($args[0] == 'button_edit.gif')
                    return BUTTON_EDIT;
                if ($args[0] == 'button_cancel.gif')
                    return BUTTON_CANCEL;

                break;
            default:
                echo 'platform not supported';
        }
    }
}


function mh_review_button()
{
    //     global $language; // temporarily set to user language, set back before return
    // find the review-button
    switch (MH_PLATFORM) {
        case 'zencart':
            $review_button = zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT, 'border="0"');
            break;
        case 'creloaded':
            $review_button = tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS, 'align="middle" border="0"');
            break;
        default:
            $review_button = mh_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW, 'border="0"');
            break;
    }
    return mh_rewriteImgSrc($review_button, HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG);
}

function mh_redirect()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_redirect', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('redirect', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_redirect', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_redirect', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_call_function()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_call_function', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('call_function', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_call_function', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_call_function', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_not_null()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_not_null', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('not_null', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_not_null', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_not_null', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_separator()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_separator', makeValuesReferenced($args));
            break;
        case 'mercari':
            return;
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_separator', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_separator', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_class_exists()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_class_exists', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('class_exists', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_class_exists', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_class_exists', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_image()
{
    $args = func_get_args();

    switch (MH_PLATFORM) {
        case 'oscommerce':
            if (MH_PLATFORM_OSCMAX_25) {
                $args[0] = preg_replace('#' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . '#', '', $args[0]);
            }


            if (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'ADMIN') {
                // wp online store admin
                if (preg_match('#^' . MH_DIR_WS_CATALOG . '#', $args[0])) {
                    $args[0] = MH_CATALOG_SERVER . $args[0];
                }
            } elseif (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'STORE') {
                // wp online store store
                /*
                echo $args[0];
                $args[0] = preg_replace('#' . HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG . '#', '', $args[0]);
                echo $args[0];
                */
            }


            return call_user_func_array('tep_image', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('image', makeValuesReferenced($args));
            break;
        case 'creloaded':
            if (MH_CONTEXT == 'STORE') {
                $args[0] = preg_replace('#' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . '#', '', $args[0]);
            }
            return call_user_func_array('tep_image', makeValuesReferenced($args));
            break;
        case 'digistore':
            return call_user_func_array('tep_image', makeValuesReferenced($args));
            break;
        case 'zencart':
            if (MH_CONTEXT == 'STORE') {
                return call_user_func_array('zen_image_OLD', makeValuesReferenced($args));
            }
            return call_user_func_array('zen_image', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_image', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_product_image($products_array)
{
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'digistore':
        case 'creloaded':
        case 'zencart':
            $img_folder = DIR_WS_IMAGES;
            $img_folder .= (defined('DYNAMIC_MOPICS_THUMBS_DIR')) ? DYNAMIC_MOPICS_THUMBS_DIR : ''; // osCMax

            if (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'STORE') {
                // wp online store
                $img_folder = MH_CATALOG_SERVER . DIR_WS_IMAGES;
            }

            break;
        case 'xtc':
        case 'gambio':
        case 'mercari':
            // $img_folder = DIR_WS_INFO_IMAGES; // full size images
            $img_folder = DIR_WS_THUMBNAIL_IMAGES;
            break;
        default:
            echo 'platform not supported';
    }

    $image = mh_image($img_folder . $products_array['products_image'], $products_array['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="10" vspace="0" align="left" border="0"');

    if (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'STORE') {
        // wp online store store
        return $image;
    } else {
        return mh_rewriteImgSrc($image, HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG);
    }
}

function mh_rewriteImgSrc($input, $server)
{
    return preg_replace('#<img src="#', '<img src="' . $server, $input);
}

function mh_product_image_src($image)
{
    $image = str_replace(' ', '%20', $image); // handle blanks in filesnames
    $match_result = preg_match("#src=\"([a-zA-Z0-9%?&.;:/\(\)=+_-]*)\"#", $image, $match);
    return $match[1];
}


function mh_date_short()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_date_short', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('date_short', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_date_short', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_date_short', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_datetime_short()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':

            if (!function_exists('tep_datetime_short')) {
                function tep_datetime_short($raw_datetime)
                {
                    if (($raw_datetime == '0001-01-01 00:00:00') || ($raw_datetime == '')) return false;

                    $year = (int)substr($raw_datetime, 0, 4);
                    $month = (int)substr($raw_datetime, 5, 2);
                    $day = (int)substr($raw_datetime, 8, 2);
                    $hour = (int)substr($raw_datetime, 11, 2);
                    $minute = (int)substr($raw_datetime, 14, 2);
                    $second = (int)substr($raw_datetime, 17, 2);

                    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
                }
            }

            return call_user_func_array('tep_datetime_short', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('datetime_short', makeValuesReferenced($args));
            break;
        case 'zencart':

            if (!function_exists('zen_datetime_short')) {
                function zen_datetime_short($raw_datetime)
                {
                    if (($raw_datetime == '0001-01-01 00:00:00') || ($raw_datetime == '')) return false;

                    $year = (int)substr($raw_datetime, 0, 4);
                    $month = (int)substr($raw_datetime, 5, 2);
                    $day = (int)substr($raw_datetime, 8, 2);
                    $hour = (int)substr($raw_datetime, 11, 2);
                    $minute = (int)substr($raw_datetime, 14, 2);
                    $second = (int)substr($raw_datetime, 17, 2);

                    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
                }
            }

            return call_user_func_array('zen_datetime_short', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_datetime_short', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_cfg_select_option()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_cfg_select_option', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('cfg_select_option', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_cfg_select_option', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_cfg_select_option', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_cfg_select_multioption($select_array, $key_value, $key = '')
{
    // thanks to zencart
    $string = '';
    for ($i = 0; $i < sizeof($select_array); $i++) {

        if (!isset($select_array[$i]))
            continue; // in case of label entries the size is 2x

        $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
        $string .= '<br /><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
        $key_values = explode(", ", $key_value);
        if (in_array($select_array[$i], $key_values))
            $string .= ' CHECKED';
        $string .= ' id="' . strtolower($select_array[$i] . '-' . $name) . '"> ' . '<label for="' . strtolower($select_array[$i] . '-' . $name) . '" class="inputSelect">';
        $string .= ($select_array['label' . $i] != '') ? $select_array[$i] . ' - ' . $select_array['label' . $i]
            : $select_array[$i];

        $string .= '</label>' . "\n";
    }
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';
    return $string;
}

function mh_cfg_pull_down_order_statuses()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_cfg_pull_down_order_statuses', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('cfg_pull_down_order_statuses', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_cfg_pull_down_order_statuses', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_cfg_pull_down_order_statuses', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_order_status_name()
{
    if (MH_CONTEXT == 'STORE') return; // not defined in storefront

    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_order_status_name', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('get_order_status_name', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_get_order_status_name', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_order_status_name', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_get_order_status_name_multiple()
{
    $args = func_get_args();
    $list_of_order_status = $args[0];

    $array_of_order_status = explode(',', $list_of_order_status);

    while (list(, $value) = each($array_of_order_status)) {
        $args[0] = $value;
        $array_of_order_status_names[] = call_user_func_array('mh_get_order_status_name', makeValuesReferenced($args));
    }


    $list_of_order_status_names = implode(',', $array_of_order_status_names);

    return $list_of_order_status_names;
}

function mh_insert_config_value($config_array, $upate = false)
{
    $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $config_array['configuration_key'] . "'");
    if (mh_db_num_rows($check_query) > 0 && !$upate) {
        // config already exists, do not update
        return false;
    } elseif (mh_db_num_rows($check_query) > 0 && $upate) {
        // config already exists, perform update
        $result = mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . addslashes($config_array['configuration_value']) . "', last_modified = now() where configuration_key = '" . $config_array['configuration_key'] . "'");
        mh_reset_config_cache();
        return $result;
    }

    // no config value yet, insert:
    // otherwise insert config
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'zencart':
            return mh_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . addslashes($config_array['configuration_title']) . "', '" . $config_array['configuration_key'] . "', '" . addslashes($config_array['configuration_value']) . "', '" . addslashes($config_array['configuration_description']) . "', '6', '1', '" . addslashes($config_array['set_function']) . "', '" . addslashes($config_array['use_function']) . "', now())");
            break;
        case 'mercari':
            return mh_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . $config_array['configuration_key'] . "', '" . addslashes($config_array['configuration_value']) . "',  '6', '1', '" . addslashes($config_array['set_function']) . "', '" . addslashes($config_array['use_function']) . "', now())");
            break;
        case 'xtc':
        case 'gambio':
            return xtc_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . $config_array['configuration_key'] . "', '" . addslashes($config_array['configuration_value']) . "',  '6', '1', '" . addslashes($config_array['set_function']) . "', '" . addslashes($config_array['use_function']) . "', now())");
            break;
        default:
            echo 'platform not supported';
    }
}

function mb_admin_button($href, $text, $id = '', $mode = 'popup', $type = 'button', $parameters = '', $location = 'document', $ceebox = 'iframe width:650')
{
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'zencart':
        case 'xtc':
        case 'gambio':
        case 'mercari':
        default:
            if ($mode == 'popup') {

                if ($type == 'button') {
                    if (MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox') {
                        if (MH_PLATFORM_OSC_23) {
                            return tep_draw_button($text, 'document', $href, '', array('type' => 'submit', 'params' => $parameters . ' class="ceebox" rel="' . $ceebox . '"'));
                        } else {
                            return '<a id="' . $id . '" class="ceebox button_mailbeez" rel="' . $ceebox . '" ' . $parameters . ' href="' . $href . '" target="_blank"><input class="button mailbeez" type="Button" onclick="return false;" value="' . $text . '"></a>';
                        }
                    } else {
                        return '<a id="' . $id . '" style="border: 1px solid black; padding: 3px; margin: 5px; line-height: 25px; " ' . $parameters . ' href="' . $href . '" target="_blank">' . $text . '</a>';
                    }
                } elseif ($type == 'link') {
                    return '<a id="' . $id . '" ' . $parameters . '  class="ceebox " rel="' . $ceebox . ' "' . $parameters . ' href="' . $href . '" target="_blank">' . $text . '</a>';
                }
            } elseif ($mode == "link") {
                if ($type == 'button') {
                    if (MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox') {
                        if (MH_PLATFORM_OSC_23) {
                            return tep_draw_button($text, 'document', $href, '', array('type' => 'submit', 'params' => $parameters));
                        } else {
                            return '<a id="' . $id . '" class="button_mailbeez" ' . $parameters . ' href="' . $href . '"><input class="button mailbeez" type="Button" onclick="' . $location . '.location.href=\'' . $href . '\'; return false;" value="' . $text . '"></a>';
                        }
                    } else {
                        return '<a id="' . $id . '" style="border: 1px solid black; padding: 3px; margin: 5px; line-height: 25px; " href="' . $href . '" ' . $parameters . ' >' . $text . '</a>';
                    }
                } elseif ($type == 'link') {
                    return '<a id="' . $id . '" ' . $parameters . ' " href="' . $href . '">' . $text . '</a>';
                }
            }
    }
}

function mh_get_category_name()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_category_name', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('get_category_name', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_get_category_name', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_category_name', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_category_tree()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_category_tree', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('get_category_tree', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_get_category_tree', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_category_tree', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_products_name()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_products_name', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('get_products_name', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_get_products_name', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_products_name', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_all_get_params()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_all_get_params', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('get_all_get_params', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_get_all_get_params', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_all_get_params', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_draw_pull_down_menu()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_pull_down_menu', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('draw_pull_down_menu', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_pull_down_menu', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_pull_down_menu', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_hide_session_id()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_hide_session_id', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('hide_session_id', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_hide_session_id', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_hide_session_id', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_address_format()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_address_format', makeValuesReferenced($args));
            break;
        case 'mercari':
            return call_user_func_array('address_format', makeValuesReferenced($args));
            break;
        case 'zencart':
            return call_user_func_array('zen_address_format', makeValuesReferenced($args));
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_address_format', makeValuesReferenced($args));
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_tabs($filename, $tab)
{
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

if (!function_exists('sortbykeylength')) {

    function sortbykeylength($a, $b)
    {
        $alen = strlen($a);
        $blen = strlen($b);
        $r = '';
        if ($alen == $blen)
            $r = 0;
        if ($alen < $blen)
            $r = 1;
        if ($alen > $blen)
            $r = -1;
        return $r;
    }

}

function mh_add_magic_slashes($str)
{
    return get_magic_quotes_gpc() ? $str : addslashes($str);
}

function mh_strip_magic_slashes($str)
{
    return get_magic_quotes_gpc() ? stripslashes($str) : $str;
}


function mhpi()
{
    $args = func_get_args();
    if (function_exists('mh_pro_inc')) {
        return call_user_func_array('mh_pro_inc', makeValuesReferenced($args));
    }
    $id = array_shift($args); // remove args[0]
    return $args;
}

if (!function_exists('mhcc')) {
    function mhcc($c)
    {
        return $c;
    }
}

function mh_setDefaultMessage($messageStack)
{
    if (!mh_template_check_writeable()) {
        if (MH_PLATFORM != 'mercari')
            $messageStack->add('<b>mailhive/common/template_c</b> needs to be writeable (it is not!)', 'error');
        else {
            global $message_stack;
            $message_stack->add('<b>mailhive/common/template_c</b> needs to be writeable (it is not!)', 'error');
        }
    }
    if (defined('MH_PLATFORM_OSC_WPOS') && MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID == '') {
        $messageStack->add('<b>Please define the Page-ID of the Page where you inserted [WP_online_store]</b> <a href="' . mh_href_link(FILENAME_MAILBEEZ, 'module=config_wponlinestore&action=edit') . '">go to configuration</a>', 'error');
    }
}

function mh_session_close()
{
    if (PHP_VERSION >= '4.0.4') {
        return session_write_close();
    } elseif (function_exists('session_close')) {
        return session_close();
    }
}

function mh_exit()
{
    mh_session_close();
    exit();
}

// php 5.3 compatibility
// http://stackoverflow.com/questions/2045875/pass-by-reference-problem-with-php-5-3-1
function makeValuesReferenced(&$arr)
{
    $refs = array();
    foreach ($arr as $key => $value)
        $refs[$key] = & $arr[$key];
    return $refs;

}


function mh_language_documentation_url($url)
{
    // rewrite url to mailbeez.de
    switch ($_SESSION['language']) {
        case "german":
            $url = str_replace('mailbeez.com/documentation', 'mailbeez.de/dokumentation', $url);
            break;
    }
    return $url;
}

if (MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING != '') {
    mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET', MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING);
} elseif (isset ($_SESSION['language_charset'])) {
    mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET', $_SESSION['language_charset']);
} elseif (MH_PLATFORM == 'xtc') {
    $lang_query = "SELECT * FROM " . TABLE_LANGUAGES . " WHERE code = '" . DEFAULT_LANGUAGE . "'";
    $lang_query = mh_db_query($lang_query);
    $lang_data = mh_db_fetch_array($lang_query);
    mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET', $lang_data['language_charset']);
} elseif (defined('CHARSET')) {
    mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET', CHARSET);
} else {
    // Fallback
    mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET', 'UTF-8');
}

mh_define('CHARSET', MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET);


mh_define('MH_CIS_URL', mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=config_customer_insight/admin_application_plugins/context_view.php&popup=true'));

//if (MH_CONTEXT == 'STORE') {
$GLOBALS['mh_template_replace_variables_common'] = array(
    'catalog_server' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG,
    'catalog_server_ssl' => HTTPS_SERVER . MH_DIR_WS_CATALOG,
    'root_catalog' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG,
    'storename' => STORE_NAME,
    'storeurl' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG,
    'store_url' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG,
    'page_contact_us' => defined('FILENAME_CONTACT_US') ? mh_href_email_link(FILENAME_CONTACT_US) : 'not defined - modify email_html.tpl template',
    'page_customer_support' => defined('FILENAME_CONTACT_US') ? mh_href_email_link(FILENAME_CONTACT_US) : 'not defined - modify email_html.tpl template',
    'page_my_account' => defined('FILENAME_ACCOUNT') ? mh_href_email_link(FILENAME_ACCOUNT) : 'not defined - modify email_html.tpl template',
    'page_password' => defined('FILENAME_ACCOUNT_PASSWORD') ? mh_href_email_link(FILENAME_ACCOUNT_PASSWORD) : 'not defined - modify email_html.tpl template',
    'blank_img' => HTTP_CATALOG_SERVER . MH_ADMIN_DIR_WS_IMAGES . 'blank.gif',
    'themes_root' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG . 'mailhive/common/templates',
    'modules_root' => HTTP_CATALOG_SERVER . MH_DIR_WS_CATALOG . 'mailhive/mailbeez/',
    'MAILBEEZ_TRACKER_PIX' => (MH_OPENTRACKER_ENABLED) ? '<img src="' . MAILBEEZ_MAILHIVE_URL_DIRECT . '?mi=%message-id%" width="1" height="1">' : '',
    'MAILBEEZ_TEMPLATE_MANAGER_INSTALLED' => ((defined('MAILBEEZ_CONFIG_TMPLMNGR_STATUS') && MAILBEEZ_CONFIG_TMPLMNGR_STATUS == 'True') or (defined('MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS') && MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS == 'True')) ? true : false,
    'MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET' => MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET,
    'MAILBEEZ_COMMON_CSS' => '',
    'MH_CIS_URL' => MH_CIS_URL
);
//if (defined('MH_PLATFORM_OSC_WPOS') && MH_CONTEXT == 'STORE') {
if (defined('MH_PLATFORM_OSC_WPOS')) {
    $GLOBALS['mh_template_replace_variables_common']['storeurl'] = mh_href_email_link('index.php');
}
//}


?>