<?php /* Smarty version 2.6.26, created on 2014-01-04 01:15:01
         compiled from main_zc.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo $this->_tpl_vars['HTML_PARAMS']; ?>
>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['SESSION_CHARSET']; ?>
"/>
    <title><?php echo $this->_tpl_vars['TITLE']; ?>
</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css"/>
    <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS"/>
    <script language="javascript" src="includes/menu.js"></script>
    <script language="javascript" src="includes/general.js"></script>
    
    <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/jquery-1.8.2.min.js"></script>
    <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/jquery.tools.min-1.2.7.js"></script>


    <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_common_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_common_ceebox.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/admin_application_plugins/common.css">
</head>
<body bgcolor="#FFFFFF" onLoad="init()">
<!-- header //-->
<?php echo $this->_tpl_vars['ADMIN_HEADER']; ?>

<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <!-- body_text //-->
        <td width="100%" valign="top">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_common_content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
        <!-- body_text_eof //-->
    </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php echo $this->_tpl_vars['ADMIN_FOOTER']; ?>

<!-- footer_eof //-->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_common_update_reminder.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</body>
</html>
<?php echo $this->_tpl_vars['ADMIN_APPLICATION_BOTTOM']; ?>
