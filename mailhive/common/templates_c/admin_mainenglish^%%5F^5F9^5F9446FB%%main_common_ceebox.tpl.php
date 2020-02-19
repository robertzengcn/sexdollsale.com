<?php /* Smarty version 2.6.26, created on 2014-01-04 01:15:01
         compiled from main_common_ceebox.tpl */ ?>
<?php if (( $this->_tpl_vars['MAILBEEZ_MAILHIVE_POPUP_MODE'] == 'CeeBox' )): ?>
<script type="text/javascript">
        <?php echo '
        jQuery(document).ready(function () {
            jQuery(".ceebox").ceebox({overlayOpacity:0.0, animSpeed:1, fadeIn:10, fadeOut:1, easing:\'linear\', titles:false, borderColor:\'#666\' });
        });
        '; ?>

</script>
<?php endif; ?>