<!--[if IE]>
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/gauge.js"></script>
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/jquery.gauge.js"></script>
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/flot/jquery.flot.js"></script>
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/flot/jquery.flot.resize.js"></script>
<script type="text/javascript"
        src="{$MH_CATALOG_URL}mailhive/common/js/flot/jquery.flot.stack.js"></script>
<script type="text/javascript">
    {literal}
    function openReportPopup(popup_link) {
        {/literal}
        {if ($MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox')}
        {literal}
        jQuery.fn.ceebox.popup('<a href="' + popup_link + '" rel="iframe">link</a>', {overlayOpacity: 0.0, animSpeed: 1, fadeIn: 1, fadeOut: 1, easing: 'linear', titles: false, borderColor: '#666' });
        {/literal}
        {/if}
        {literal}

        return false;
    }

    function openCISPopup(cid, viewmodule) {
        {/literal}
        {if $smarty.const.MAILBEEZ_INSIGHT_VIEW_STATUS == 'True'}
        return openReportPopup('{$MAILBEEZ_CIS_URL}&cID=' + cid + '&view_module=' + viewmodule);
        {else}
        return false;
        {/if}
        {literal}
    }
    {/literal}
</script>
