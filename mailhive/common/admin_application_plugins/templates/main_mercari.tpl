{$MERCARI_METATAG}
<!--script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.1.2.js"></script-->
<script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

<link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
<link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/admin_application_plugins/common.css">

<script type="text/javascript">
    function rowOverEffect(object) {
        if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
    }

    function rowOutEffect(object) {
        if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
    }
</script>
</head>
<body>
{$ADMIN_HEADER}
<div id="wrapper">
    <table class="outerTable" cellpadding="0" cellspacing="0">
        <tr>
            <td class="columnLeft2" width="{$ADMIN_BOX_WIDTH}" valign="top">
                {$ADMIN_COLUMN_LEFT}
            </td>
            <td class="boxCenter" valign="top">
            	{include file="main_common_content.tpl"}
            </td>
        </tr>
    </table>
	{$ADMIN_FOOTER}
	{include file="main_common_update_reminder.tpl"}
</div>
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}
