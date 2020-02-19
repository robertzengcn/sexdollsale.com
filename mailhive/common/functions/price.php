<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6

  price functions
 */

///////////////////////////////////////////////////////////////////////////////
///                                                                          //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


if (!function_exists('mh_price')) {
    function mh_price($value)
    {
        switch (MH_PLATFORM) {
            case 'oscommerce':
            case 'creloaded':
            case 'digistore':
            case 'zencart':
                global $currencies;
                if (!class_exists('currencies')) {
                    require_once(DIR_WS_CLASSES . 'currencies.php');
                    $currencies = new currencies();
                } elseif (!is_object($currencies)) {
                    $currencies = new currencies();
                }
                return $currencies->format($value);
                break;
            case 'mercari':
                global $price;
                if (MH_CONTEXT == 'ADMIN') {
                    $value = format_price($value, 1, DEFAULT_CURRENCY, 0, 0);
                    return $value;
                }
                return $price->format($value, true);
            case 'xtc':
            case 'gambio':
                global $xtPrice;
                if (MH_CONTEXT == 'ADMIN') {
                    $value = format_price($value, 1, DEFAULT_CURRENCY, 0, 0);
                    return $value;
                }
                return $xtPrice->xtcFormat($value, true, false);
                break;
            default:
                echo 'platform not supported';
        }
    }
}

?>