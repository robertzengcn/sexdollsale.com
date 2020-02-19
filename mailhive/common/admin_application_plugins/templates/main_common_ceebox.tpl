{if ($MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox')}
<script type="text/javascript">
        {literal}
        jQuery(document).ready(function () {
            jQuery(".ceebox").ceebox({overlayOpacity:0.0, animSpeed:1, fadeIn:10, fadeOut:1, easing:'linear', titles:false, borderColor:'#666' });
        });
        {/literal}
</script>
{/if}