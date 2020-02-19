<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2013 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.7

  event log functions
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

define('MH_LOG_QUERY_TIME', 0.01); // query time limit in sec

if (defined('MAILBEEZ_LOGGER') && MAILBEEZ_LOGGER ) {
    if (MAILBEEZ_LOGGER_SYSTEM == 'firephp') {
        require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/FirePHPCore/fb.php');
    }
    if (MAILBEEZ_LOGGER_SYSTEM == 'firelogger' || !defined('MAILBEEZ_LOGGER_SYSTEM')) {
        require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/firelogger.php');
    }
}


function mh_log($logmsg, $logval)
{

    if (!defined('MAILBEEZ_LOGGER') || !MAILBEEZ_LOGGER) {
        return false;
    }

    if (MAILBEEZ_LOGGER_SYSTEM == 'firephp') {
        fb($logval, $logmsg);
    }

    if (MAILBEEZ_LOGGER_SYSTEM == 'firelogger' || !defined('MAILBEEZ_LOGGER_SYSTEM')) {
        flog($logmsg, $logval);

        /*
        http://firelogger-php-tests.binaryage.com/basic.php

        flog("Hello from PHP!");

        flog("Expansion %s", 1);
        flog("Expansion %s", "string");
        flog("Expansion %s", array(1,2,3,4,5));
        flog("Expansion %s", array("a" => 1, "b" => 2, "c" => 3));

        flog("Unbound args", 1, 2, 3, 4, 5);
        flog(1, "no formating string");

        flog("debug", "DEBUG log level");
        flog("info", "INFO log level");
        flog("error", "ERROR log level");
        flog("warning", "LOG level");
        flog("critical", "CRITICAL log level");

        flog("Complex structure %o", $_SERVER);
        flog("Complex object structure %o", (object) array('item1' => new ArrayObject($_SERVER), 'item2' => new SplFileInfo(__FILE__)));
        flog("Global scope!", $GLOBALS);


         */

        /*
            http://inchoo.net/ecommerce/firelogger-a-sexy-server-logger-console-in-firebug/

        Also, if you notice, the PHP library uses ob_start() to capture the content so it can send the headers before flushing it… won’t work if your app uses ob_start() by itself, and there’s a hack required to disable this and run the encoding handler manually..

        $_SERVER['HTTP_X_FIRELOGGER'] = null;
        define('FIRELOGGER_NO_OUTPUT_HANDLER', 1);
        define('FIRELOGGER_NO_CONFLICT', 1);
        include_once "firelogger.php";
        FireLogger::$enabled = true;


         *
         *
         */
    }
}


function mh_log_query($query_time, $sql)
{
    if (!defined('MAILBEEZ_LOGGER') || !MAILBEEZ_LOGGER) {
        return false;
    }

    if ($query_time > MH_LOG_QUERY_TIME) {
        if (MAILBEEZ_LOGGER_SYSTEM == 'firephp') {
            mh_log("query: $query_time %s", array('QUERY_TIME' => $query_time, 'SQL' => $sql));
        }
        if (MAILBEEZ_LOGGER_SYSTEM == 'firelogger' || !defined('MAILBEEZ_LOGGER_SYSTEM')) {
            fwarn("query: $query_time %s", array('QUERY_TIME' => $query_time, 'SQL' => $sql));
        }
    }
}


?>