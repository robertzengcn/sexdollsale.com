<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.7.0
 */


///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_editor_basic.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_template_codes_basic.php');


class mailbeez_mailer
{
    var $subject, $ga;
    var $filter;
    var $filter_loaded;
    var $mailbeez_editor;
    var $mailbeez_codes;

    function mailbeez_mailer($mailbeeObj, $initiate_editor = false)
    {
        $this->mailBee = $mailbeeObj; // current mailBee to work on
        $this->copySentCounter = 0;

        if ($initiate_editor) {
            // for previewing
            $this->mailbeez_editor = new mailbeez_editor();
            $this->mailbeez_editor->_register_editor_codes();
            $this->mailbeez_codes = new mailbeez_template_codes();
        }
    }

    function sendBeez($mailbeez_module_path, $_iteration = 1, $mode = 'production')
    {

        if (!is_object($this->mailbeez_editor)) {
            $this->mailbeez_editor = new mailbeez_editor();
            $this->mailbeez_editor->_register_editor_codes();
            $this->mailbeez_codes = new mailbeez_template_codes();
        }

        $doCheck = true;
        if ($mode == 'test') {
            $doCheck = false;
        }
        $row = 0;
        $row_out = 0;

        $mailbeez_module = $this->get_module_name($mailbeez_module_path);

        // changed handling of iteration -> can be overwritten by resultset
        $iteration = $_iteration;

        $this->load_filter();

        while (list($key, $mail) = each($this->mailBee->audience)) {
            $row_out++;
            $order_id = 'none';
            if (isset($mail['order_id'])) {
                $order_id = $mail['order_id'];
            }

            list($mail) = @mhpi('mailbeez_mailer_1', $mail, $this->mailBee);

            // set dynamic iteration if exists
            $iteration = isset($mail['_iteration']) ? $mail['_iteration'] : $iteration;

            if ($doCheck == true) {
                $check_result = $this->late_check($mailbeez_module, $iteration, $mail['customers_id'], $order_id, $mail, $mode, false);

                if ($check_result != false) {
                    // result is an array
                    list($check_result, $date_sent, $_result_iteration, $date_block, $filter_block, $valid_block, $filter_result, $bounce_block, $check_subscr) = $check_result;
                }
            }

            if ($filter_result['STOP'] == 'true') {
                echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_FILTER_STOP;
                echo $filter_block;
                echo '</div>';
                $this->mailBee->update_process_lock();
                return $row;
            }

            if ($doCheck == true && $check_result == true) {
                // was already sent
                if ($date_sent != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_ALREADY_SEND;
                    echo $date_sent . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($date_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_BLOCKED;
                    echo $date_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($filter_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_FILTER_BLOCKED;
                    echo $filter_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($valid_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_VALID_BLOCKED;
                    echo $valid_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($bounce_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_BOUNCE_BLOCKED;
                    echo $bounce_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($check_subscr != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_SUBSCRIPTION_BLOCKED;
                    echo $bounce_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                }
                echo '</div>';
            } else {
                // send
                $row++;
                // $replace_variables = $mail;


                $smarty = new Smarty;
                $smarty->caching = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING;
                $smarty->template_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEMPLATE_DIR; // root dir to templates
                $smarty->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
                $smarty->config_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR;
                $smarty->compile_check = true;
                $smarty->compile_id = $this->mailBee->get_module_id();

                // some magic
                list($smarty, ,) = @mhpi('mailbeez_mailer_2', $smarty, $this, $mail);

                $mail = $this->mailBee->beforeFilter($mail, $mode);

                // add data
                $mail = $this->mailBee->beforeFilterData($mail, $mode);
                list($mail, $filter_result) = $this->process_data_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $mail, $mode);
                $mail = $this->mailBee->afterFilterData($mail, $mode);

                // insert content
                $mail = $this->mailBee->beforeFilterContent($mail, $mode);
                list($mail, $filter_result) = $this->process_content_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $mail, $mode);
                $mail = $this->mailBee->afterFilterContent($mail, $mode);

                // generate Email
                $mail = $this->mailBee->beforeGenerate($mail, $mode);
                list($output_subject, $output_content_html, $output_content_txt) = mh_smarty_generate_mail($this, $mail, $mailbeez_module_path, $smarty);
                $mail = $this->mailBee->afterGenerateMail($mail, $mode);

                // modifiy output
                $mail = $this->mailBee->beforeFilterModify($mail, $mode);
                list($output_subject, $output_content_html, $output_content_txt) = $this->process_modifier_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $output_subject, $output_content_html, $output_content_txt, $mode, $this, $mail);
                $mail = $this->mailBee->afterFilterModify($mail, $mode);

                $mail = $this->mailBee->afterFilter($mail, $mode);

                if (MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL == 'True') {
                    echo "<hr noshade size='1'>";
                    echo $output_content_html;
                }
                //exit();
                // do things before sending

                if ((int)$this->mailBee->required_mb_version < 2 && (MAILBEEZ_MAILHIVE_MODE == 'production')) {
                    $this->mailBee->beforeSend($mail, $mode);
                } elseif ((int)$this->mailBee->required_mb_version >= 2) {
                    $mail = $this->mailBee->beforeSend($mail, $mode);
                }

                if (MAILBEEZ_SIMULATION == 'False' || $mode == 'test') {
                    // send "real" email to customer
                    $mail_meta = mh_sendEmail($mail, $mail['email_address'], $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                } else {
                    // send simulation
                    $output_subject = MAILBEEZ_SIMULATION_TAG . $output_subject;
                    $mail_meta = mh_sendEmail($mail, MAILBEEZ_CONFIG_SIMULATION_EMAIL, $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                }

                $mail['message_id'] = $mail_meta['message_id'];


                // do things after sending
                if ((int)$this->mailBee->required_mb_version < 2 && (MAILBEEZ_MAILHIVE_MODE == 'production')) {
                    $this->mailBee->afterSend($mail, $mode);
                } elseif ((int)$this->mailBee->required_mb_version >= 2) {
                    $this->mailBee->afterSend($mail, $mode);
                }

                list($mail, $filter_result) = $this->process_afterSend_filter($this->mailBee, $iteration, $mail, $mode);

                if (($doCheck && (MAILBEEZ_SIMULATION == 'False')) ||
                    ($doCheck && (MAILBEEZ_SIMULATION == 'True' && MAILBEEZ_CONFIG_SIMULATION_TRACKING == 'True'))
                ) {
                    $this->track($mailbeez_module, $iteration, $mail);
                    $this->mailBee->afterTrack($mail, $mode);
                }

                if ((MAILBEEZ_MAILHIVE_COPY == 'True' && MAILBEEZ_SIMULATION == 'False') ||
                    (MAILBEEZ_CONFIG_SIMULATION_COPY == 'True' && MAILBEEZ_MAILHIVE_COPY == 'True' && MAILBEEZ_SIMULATION == 'True')
                ) {
                    // send copy
                    if ($this->copySentCounter < MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT) {
                        mh_sendEmail($mail, MAILBEEZ_MAILHIVE_EMAIL_COPY, $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                        $this->copySentCounter++;
                    }
                }

                echo '<div class="s"><a name="1">&nbsp;</a>' . $row . ' ' . ((MAILBEEZ_SIMULATION == 'False') ? ''
                        : MAILBEEZ_SIMULATION_TAG) . TEXT_EMAIL_SEND;
                echo $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                echo '</div>';
            }

            $this->mailBee->update_process_lock();

            if (($row_out % 100) == 0) {
                echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">  scrolldown(); </SCRIPT>';
                echo str_repeat(" ", 4096); // force a flush
                ob_flush();
                flush();
            }
        }
        return $row;
    }

    function track($mailbeez_module, $iteration = 1, $mail)
    {
        // TABLE_MAILBEEZ_TRACKING
        $channel = (isset($mail['channel'])) ? $mail['channel'] : 'DEFAULT';
        $customers_id = $mail['customers_id'];
        $customers_email = $mail['email_address'];
        $order_id = isset($mail['order_id']) ? $mail['order_id'] : 0;
        $message_id = $mail['message_id'];

        $sql_data_array = array('module' => $mailbeez_module,
            'date_sent' => 'now()',
            'iteration' => (int)$iteration,
            'customers_id' => $customers_id,
            'customers_email' => $customers_email,
            'orders_id' => $order_id,
            'channel' => $channel,
            'simulation' => MAILBEEZ_SIMULATION_ID,
            'batch_id' => MAILBBEEZ_EVENTLOG_BATCH_ID,
            'message_id' => $message_id
        );

        list($sql_data_array,) = @mhpi('mailbeez_mailer_3', $sql_data_array, $mail);


        mh_db_perform(TABLE_MAILBEEZ_TRACKING, $sql_data_array);
    }

    function block($mailbeez_module, $customers_id, $email_address, $source = 0, $docheck = true, $mode = 'prod', $behaviour = 'Global')
    {
        // TABLE_MAILBEEZ_BLOCK
        // block module for customer
        // check if combination of customer_id and email_adress is valid

        $check_result = true;
        if ($docheck) {
            $check_result = $this->check_track($mailbeez_module, false, $customers_id);
        }

        if ($behaviour == 'Global') {
            // nicetohavetodo
            // implement global block
            // currently check_block works depending on configuration setting
            // and
        }

        if ($check_result) {
            // this customer has received an email
            if (!$this->check_block($mailbeez_module, $customers_id)) {
                // not yet blocked
                $sql_data_array = array('module' => $mailbeez_module,
                    'date_block' => 'now()',
                    'customers_id' => $customers_id,
                    'source' => $source,
                    'simulation' => ($mode == 'prod') ? 0 : $mode);

                mh_db_perform(TABLE_MAILBEEZ_BLOCK, $sql_data_array);
                return 'ok';
            }
            return '-1';
        }
        return 'failed';
    }

    function unblock($mailbeez_module, $customers_id, $email_address, $source = 0, $docheck = false, $mode, $behaviour)
    {
        // TABLE_MAILBEEZ_BLOCK
        // block module for customer
        // check if combination of customer_id and email_adress is valid
        $check_result = true;
        if ($docheck) {
            $check_result = $this->check_track($mailbeez_module, false, $customers_id);
        }

        if ($behaviour == 'Global') {
            // nicetohavetodo
            // implement global unblock
            // currently check_block works depending on configuration setting
            // and
        }

        if ($check_result) {
            // this customer has received an email
            if ($this->check_block($mailbeez_module, $customers_id)) {
                mh_db_query("delete from " . TABLE_MAILBEEZ_BLOCK . "
                             where module = '" . $mailbeez_module . "'
                                and customers_id = '" . $customers_id . "'");
                return 'ok';
            }
            return '-1';
        }
        return 'failed';
    }


    function check_last($customers_id)
    {
        return false;
    }

    function check_track($mailbeez_module, $iteration = '', $customers_id, $order_id = 'none')
    {
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_track_start');
        $sql = '';
        if ($order_id != 'none') {
            // check for orders_id (e.g. when blocking)
            $sql .= " and orders_id='" . $order_id . "' ";
        }

        if ($iteration != '') {
            // check for iteration ( e.g. when sending)
            $sql .= " and iteration='" . $iteration . "' ";
        }

        // V2.7.6 added SQL_NO_CACHE
        $check_query_raw = "select SQL_NO_CACHE date_sent, iteration
					        from " . TABLE_MAILBEEZ_TRACKING . "
    					where module='" . $mailbeez_module . "'
							" . $sql . "
							" . MAILBEEZ_SIMULATION_SQL . "
							and customers_id='" . $customers_id . "'
						order by date_sent DESC";

        $check_query = mh_db_query($check_query_raw);

        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);
            $return = array($check['date_sent'], $check['iteration']);
        } else {
            $return = array(false, false);
        }
        $request_profiler->restart('mailbeez_mailer_check_track_end');

        // todo
//        mysql_free_result($check_query); // free memory
        $check_query = null;
        return $return;
    }

    // V2.6
    // check does no longer check for mode
    // also sim-blocks are valid in production mode

    function check_block($mailbeez_module, $customers_id)
    {
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_block_start');

        $cache_key = 'cache_check_block_' . "$mailbeez_module#$customers_id";

        if (isset($GLOBALS[$cache_key]) && MAILBEEZ_CHECK_CACHE) {
            $cache_value = $GLOBALS[$cache_key];
            $request_profiler->restart('mailbeez_mailer_check_block_end_cached');
            return $cache_value;
        }

        if ($this->mailBee->noblock) {
            return false;
        }
        // TABLE_MAILBEEZ_BLOCK
        $sql = '';
        if (MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR == 'Global') {
            $sql = '';
        } else {
            $sql = " module='" . $mailbeez_module . "' and ";
        }
        $check_query_sql = "select date_block
                                    from " . TABLE_MAILBEEZ_BLOCK . "
        						where " . $sql . " customers_id='" . $customers_id . "'
								order by date_block DESC";


        $check_query = mh_db_query($check_query_sql);

        $check_block_result = false;
        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);
            $check_block_result = $check['date_block'];
        }
        if (MAILBEEZ_CHECK_CACHE) {
            $GLOBALS[$cache_key] = $check_block_result;
        }
        $request_profiler->restart('mailbeez_mailer_check_block_end');

        // todo
//        mysql_free_result($check_query); // free memory

        return $check_block_result;
    }


    // mailbeez v2.6

    function check_subscription($mailbeez_module, $customers_id)
    {
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_subscription_start');
        // use Spam Compliance Framework if available
        if (defined('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_ADVANCED_STATUS') && MAILBEEZ_CONFIG_SPAMCOMPLIANCE_ADVANCED_STATUS == 'True') {
            require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_spamcompliance_advanced.php');
            $return = config_spamcompliance_advanced::check_subscription($mailbeez_module, $customers_id);
        } elseif (MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION == 'True') {

            if ($this->mailBee->nochk) {
                $return = false;
            } else {
                $check_query_sql = "select customers_email_address
                                            from " . TABLE_CUSTOMERS . "
                                            where customers_id = '" . $customers_id . "' and customers_newsletter = '1'";

                $check_query = mh_db_query($check_query_sql);
                if (mh_db_num_rows($check_query) > 0) {
                    $return = false;
                } else {
                    $return = true;
                }
            }
        } else {
            $return = false;
        }
        // todo
//        mysql_free_result($check_query); // free memory
        $request_profiler->restart('mailbeez_mailer_check_subscription_end');
        return $return;
    }

    function load_filter()
    {
        if (defined('MAILBEEZ_FILTER_INSTALLED') && mh_not_null(MAILBEEZ_FILTER_INSTALLED)) {
            if (!$this->filter_loaded) {
                $filter_modules = explode(';', MAILBEEZ_FILTER_INSTALLED);
                if (sizeof($filter_modules) > sizeof($this->filter)) {
                    while (list(, $filterbee) = each($filter_modules)) {
                        $class = substr($filterbee, 0, strrpos($filterbee, '.'));
                        $class_name = mh_get_class_name($class);
                        if (!is_object($GLOBALS[$class])) {
                            include_once(MH_DIR_FS_CATALOG . 'mailhive/filterbeez/' . $filterbee);
                            $GLOBALS[$class] = new $class_name; // register filter
                        }
                        $this->filter[] = $class; // register filter
                    }
                }
            }
        } else {
            $this->filter = array();
//            $this->filter = false;
        }
        if (!$this->filter_loaded) {
            $this->filter_loaded = true;
        }
    }

    function process_data_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode)
    {
        $mail_backup = $mail;

        // run through active data filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'data') {
                list($mail, $result) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }

        return array($mail, $result);
    }

