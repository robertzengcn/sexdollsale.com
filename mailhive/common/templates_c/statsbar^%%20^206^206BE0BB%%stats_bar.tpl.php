<?php /* Smarty version 2.6.26, created on 2014-01-04 03:15:06
         compiled from stats_bar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'stats_bar.tpl', 15, false),array('modifier', 'date_format', 'stats_bar.tpl', 29, false),array('modifier', 'default', 'stats_bar.tpl', 41, false),)), $this); ?>
<script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/timeago/jquery.timeago.js"></script>

<?php if ($_SESSION['language'] == 'german'): ?>
    <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/timeago/locales/jquery.timeago.de.js"></script>
<?php endif; ?>

<div class="stat_bar" style="display: block">
    <div class="dashboard_stats_bar_container">
        <div class="sb_chart">
            <div class="label" style="text-align: left;">
                <?php if (@MH_RESPONSIVE != 'True'): ?>
                <div class="arrow_box"><?php endif; ?>

                    <img src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/images/icon_mobile_32.png" width="12"
                         height="12" align="absmiddle" hspace="0" border="0" style="margin-top: -2px"><?php echo @MAILBEEZ_STATSBAR_LABEL_MOBILE; ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['pct_mobile'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
%
                    
                    <?php if (@MH_RESPONSIVE != 'True'): ?></div><?php endif; ?>

            </div>
        </div>
        <div class="sb_chart_divider"></div>
        <div class="sb_chart_number">
            <div class="number"><?php echo $this->_tpl_vars['cnt_sent']; ?>
</div>
            <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_EMAILS; ?>
 <?php echo @MAILBEEZ_STATSBAR_LABEL_SENT; ?>

                <br/>
                <?php if ($this->_tpl_vars['beginning_of_time'] != '2000-01-01 00:00:00'): ?>
                    <?php echo @MAILBEEZ_STATSBAR_LABEL_BEGIN_OF_TIME; ?>

                    <abbr class="timeago" title="<?php echo $this->_tpl_vars['beginning_of_time']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['beginning_of_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
</abbr>
                <?php endif; ?>
            </div>
        </div>

        <?php if (@MAILBEEZ_BOUNCEHIVE_STATUS == 'True'): ?>
            <div class="sb_chart_arrow">&gt;</div>
            <div class="sb_chart">
                <div class="sb_percentage-delivered" data-percent="<?php echo ((is_array($_tmp=$this->_tpl_vars['pct_delivered'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
">
                    <span><?php echo ((is_array($_tmp=$this->_tpl_vars['pct_delivered'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</span>%
                </div>
                <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_DELIVERED; ?>
: <br/>
                    <?php echo ((is_array($_tmp=@$this->_tpl_vars['cnt_delivered'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>

                </div>
            </div>
            <div class="sb_chart_arrow">&gt;</div>
        <?php endif; ?>


        <div class="sb_chart">
            <div class="sb_percentage-open" data-percent="<?php echo ((is_array($_tmp=$this->_tpl_vars['pct_opened'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
">
                <span><?php echo ((is_array($_tmp=$this->_tpl_vars['pct_opened'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</span>%
            </div>
            <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_OPENED; ?>
: <br/>
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['cnt_opened'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>

                                                
                                                
            </div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart">
            <div class="sb_percentage-click" data-percent="<?php echo ((is_array($_tmp=$this->_tpl_vars['pct_clicked'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
">
                <span><?php echo ((is_array($_tmp=$this->_tpl_vars['pct_clicked'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</span>%
            </div>
            <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_CLICKED; ?>
: <br/>
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['cnt_clicked'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart">
            <div class="sb_percentage-order" data-percent="<?php echo ((is_array($_tmp=$this->_tpl_vars['pct_ordered'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
">
                <span><?php echo ((is_array($_tmp=$this->_tpl_vars['pct_ordered'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</span>%
            </div>
            <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_ORDERED; ?>
: <br/>
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['cnt_orders'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</div>
        </div>
        <div class="sb_chart_arrow">&gt;</div>
        <div class="sb_chart_number">
            <div class="number"><?php echo $this->_tpl_vars['revenue']; ?>
</div>
            <div class="label"><?php echo @MAILBEEZ_STATSBAR_LABEL_REVENUE; ?>
<br>
                <?php echo @MAILBEEZ_STATSBAR_LABEL_COUPON_VAL; ?>
: <?php echo $this->_tpl_vars['coupon_val']; ?>
</div>
        </div>

        <div style="clear:both; height: 2px;"></div>

        <div style="text-align: left">
            <?php if (@MH_RESPONSIVE != 'True'): ?>
                <br/>
                <div style="padding: 7px; border: 1px solid #c0c0c0; border-radius: 3px; background-color: <?php echo @MH_COLOR_YELLOW; ?>
">
                    <img src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/images/responsive_75.png" width="75" hspace="0" border="0" style="margin-top: -20px; float: left; padding-right: 20px;">
                    <?php echo @MAILBEEZ_STATSBAR_RESPONSIVE_INFO; ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
    <div style="clear:both; height: 2px;"></div>

</div>

<?php echo '
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("abbr.timeago").timeago();
        });
    </script>
'; ?>
