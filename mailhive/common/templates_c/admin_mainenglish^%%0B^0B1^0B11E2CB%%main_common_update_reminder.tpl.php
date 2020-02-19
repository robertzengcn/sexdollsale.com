<?php /* Smarty version 2.6.26, created on 2014-01-04 01:15:01
         compiled from main_common_update_reminder.tpl */ ?>
<?php if ($this->_tpl_vars['MAILBEEZ_UPDATE_REMINDER']): ?>
    <script type="text/javascript">
        <!--
        window.setTimeout("mbUpdateReminder()", 1000);
        <?php echo '
        function mbUpdateReminder() {
        '; ?>

            Check = confirm("Check mailbeez.com for Updates?\n(You can always use the button upper right)\n<?php if (( $this->_tpl_vars['MAILBEEZ_MAILHIVE_POPUP_MODE'] == 'off' )): ?>(this will redirect to the mailbeez server)<?php endif; ?>");
        <?php echo '
            if (Check == true) {
        '; ?>


        <?php if (( $this->_tpl_vars['MAILBEEZ_MAILHIVE_POPUP_MODE'] == 'CeeBox' )): ?>
             jQuery.fn.ceebox.popup("<a rel='width:600' href='<?php echo $this->_tpl_vars['MAILBEEZ_VERSION_CHECK_URL']; ?>
'>link</a>",
                     <?php echo '
                     {overlayOpacity:0.0, animSpeed: 100, fadeIn: 0, titles: false	}
                     '; ?>

        );
        <?php else: ?>
             document.location.href="<?php echo $this->_tpl_vars['MAILBEEZ_VERSION_CHECK_URL']; ?>
";
        <?php endif; ?>
        <?php echo '
         }
        }
        '; ?>

        //-->
    </script>
<?php endif; ?>