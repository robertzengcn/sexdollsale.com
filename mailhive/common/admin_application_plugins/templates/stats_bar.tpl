<script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/timeago/jquery.timeago.js"></script>

{if $smarty.session.language == 'german'}
    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/timeago/locales/jquery.timeago.de.js"></script>
{/if}

<div class="stat_bar" style="display: block">
    <div class="dashboard_stats_bar_container">
        <div class="sb_chart">
            <div class="label" style="text-align: left;">
                {if $smarty.const.MH_RESPONSIVE != 'True'}
                <div class="arrow_box">{/if}

                    <img src="{$MH_CATALOG_URL}mailhive/common/images/icon_mobile_32.png" width="12"
                         height="12" align="absmiddle" hspace="0" border="0" style="margin-top: -2px">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_MOBILE}: {$pct_mobile|string_format:"%d"}%
                    {*({$cnt_mobile|default:'0'})*}

                    {if $smarty.const.MH_RESPONSIVE != 'True'}</div>{/if}

            </div>
        </div>
        <div class="sb_chart_divider"></div>
        <div class="sb_chart_number">
            <div class="number">{$cnt_sent}</div>
            <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_EMAILS} {$smarty.const.MAILBEEZ_STATSBAR_LABEL_SENT}
                <br/>
                {if $beginning_of_time != '2000-01-01 00:00:00'}
                    {$smarty.const.MAILBEEZ_STATSBAR_LABEL_BEGIN_OF_TIME}
                    <abbr class="timeago" title="{$beginning_of_time}">{$beginning_of_time|date_format}</abbr>
                {/if}
            </div>
        </div>

        {if $smarty.const.MAILBEEZ_BOUNCEHIVE_STATUS == 'True'}
            <div class="sb_chart_arrow">&gt;</div>
            <div class="sb_chart">
                <div class="sb_percentage-delivered" data-percent="{$pct_delivered|string_format:"%d"}">
                    <span>{$pct_delivered|string_format:"%d"}</span>%
                </div>
                <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_DELIVERED}: <br/>
                    {$cnt_delivered|default:'0'}
                </div>
            </div>
            <div class="sb_chart_arrow">&gt;</div>
        {/if}


        <div class="sb_chart">
            <div class="sb_percentage-open" data-percent="{$pct_opened|string_format:"%d"}">
                <span>{$pct_opened|string_format:"%d"}</span>%
            </div>
            <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_OPENED}: <br/>
                {$cnt_opened|default:'0'}
                {*<br/>*}
                {*<img src="{$MH_CATALOG_URL}mailhive/common/images/icon_mobile_32.png" width="12"*}
                {*height="12" align="absmiddle" hspace="0" border="0" style="margin-top: -2px">*}

                {*{$smarty.const.MAILBEEZ_STATSBAR_LABEL_MOBILE}: *}
                {**}
                {*{$pct_mobile|string_format:"%d"}%*}

            </div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart">
            <div class="sb_percentage-click" data-percent="{$pct_clicked|string_format:"%d"}">
                <span>{$pct_clicked|string_format:"%d"}</span>%
            </div>
            <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_CLICKED}: <br/>
                {$cnt_clicked|default:'0'}</div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart">
            <div class="sb_percentage-order" data-percent="{$pct_ordered|string_format:"%d"}">
                <span>{$pct_ordered|string_format:"%d"}</span>%
            </div>
            <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_ORDERED}: <br/>
                {$cnt_orders|default:'0'}</div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart_number">
            <div class="number">{$revenue}</div>
            <div class="label">{$smarty.const.MAILBEEZ_STATSBAR_LABEL_REVENUE}<br>
                {$smarty.const.MAILBEEZ_STATSBAR_LABEL_COUPON_VAL}: {$coupon_val}</div>
        </div>

        <div style="clear:both; height: 2px;"></div>

        <div style="text-align: left">
            {if $smarty.const.MH_RESPONSIVE != 'True'}
                <br/>
                <div style="padding: 7px; border: 1px solid #c0c0c0; border-radius: 3px; background-color: {$smarty.const.MH_COLOR_YELLOW}">
                    <img src="{$MH_CATALOG_URL}mailhive/common/images/responsive_75.png" width="75" hspace="0" border="0" style="margin-top: -20px; float: left; padding-right: 20px;">
                    {$smarty.const.MAILBEEZ_STATSBAR_RESPONSIVE_INFO}
                </div>
            {/if}
        </div>
    </div>
    <div style="clear:both; height: 2px;"></div>

</div>

{literal}
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("abbr.timeago").timeago();
        });
    </script>
{/literal}
