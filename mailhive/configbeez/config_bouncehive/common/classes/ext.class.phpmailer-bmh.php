<?php
/* class.phpmailer-bmh.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer-BMH (Bounce Mail Handler)                            |
|   Version: 5.0.0rc1                                                       |
|   Contact: codeworxtech@users.sourceforge.net                             |
|      Info: http://phpmailer.codeworxtech.com                              |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost andy.prevost@worxteam.com (admin)                 |
| Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            (http://www.gnu.org/licenses/gpl.html)                         |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| This is a update of the original Bounce Mail Handler script               |
| http://sourceforge.net/projects/bmh/                                      |
| The script has been renamed from Bounce Mail Handler to PHPMailer-BMH     |
| ------------------------------------------------------------------------- |
| We offer a number of paid services:                                       |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
Last updated: January 21 2009 13:49 EST */


require_once(_PATH_BMH . 'common/libraries/PHPMailer-BMH/class.phpmailer-bmh.php');
require_once(_PATH_BMH . 'common/libraries/PHP-Bounce-Handler/bounce_driver.class.php');
/*
 * $bouncehandler = new ExtBouncehandler();
$multiArray = $bouncehandler->get_the_facts($strEmail); , or
 *
 */


class ExtBounceMailHandler extends BounceMailHandler
{

// mailbeez

    public $action_function = BH_BOUNCEBEEZ_ACTION;

    function ExtBounceMailHandler()
    {

        include_once(_PATH_BMH . 'bouncebeez/' . $this->action_function . '.php');

        $this->bouncehandler = new Bouncehandler();
        // a perl regular expression to find a web beacon in the email body
        $this->bouncehandler->web_beacon_preg_1 = "/u=([0-9a-fA-F]{32})/";
        $this->bouncehandler->web_beacon_preg_2 = "/m=(\d*)/";

        // find a web beacon in an X-header field (in the head section)
        $this->bouncehandler->x_header_search_1 = "X-ctnlist-suid";
        //$bouncehandler->x_header_search_2 = "X-sumthin-sumpin";

        $bouncelist["This user doesn't have"] = '5.1.1';
        $bouncelist["Host or domain name not found"] = '5.1.1';
        $bouncelist["Dies ist"] = '5.1.1';
        $bouncelist['[45]\.\d\.\d'] = 'x'; # use the code from the regex
        $bouncelist['5\.1\.1'] = '5.1.1'; # use the code from the regex
        $bouncelist['User unknown'] = '5.1.1'; # use the code from the regex
        $this->bouncehandler->bouncelist = array_merge($this->bouncehandler->bouncelist, $bouncelist);

    }


    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////


