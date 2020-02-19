<?php /* Smarty version 2.6.26, created on 2014-01-04 03:17:49
         compiled from popup.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo $this->_tpl_vars['HTML_PARAMS']; ?>
>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['SESSION_CHARSET']; ?>
"/>
  <title><?php echo $this->_tpl_vars['TITLE']; ?>
</title>
  <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['MH_ADMIN_CSS_PATH']; ?>
includes/stylesheet.css"/>
  <style>
      <?php echo '
      * {
        font-family: Arial, Helvetica;
        font-size: 11px;
      }

      h1 {
        font-family: Arial, Helvetica;
        font-size: 14px;
      }

      body {
        margin: 0;
        width: 100%;
        background: none;
      }


      input.button {
          display: inline-block;
          min-width: 120px;
          width: auto;
      }

      '; ?>

  </style>
  <script language="javascript" src="<?php echo $this->_tpl_vars['MH_ADMIN_CSS_PATH']; ?>
includes/general.js"></script>

  <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/jquery.min-1.5.1.js"></script>
  <script language="javascript" src="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
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
<?php echo '
  <script type="text/javascript">
    function scrolldown() {
      var a = document.anchors.length;
      var b = document.anchors[a - 1];
      var y = b.offsetTop;
      window.scrollTo(0, y + 120);
    }
  </script>
'; ?>


  <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/js/ceebox/css/ceebox_mh.css"/>
  <link rel="stylesheet" type="text/css"
        href="<?php echo $this->_tpl_vars['MH_CATALOG_URL']; ?>
mailhive/common/admin_application_plugins/common.css">
</head>
<body bgcolor="#FFFFFF">
<?php echo $this->_tpl_vars['MAILBEEZ_MAIN_CONTENT']; ?>

</body>
</html>