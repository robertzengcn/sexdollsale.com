<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$HTML_PARAMS}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}">
<title>{$TITLE}</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery-1.8.2.min.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.2.7.js"></script>
    {*<script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.1.2.js"></script>*}
    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>
{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="{$MH_CATALOG_URL}mailhive/common/admin_application_plugins/common.css">

    {literal}
    <style type="text/css">
        td.pageHeading {
            padding-left: 10px;

        }

        #cfg_column {
            background-image: url("images/right_bg.gif");
        }
    </style>
    {/literal}
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
{$ADMIN_HEADER}
<!-- header_eof //-->


<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="{$ADMIN_BOX_WIDTH}" valign="top" height="100%" valign=top>
    <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
          {$ADMIN_COLUMN_LEFT}

              <!-- left_navigation_eof //-->
                        </table>
                      </td>
                      <td width="1" background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
                    </tr>
                  </table></td>
              <!-- body_text //-->


        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr class="dataTableHeadingRow">
                            <td class="dataTableHeadingContent">{$HEADING_TITLE}{if (defined('MAILBEEZ_MAILHIVE_MODE'))}
                                                            V. {$MAILBEEZ_VERSION}{/if}</td>
                          </tr>
                </table>


        {include file="main_common_content.tpl"}
        </td>
        <!-- body_text_eof //-->
    </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
{$ADMIN_FOOTER}
<!-- footer_eof //-->

{include file="main_common_update_reminder.tpl"}
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}