    /**
     * Process the messages in a mailbox
     * @param string $max       (maximum limit messages processed in one batch, if not given uses the property $max_messages
     * @return boolean
     */
    function processMailbox($max = false)
    {
        // echo "<h1>processMailbox Ext</h1>";

        if (empty($this->action_function) || !function_exists($this->action_function)) {
            $this->error_msg = 'Action function not found!';
            $this->output();
            return false;
        }

        if ($this->moveHard && ($this->disable_delete === false)) {
            $this->disable_delete = true;
        }

        if (!empty($max)) {
            $this->max_messages = $max;
        }


        // initialize counters
        $c_total = @imap_num_msg($this->_mailbox_link);

        if ($c_total === false) {
            $this->error_msg = 'Cannot get number of Messages';
        }

        $c_fetched = $c_total;
        $c_processed = 0;
        $c_unprocessed = 0;
        $c_deleted = 0;
        $c_moved = 0;
        $this->output('Total: ' . $c_total . ' messages ');
        // proccess maximum number of messages
        if ($c_fetched > $this->max_messages) {
            $c_fetched = $this->max_messages;
            $this->output('Processing first ' . $c_fetched . ' messages ');
        }

        if ($this->testmode) {
            $this->output('Running in test mode, not deleting messages from mailbox<br />');
        } else {
            if ($this->disable_delete) {
                if ($this->moveHard) {
                    $this->output('Running in move mode<br />');
                } else {
                    $this->output('Running in disable_delete mode, not deleting messages from mailbox<br />');
                }
            } else {
                $this->output('Processed messages will be deleted from mailbox<br />');
            }
        }
        for ($x = 1; $x <= $c_fetched; $x++) {
            /*
            $this->output( $x . ":",VERBOSE_REPORT);
            if ($x % 10 == 0) {
              $this->output( '.',VERBOSE_SIMPLE);
            }
            */
            // fetch the messages one at a time
            if ($this->use_fetchstructure) {
                $structure = imap_fetchstructure($this->_mailbox_link, $x);
                if ($structure->type == 1 && $structure->ifsubtype && $structure->subtype == 'REPORT' && $structure->ifparameters && $this->isParameter($structure->parameters, 'REPORT-TYPE', 'delivery-status')) {
                    $processed = $this->processBounce($x, 'DSN', $c_total);
                } else { // not standard DSN msg
                    $this->output('Msg #' . $x . ' is not a standard DSN message', VERBOSE_REPORT);
                    if ($this->debug_body_rule) {
                        $this->output("  Content-Type : {$match[1]}", VERBOSE_DEBUG);
                    }
                    $processed = $this->processBounce($x, 'BODY', $c_total);
                }
            } else {
                $header = imap_fetchheader($this->_mailbox_link, $x);
                // Could be multi-line, if the new line begins with SPACE or HTAB
                if (preg_match("/Content-Type:((?:[^\n]|\n[\t ])+)(?:\n[^\t ]|$)/is", $header, $match)) {
                    if (preg_match("/multipart\/report/is", $match[1]) && preg_match("/report-type=[\"']?delivery-status[\"']?/is", $match[1])) {
                        // standard DSN msg
                        $processed = $this->processBounce($x, 'DSN', $c_total);
                    } else { // not standard DSN msg
                        $this->output('Msg #' . $x . ' is not a standard DSN message', VERBOSE_REPORT);
                        if ($this->debug_body_rule) {
                            $this->output("  Content-Type : {$match[1]}", VERBOSE_DEBUG);
                        }
                        $processed = $this->processBounce($x, 'BODY', $c_total);
                    }
                } else { // didn't get content-type header
                    $this->output('Msg #' . $x . ' is not a well-formatted MIME mail, missing Content-Type', VERBOSE_REPORT);
                    if ($this->debug_body_rule) {
                        $this->output('  Headers: ' . $this->bmh_newline . $header . $this->bmh_newline, VERBOSE_DEBUG);
                    }
                    $processed = $this->processBounce($x, 'BODY', $c_total);
                }
            }


            $deleteFlag[$x] = false;
            $moveFlag[$x] = false;
            if ($processed) {
                $c_processed++;

                // mailbeez code
                switch ($processed) {
                    case 'hard';
                        // check if the move directory exists, if not create it
                        $this->mailbox_exist($this->hardMailbox);
                        // move the message
                        @imap_mail_move($this->_mailbox_link, $x, $this->hardMailbox);
                        $moveFlag[$x] = true;
                        $c_moved++;
                        break;
                    case 'soft';
                        // check if the move directory exists, if not create it
                        $this->mailbox_exist($this->softMailbox);
                        // move the message
                        @imap_mail_move($this->_mailbox_link, $x, $this->softMailbox);
                        $moveFlag[$x] = true;
                        $c_moved++;
                        break;
                    case 'nobounce';
                        // check if the move directory exists, if not create it
                        $this->mailbox_exist($this->procMailbox);
                        // move the message
                        @imap_mail_move($this->_mailbox_link, $x, $this->procMailbox);
                        $moveFlag[$x] = true;
                        $c_moved++;
                        break;

                }

                /*

                if (($this->testmode === false) && ($this->disable_delete === false)) {
                    // delete the bounce if not in test mode and not in disable_delete mode
                    @imap_delete($this->_mailbox_link, $x);
                    $deleteFlag[$x] = true;
                    $c_deleted++;
                } elseif ($this->moveHard) {
                    // check if the move directory exists, if not create it
                    $this->mailbox_exist($this->hardMailbox);
                    // move the message
                    @imap_mail_move($this->_mailbox_link, $x, $this->hardMailbox);
                    $moveFlag[$x] = true;
                    $c_moved++;
                } elseif ($this->moveSoft) {
                    // check if the move directory exists, if not create it
                    $this->mailbox_exist($this->softMailbox);
                    // move the message
                    @imap_mail_move($this->_mailbox_link, $x, $this->softMailbox);
                    $moveFlag[$x] = true;
                    $c_moved++;
                }
                */
            } else { // not processed
                $c_unprocessed++;
                if (!$this->testmode && !$this->disable_delete && $this->purge_unprocessed) {
                    // delete this bounce if not in test mode, not in disable_delete mode, and the flag BOUNCE_PURGE_UNPROCESSED is set
                    @imap_delete($this->_mailbox_link, $x);
                    $deleteFlag[$x] = true;
                    $c_deleted++;
                }
            }
            flush();
            ob_flush();
        }
        $this->output($this->bmh_newline . 'Closing mailbox, and purging messages');
        imap_close($this->_mailbox_link);
        $this->output('Read: ' . $c_fetched . ' messages');
        $this->output($c_processed . ' action taken');
        $this->output($c_unprocessed . ' no action taken');
        $this->output($c_deleted . ' messages deleted');
        $this->output($c_moved . ' messages moved');
        return true;
    }


    /**
     * Function to process each individual message
     * @param int    $pos            (message number)
     * @param string $type           (DNS or BODY type)
     * @param string $totalFetched   (total number of messages in mailbox)
     * @return boolean
     */
    function processBounce($pos, $type, $totalFetched)
    {
        $true_header = imap_fetchbody($this->_mailbox_link, $pos, 0);
        $true_body = imap_fetchbody($this->_mailbox_link, $pos, 1);
        $result = $this->bouncehandler->parse_email($true_header . $true_body);

        $params = array('result' => $result[0], 'header' => $true_header, 'body' => $true_body);
        return call_user_func($this->action_function, $params);

        // -----------------
    }

    function output($msg = false, $verbose_level = VERBOSE_SIMPLE, $type = 'msg')
    {
        if ($this->verbose >= $verbose_level) {
            if (empty($msg)) {
                //echo $this->error_msg . $this->bmh_newline;
            } else {
                echo '<div class="' . $type . '">' . $msg . $this->bmh_newline . '</div>';
                $this->output_flush();
            }
        }
    }

    function output_flush()
    {
        echo str_repeat(" ", 4096); // force a flush
        echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
      <!--
      scrolldown();
      //-->
      </SCRIPT>';
        ob_flush();
        flush();
    }
}

?>
