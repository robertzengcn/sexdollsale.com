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


class mb_mouseflow
{

// class constructor
    function mb_mouseflow()
    {

    }

    function rewriteContent($input, $type = 'html')
    {
        if (MAILBEEZ_MOUSEFLOW_STATUS == 'False') {
            return $input;
        }

        $rewritten = $input;
        if ($type == 'html') {
            $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "&" . MAILBEEZ_MOUSEFLOW_MAILBEEZ_URL_ID . "\"", $rewritten);
            $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "?" . MAILBEEZ_MOUSEFLOW_MAILBEEZ_URL_ID . "\"", $rewritten);
        }
        return $rewritten;
    }
}

?>