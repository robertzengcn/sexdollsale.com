<?php /* Smarty version 2.6.26, created on 2014-01-04 05:33:07
         compiled from mh:txtBodyTemplate */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'mh:txtBodyTemplate', 3, false),)), $this); ?>
Happy Birthday

firstname: <?php echo ((is_array($_tmp=@$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Firstname') : smarty_modifier_default($_tmp, 'Firstname')); ?>

lastname: <?php echo ((is_array($_tmp=@$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Lastname') : smarty_modifier_default($_tmp, 'Lastname')); ?>

email: <?php echo ((is_array($_tmp=@$this->_tpl_vars['email_address'])) ? $this->_run_mod_handler('default', true, $_tmp, 'me@mail.com') : smarty_modifier_default($_tmp, 'me@mail.com')); ?>


customers id: <?php echo ((is_array($_tmp=@$this->_tpl_vars['customers_id'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Customers_id') : smarty_modifier_default($_tmp, 'Customers_id')); ?>


birthday: <?php echo ((is_array($_tmp=@$this->_tpl_vars['date_of_birth'])) ? $this->_run_mod_handler('default', true, $_tmp, '01.01.2011') : smarty_modifier_default($_tmp, '01.01.2011')); ?>