<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html {$HTML_PARAMS}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}"/>
    <title>{$TITLE}</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.1.2.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="{$MH_CATALOG_URL}mailhive/common/admin_application_plugins/common.css">

    <script type="text/javascript">
        {literal}
        function rowOverEffect(object) {
            if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
        }

        function rowOutEffect(object) {
            if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
        }
        {/literal}
    </script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
{$ADMIN_HEADER}
<!-- header_eof //-->
<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td class="boxCenter" width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <!-- body_text //-->
                    <td width="100%" valign="top">
                    {include file="main_common_content.tpl"}
                    </td>
                    <!-- body_text_eof //-->
                </tr>
            </table>
        </td>
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

