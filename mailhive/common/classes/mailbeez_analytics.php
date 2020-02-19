<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2013 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License



 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


class mailbeez_analytics
{

    // these regexp are also applied during update process
    // see config_queen

    // preg_match('/' . self::MOBILE_REGEX1 . '/i', $useragent)
    const MOBILE_REGEX1 = '(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino';

    // preg_match('/' . self::MOBILE_REGEX2 . '/i', substr($useragent, 0, 4)
    const MOBILE_REGEX2 = '1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-';


// class constructor
    var $timeframe_start;

    function mailbeez_analytics()
    {
        $this->timeframe_start = (defined('MAILBEEZ_ANALYTICS_BEGIN_OF_TIME')) ? MAILBEEZ_ANALYTICS_BEGIN_OF_TIME : '2000-01-01 00:00:00';
    }


    function get_engagement_data()
    {
        $exclude_modules_sql = '';

        $timeframe_start = $this->timeframe_start;

        $query_sql = "select count(*) as cnt_sent,
                        SUM(IF(t.opened > '2000-01-01 00:00:00', 1,0) or IF(t.clicked > '2000-01-01 00:00:00', 1,0)) AS cnt_opened,
                        SUM(IF(t.clicked > '2000-01-01 00:00:00', 1,0)) AS cnt_clicked,
                        SUM(IF(t.bounce_status not in ('H', 'S'), 1,0) ) AS cnt_delivered
                        from " . TABLE_MAILBEEZ_TRACKING . " t
                      where date_sent > '" . $timeframe_start . "'
                          " . str_replace('simulation', 't.simulation', MAILBEEZ_SIMULATION_SQL) . "
                        ";

        $query = mh_db_query($query_sql);
        $result = mh_db_fetch_array($query);

        return array($result['cnt_sent'], $result['cnt_opened'], $result['cnt_clicked'], $result['cnt_delivered']);

    }

    function get_orders_data()
    {
        $timeframe_start = $this->timeframe_start;

        $query_orders_cnt_raw = "select count(distinct orders_id) as cnt_orders
                                    from " . TABLE_MAILBEEZ_TRACKING_ORDERS . "
                                    where date_record > '" . $timeframe_start . "'";

        $query_orders_cnt = mh_db_query($query_orders_cnt_raw);
        $result_cnt = mh_db_fetch_array($query_orders_cnt);

        // exclude double trackings:
        $query_revenue_raw = "select sum(ot.value) as revenue_value from
                                (select distinct orders_id
                                    from " . TABLE_MAILBEEZ_TRACKING_ORDERS . "
                                    where date_record > '" . $timeframe_start . "'
                                 ) mo
                              join " . TABLE_ORDERS_TOTAL . " ot
                                on (ot.orders_id = mo.orders_id and class='ot_subtotal')
                              where 1";

//        echo "Subtotal SQL: " . $query_revenue_raw;

        $query_revenue = mh_db_query($query_revenue_raw);
        $result_revenue = mh_db_fetch_array($query_revenue);

        $query_coupon_raw = "select sum(ot.value) as coupon_value from
                                (select distinct orders_id
                                    from " . TABLE_MAILBEEZ_TRACKING_ORDERS . "
                                    where date_record > '" . $timeframe_start . "'
                                 ) mo
                              join " . TABLE_ORDERS_TOTAL . " ot
                                on (ot.orders_id = mo.orders_id and class='ot_coupon')
                              where 1";

//        echo "<br>Coupon SQL: " . $query_coupon_raw;

        $query_coupon = mh_db_query($query_coupon_raw);
        $result_coupon = mh_db_fetch_array($query_coupon);

        return array($result_revenue['revenue_value'], $result_cnt['cnt_orders'], $result_coupon['coupon_value']);
//        return mh_price($query_revenue_result['revenue']);
    }

    function get_mobile_useragent_data()
    {
        $query_mobile_raw = "select count(*) as cnt_mobile
                                    from " . TABLE_MAILBEEZ_TRACKING . "
                                where mobile='1'
                                " . MAILBEEZ_SIMULATION_SQL . "
                                ";

        $query_mobile = mh_db_query($query_mobile_raw);
        $result_mobile = mh_db_fetch_array($query_mobile);
        return array($result_mobile['cnt_mobile']);


        /*
         *
         * not tested
        $query_mobile_raw = "select count(*) as total, (
                            count(*) / (
                                select count(*)
                                    from " . TABLE_MAILBEEZ_TRACKING . "
                                where mobile='1')
                            ) * 100 as 'percentage'
                            from " . TABLE_MAILBEEZ_TRACKING . "";

        $query_mobile = mh_db_query($query_mobile_raw);
        $result_mobile = mh_db_fetch_array($query_mobile);
        return array($result_mobile['total'], $result_mobile['percentage']);
         */
    }

    static function is_mobile_useragent($useragent)
    {
        // http://detectmobilebrowsers.com/
        $match = preg_match('/' . self::MOBILE_REGEX1 . '/i', $useragent) || preg_match('/' . self::MOBILE_REGEX2 . '/i', substr($useragent, 0, 4));
        return ($match) ? 1 : 0;
    }
}

?>