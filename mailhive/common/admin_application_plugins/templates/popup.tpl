<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html {$HTML_PARAMS}>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}"/>
  <title>{$TITLE}</title>
  <link rel="stylesheet" type="text/css" href="{$MH_ADMIN_CSS_PATH}includes/stylesheet.css"/>
  <style>
      {literal}
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

      {/literal}
  </style>
  <script language="javascript" src="{$MH_ADMIN_CSS_PATH}includes/general.js"></script>

  <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.min-1.5.1.js"></script>
  <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
  <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>
{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}
{literal}
  <script type="text/javascript">
    function scrolldown() {
      var a = document.anchors.length;
      var b = document.anchors[a - 1];
      var y = b.offsetTop;
      window.scrollTo(0, y + 120);
    }
  </script>
{/literal}

  <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
  <link rel="stylesheet" type="text/css"
        href="{$MH_CATALOG_URL}mailhive/common/admin_application_plugins/common.css">
</head>
<body bgcolor="#FFFFFF">
{$MAILBEEZ_MAIN_CONTENT}
</body>
</html>