    function process_content_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode)
    {
        $mail_backup = $mail;

        // run through active data filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'content') {
                list($mail, $result) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }

        return array($mail, $result);
    }

    // issues:
    // filter like filter_check_block_all
    // do not respect module setting nochk


    function process_check_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode)
    {
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_process_check_filter_start');
        $this->load_filter();
// run through active check filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'check') {
                $request_profiler->restart('mailbeez_mailer_check_process_check_filter_process_start ' . $filterClass);
                $filter_result = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode);
                $request_profiler->restart('mailbeez_mailer_check_process_check_filter_process_end ' . $filterClass);
                if ($filter_result != false) {
                    $request_profiler->restart('mailbeez_mailer_check_process_check_filter_return ' . $filterClass);
                    return $filter_result;
                }
            }
        }
        $request_profiler->restart('mailbeez_mailer_check_process_check_filter_end');
        return false;
    }

    // added $mailbeez_obj, $mail
    // 01.11.2011 - 2.5A

    function process_modifier_filter($mailbeez_module, $iteration, $customers_id, $order_id, $output_subject, $output_content_html, $output_content_txt, $mode, $mailbeez_obj, $mail)
    {
        $this->load_filter();
        // run through active modifier filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'modifier') {
                list($output_subject, $output_content_html, $output_content_txt) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $output_subject, $output_content_html, $output_content_txt, $mode, $mailbeez_obj, $mail);
            }
        }
        return array($output_subject, $output_content_html, $output_content_txt);
    }

    function process_afterSend_filter($mailbeez_obj, $iteration, $mail, $mode)
    {
        $mail_backup = $mail;
        $this->load_filter();
        // run through active modifier filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'afterSend') {
                $result = $GLOBALS[$filterClass]->processFilter($mailbeez_obj, $iteration, $mail, $mode);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }
        return array($mail, $result);
    }

    function check_valid($mailbeez_module, $iteration, $customers_id, $order_id, $mail)
    {
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_valid_start');
        // check if data is valid
        if (!is_array($mail)) {
            $return = false;
        } elseif ($mail['email_address'] == '' || is_null($mail['email_address']) || !stristr($mail['email_address'], '@')) {
            $return = 'email_address empty - data not valid for sending';
        } elseif (function_exists('filter_var')) { // PHP 4 compatibility
            if (!filter_var($mail['email_address'], FILTER_VALIDATE_EMAIL)) {
                $return = 'email_address invalid - data not valid for sending';
            } else {
                $return = false;
            }
        } elseif (!$this->isValidEmail($mail['email_address'])) {
            $return = 'email_address invalid - data not valid for sending';
        }

        // v2.7.6
        // fixed logic
        if (!$return && isset($mail['firstname']) && isset($mail['lastname'])) {
            if (($mail['firstname'] == '' && $mail['lastname'] == '') || (is_null($mail['firstname']) && is_null($mail['lastname']))) {
                $return = 'both firstname and lastname empty - data not valid for sending';
            } else {
                $return = false;
            }
        }

        $request_profiler->restart('mailbeez_mailer_check_valid_end');
        return $return;
    }


    function check_bounce($customers_id, $mail)
    {
        // Check if there is a hard bounce
        // or soft bounce within the last x days.
        // get the latest bounce date
        // in case of a converted softbounces that will be the hardbounce date
        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_bounce_start');

        $sql_query_raw = 'select bounce_type, date_bounce
                        from ' . TABLE_MAILBEEZ_BOUNCE . '
                        where customers_id = "' . $customers_id . '"
                        and customers_email = "' . $mail['email_address'] . '"
                        and (
                            bounce_type = "H"
                        OR
                          ( bounce_type = "S"
                            and date_bounce > ' . $this->dbdate(MAILBEEZ_BOUNCEHIVE_SOFT_DAYS) . ')
                        )
                        order by date_bounce desc
                        ';
        $check_query = mh_db_query($sql_query_raw);
        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);
            $return = $check['date_bounce'];
        } else {
            $return = false;
        }
        $request_profiler->restart('mailbeez_mailer_bounce_end');
        return $return;
    }

    function check($mailbeez_module, $iteration, $customers_id, $order_id = 'none', $mail = '', $mode = '', $called_by_filter = false)
    {

        global $request_profiler;
        $request_profiler->restart('mailbeez_mailer_check_start');

        if (!isset($mail['email_address'])) {
            // email_address is required for some filters, make sure it exists (backwards compatiblity)
            // modules should give that information
            $email_query_sql = "select customers_email_address as email_address
                                from " . TABLE_CUSTOMERS . "
                                where customers_id = '" . $customers_id . "'";
            $email_query = mh_db_query($email_query_sql);
            if (mh_db_num_rows($email_query) > 0) {
                $email_query_result = mh_db_fetch_array($email_query);
                $mail['email_address'] = $email_query_result['email_address'];
            }
        }

        $cache_key = 'cache_check_' . "$mailbeez_module#$iteration#$customers_id#$order_id";

        if (isset($GLOBALS[$cache_key]) && MAILBEEZ_CHECK_CACHE) {
            $cache_value = $GLOBALS[$cache_key];
            return $cache_value;
        }

        // compatibility filter_check_block_all.php early version which calls "check" instead of "check_block"
        if ($mailbeez_module == 'ALL') {
            $check_result_array = $this->check_block($mailbeez_module, $customers_id);
            if (MAILBEEZ_CHECK_CACHE) {
                $GLOBALS[$cache_key] = $check_result_array;
            }
            $request_profiler->restart('mailbeez_mailer_check_end_cached');
            return $check_result_array;
        }

        if (MAILBEEZ_CHECK_CACHE) {
            $GLOBALS[$cache_key] = false;
        }

        $filter_result = false;

        // check 1: already sent?
        list($date_sent, $iteration_result) = $this->check_track($mailbeez_module, $iteration, $customers_id, $order_id);

        // check 2: blocked?
        if (!$date_sent) {
            $date_block = $this->check_block($mailbeez_module, $customers_id);
        } else {
            $date_block = false;
        }

        // check 3: filter?
        if (!$date_sent && !$date_block && $mail != '') {
            list($filter_block, $filter_result) = $this->process_check_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode);
        } else {
            $filter_block = false;
        }

        // check 4: valid?
        if (!$date_sent && !$date_block && !$filter_block && $mail != '') {
            $valid_block = $this->check_valid($mailbeez_module, $iteration, $customers_id, $order_id, $mail);
        } else {
            $valid_block = false;
        }


        // check 5: subscriber?
        if (!$date_sent && !$date_block && !$filter_block && !$valid_block) {
            $check_subscr = $this->check_subscription($mailbeez_module, $customers_id);
        } else {
            $check_subscr = false;
        }

        // check 6: bounce?
        if (!$date_sent && !$date_block && !$filter_block && !$valid_block && !$check_subscr && $mail != '') {
            $bounce_block = $this->check_bounce($customers_id, $mail);
        } else {
            $bounce_block = false;
        }

        $check_result = false;
        if ($date_sent || $date_block || $filter_block || $valid_block || $bounce_block || $check_subscr) {
            $check_result = true; // no email to this customer
            $check_result_array = array($check_result, $date_sent, $iteration_result, $date_block, $filter_block, $valid_block, $filter_result, $bounce_block, $check_subscr);
            if (MAILBEEZ_CHECK_CACHE) {
                $GLOBALS[$cache_key] = $check_result_array;
            }
            $return = $check_result_array;
        } else {
            $return = false;
        }
        $request_profiler->restart('mailbeez_mailer_check_end');
        return $return;
    }

    // added $order_id
    // 03.10.2011 - 2.5A
    // added $mail
    // 15.11.2012 - 2.7A
    function early_check($module, $iteration, $customers_id, $order_id = 'none', $mail = '')
    {
        // early check enabled.
        if (MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED == 'True') {
            $chk_result = $this->check($module, $iteration, $customers_id, $order_id, $mail);
            return $chk_result;
        }
        return false;
    }

    function late_check($mailbeez_module, $iteration, $customers_id, $order_id = 'none', $mail = '', $mode = '', $called_by_filter = false)
    {
        return $this->check($mailbeez_module, $iteration, $customers_id, $order_id, $mail, $mode, $called_by_filter);
    }


    // V2.6: make mode aware
    function buildBlockUrl($mail, $module_path)
    {
        $mode = 'prod';
        if (MAILBEEZ_SIMULATION == 'True') {
            $mode = MAILBEEZ_SIMULATION_ID;
        }

        $behaviour = MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR;

        $block_token = base64_encode($mail['customers_id'] . '|' . $mail['email_address'] . '|' . $mode . '|' . $behaviour);

        //might run through URL rewrite
        //$block_url = mh_href_email_link(FILENAME_HIVE, 'ma=block&m=' . $module . '&mp=' . $block_token , 'NONSSL', false);
        // plain url

        $block_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_HIVE . '?ma=block&m=' . $this->get_module_name($module_path) . '&mp=' . $block_token;
        return $block_url;
    }

    // not used?
    function buildUnBlockUrl($mail, $module_path)
    {
        $block_token = base64_encode($mail['customers_id'] . '|' . $mail['email_address']);
        $block_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_HIVE . '?ma=unblock&m=' . $this->get_module_name($module_path) . '&mp=' . $block_token;
        return $block_url;
    }

    function dbdate($day)
    {
        $rawtime = strtotime("-" . $day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }


    function get_module_name($module_path)
    {
        return mh_get_module_name($module_path);
    }

    function isValidEmail($email)
    {
        return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
    }

}

?>