<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  rewrite emails to add tracking code


 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

if (defined('MH_DIR_FS_CATALOG')) {
    // in case of open tracking
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_analytics.php');
}

define('MAILBEEZ_CLICKTRACKER_TIMESTAMP_FOLDER', 'mailhive/common/templates_c/');
define('MAILBEEZ_CLICKTRACKER_TIMESTAMP_FILE', 'clicktracker_timestamp.txt');
define('MAILBEEZ_CLICKTRACKER_LOG_FOLDER', 'mailhive/common/templates_c/');
define('MAILBEEZ_CLICKTRACKER_LOG_FILE', 'mailbeeztracker.txt');
define('MAILBEEZ_CLICKTRACKER_CYCLE', 30); // sec

class mb_clicktracker
{

// class constructor
    function mb_clicktracker()
    {

    }

    function process()
    {
        if (MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO == 'True') {
            if ($this->doRun(MH_DIR_FS_CATALOG)) {
                $this->import_log_file(true);
                $this->consolidate_coupon_data(true);
                $this->updateTimeStamp(MH_DIR_FS_CATALOG);
            }
        }
    }

    function rewriteContent($input, $type = 'html')
    {
        if (MAILBEEZ_ANALYTICS_STATUS == 'False') {
            return $input;
        }

        $this->code = 'mid=%message-id%';
        $rewrite_mode = 'shop';
        $rewritten = $input;

        switch ($rewrite_mode) {
            /*
            case 'all':
                // rewrite all links
                $rewritten = preg_replace("#href=\"(([a-zA-Z]+://)([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"$1" . "&" . $this->code . "\"", $rewritten);
                $rewritten = preg_replace("#href=\"(([a-zA-Z]+://)([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"$1" . "?" . $this->code . "\"", $rewritten);
                break;
            */
            case 'shop':
                // rewrite only internal links
                // html links
                if ($type == 'html') {
                    $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "&" . $this->code . "\"", $rewritten);
                    $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "?" . $this->code . "\"", $rewritten);

                    if (defined('HTTPS_SERVER') && HTTPS_SERVER != '') {
                        $rewritten = preg_replace("#href=\"" . HTTPS_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "&" . $this->code . "\"", $rewritten);
                        $rewritten = preg_replace("#href=\"" . HTTPS_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "?" . $this->code . "\"", $rewritten);
                    }


                } elseif ($type == 'txt' && MAILBEEZ_ANALYTICS_REWRITE_FORMAT == 'True') {
                    // txt links
                    $rewritten = preg_replace("#" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\s#", "" . HTTP_SERVER . "$1" . "&" . $this->code . "", $rewritten);
                    $rewritten = preg_replace("#" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\s#", "" . HTTP_SERVER . "$1" . "?" . $this->code . "", $rewritten);
                    if (defined('HTTPS_SERVER') && HTTPS_SERVER != '') {

                        $rewritten = preg_replace("#" . HTTPS_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\s#", "" . HTTP_SERVER . "$1" . "&" . $this->code . "", $rewritten);
                        $rewritten = preg_replace("#" . HTTPS_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\s#", "" . HTTP_SERVER . "$1" . "?" . $this->code . "", $rewritten);
                    }
                }


                //fix rewritten urls
                $rewritten = str_replace('/&', '/?', $rewritten);

                break;
        }
        return $rewritten;
    }

    function record_open($message_id, $store_basedir)
    {
        if ($message_id != '%message-id%') {
            $logfile_path = $store_basedir . MAILBEEZ_CLICKTRACKER_LOG_FOLDER . MAILBEEZ_CLICKTRACKER_LOG_FILE;
            if (($fp = fopen($logfile_path, 'ab')) !== FALSE) {
                if (flock($fp, LOCK_EX) === TRUE) {
                    fwrite($fp, $message_id . "|" . time() . "|" . $_SERVER['HTTP_USER_AGENT'] . "\n");
                    flock($fp, LOCK_UN);
                }
                fclose($fp);
            }
        }

        header('Content-type: image/png');
        header("Content-length: 87");
        echo gzinflate(base64_decode('6wzwc+flkuJiYGDg9fRwCQLSjCDMwQQkJ5QH3wNSbCVBfsEMYJC3jH0ikOLxdHEMqZiTnJCQAOSxMDB+E7cIBcl7uvq5rHNKaAIA'));
    }

    function updateTimeStamp($store_basedir)
    {
        $timestamp_file_path = $store_basedir . MAILBEEZ_CLICKTRACKER_TIMESTAMP_FOLDER . MAILBEEZ_CLICKTRACKER_TIMESTAMP_FILE;

        if ($fp = @fopen($timestamp_file_path, 'w')) {
            flock($fp, 2); // LOCK_EX
            fputs($fp, 'clicktracker timestamp');
            flock($fp, 3); // LOCK_UN
            fclose($fp);
        }
    }

    function doRun($store_basedir)
    {
        $timestamp_file_path = $store_basedir . MAILBEEZ_CLICKTRACKER_TIMESTAMP_FOLDER . MAILBEEZ_CLICKTRACKER_TIMESTAMP_FILE;

        $timedif = @(time() - filemtime($timestamp_file_path));

        if ($timedif < (MAILBEEZ_CLICKTRACKER_CYCLE)) {
            return false; // do not run
        } else {
            return true; // do run
        }
    }


    function record_click($message_id, $url)
    {
        if (MAILBEEZ_ANALYTICS_STATUS == 'False') {
            return false;
        }

        $_SESSION['mailbeez_message_id'] = $message_id;
        // testing
        /*
        echo "<h3>MailBeez Click-Tracker</h3>";
        echo "Message-Id: ";
        echo $_GET['mid'];
        */

        // record click
        $sql_data_array = array('message_id' => $message_id,
            'url' => $url,
            'date_record' => 'now()');
        mh_db_perform(TABLE_MAILBEEZ_TRACKING_CLICKS, $sql_data_array);

        // mark message as opened
        // mark message as clicked
        $sql_data_array = array('opened' => 'now()',
            'clicked' => 'now()');
        mh_db_perform(TABLE_MAILBEEZ_TRACKING, $sql_data_array, 'update', 'message_id="' . $message_id . '"');
    }


    function record_order()
    {
        if (MAILBEEZ_ANALYTICS_STATUS == 'False') {
            return false;
        }

        // write to session
        // track in mailbeez
        // track when order is placed
        // get order id, customers id

        $message_id = $_SESSION['mailbeez_message_id'];

        $mailbeez_orders_query = mh_db_query("select orders_id, orders_status
                                  from " . TABLE_ORDERS . "
                                  where customers_id = '" . $_SESSION['customer_id'] . "'
                                  order by orders_id desc limit 1");
        $mailbeez_orders = mh_db_fetch_array($mailbeez_orders_query);
        $mailbeez_last_order = $mailbeez_orders['orders_id'];
        //$mailbeez_order_status = $mailbeez_orders['orders_status'];

        mb_clicktracker::write_record_order($message_id, $mailbeez_last_order, $_SESSION['customer_id']);
    }


    function write_record_order($message_id, $orders_id, $customers_id)
    {

        if (isset($_SESSION['mailbeez_tracking_orders_id']) && $_SESSION['mailbeez_tracking_orders_id'] == $orders_id) {
            return true;
        }

        $_SESSION['mailbeez_tracking_orders_id'] = $orders_id;

        $sql_data_array = array('message_id' => $message_id,
            'orders_id' => $orders_id,
            'customers_id' => $customers_id,
            'date_record' => 'now()');
        mh_db_perform(TABLE_MAILBEEZ_TRACKING_ORDERS, $sql_data_array);

        $query_sql = "select date_purchased
                                from " . TABLE_ORDERS . "
                             where orders_id = '" . $orders_id . "'  ";
        $query = mh_db_query($query_sql);
        $result = mh_db_fetch_array($query);

        $sql_data_array = array('ordered' => $result['date_purchased']);
        mh_db_perform(TABLE_MAILBEEZ_TRACKING, $sql_data_array, 'update', 'message_id="' . $message_id . '"');
    }

    function consolidate_coupon_data($silent = false)
    {
        if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php')) {
            require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php');
            $cfg_ce = new config_coupon_engine();
            if ($cfg_ce->version > 2.2 && $cfg_ce->enabled) {
                couponbeez::consolidate_order_data($silent);
            }
        }
    }


    function import_log_file($silent = false)
    {
        $logfile = MH_DIR_FS_CATALOG . 'mailhive/common/templates_c/mailbeeztracker.txt';

        if (file_exists($logfile)) {
            $logfile_process = $logfile . '_' . time();
            rename($logfile, $logfile_process);

            $fp = fopen($logfile_process, "r");
            if ($fp) {
                while (!feof($fp)) {
                    $line = fgets($fp);
                    if (substr_count($line, '|') < 2) {
                        continue;
                    }
                    list($message_id, $timestamp, $user_agent) = explode("|", $line);

                    if ($message_id != '') {
                        if (!$silent) {
                            echo "processed: $message_id , $timestamp<br />";
                        }
                        // mark message as opened
                        $sql_data_array = array('opened' => date("Y-m-d H:i:s", $timestamp),
                            'mobile' => mailbeez_analytics::is_mobile_useragent($user_agent));

                        mh_db_perform(TABLE_MAILBEEZ_TRACKING, $sql_data_array, 'update', 'message_id="' . $message_id . '"');

                        $sql_data_array = array('message_id' => $message_id,
                            'date' => date("Y-m-d H:i:s", $timestamp),
                            'user_agent' => $user_agent);
                        mh_db_perform(TABLE_MAILBEEZ_OPENS_LOG, $sql_data_array);
                    }
                }
            }

            fclose($fp);
            // http://stringoftheseus.com/blog/2010/12/22/php-unlink-permisssion-denied-error-on-windows/
            // fix unlink issue on windows server
            chmod($logfile_process, 0776);
            unlink($logfile_process);
        } else {
            //  echo "no open tracking file";
        }
    }
}

?>