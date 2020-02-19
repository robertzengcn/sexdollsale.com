<?php /* Smarty version 2.6.26, created on 2014-01-04 05:32:16
         compiled from mh:htmlTemplate */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET']; ?>
"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo $this->_tpl_vars['subject']; ?>
</title>
<!-- CSS is parsed into inline styles -->
<style type="text/css"><?php echo '
    /* Based on The MailChimp Reset INLINE: Yes. */
    /* Client-specific Styles */
#outlook a {
    padding: 0;
}

    /* Force Outlook to provide a "view in browser" menu link. */
body {
    width: 100% !important;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
    margin: 0;
    padding: 0;
}

    /* Prevent Webkit and Windows Mobile platforms from changing default font sizes.*/
.ExternalClass {
    width: 100%;
}

    /* Force Hotmail to display emails at full width */
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, . ExternalClass div {
    line-height: 100%;
}

    /* Forces Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
#backgroundTable {
    margin: 0;
    padding: 0;
    width: 100% !important;
    line-height: 100% !important;
}

    /* End reset */

    /* Some sensible defaults for images
  Bring inline: Yes. */
img {
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
}

a img {
    border: none;
}

    /* image fix for google */
img {
    display: block;
}

    /* Yahoo paragraph fix
  Bring inline: Yes. */
p {
    margin: 1em 0;
}

    /* Hotmail header color reset
  Bring inline: Yes. */
h1, h2, h3, h4, h5, h6 {
    color: black !important;
}

h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
    color: blue !important;
}

h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active {
    color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
}

h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
    color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
}

    /* Outlook 07, 10 Padding issue fix
  Bring inline: No.*/
table td {
    border-collapse: collapse;
}

    /* Remove spacing around Outlook 07, 10 tables
  Bring inline: Yes */
table {
    border-collapse: collapse;
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
}

    /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email and make sure to bring your styles inline.  Your link colors will be uniform across clients when brought inline.
  Bring inline: Yes. */
a {
    color: #3a6ea5;
}

a:visited {
    color: #3a6ea5;
}

a:active {
    color: #cc0000;
}

    /* custom design  */
body {
    background-color: #f0f0f0;
    color: #000000;
    font-size: 12px;
    font-family: "Arial", "Helvetica";
    background-image: url("[##$catalog_server##]mailhive/common/templates/images/greybgtexture.jpg");
}

#backgroundTable {
    /*background-color: #c0c0c0;*/
}

#emailTable {
    background-color: #c0c0c0;
}

a.social_link {
    font-family: "Arial", "Helvetica";
    font-weight: bold;
    font-size: 15px;
    text-decoration: none;
    color: #363636
}

div.footer {
    color: #898989;
    font-size: 12px;
    font-family: "Arial", "Helvetica";
}

table.emailBodyTable {
    background-color: #ffffff;
}

table.emailFooterTable {
    background-color: #ffffff;
}

td.emailBody {
    font-size: 12px;
    font-family: "Arial", "Helvetica";
    background-color: #ffffff;
    line-height: 16px !important;
}

td.h_divider {
    background-color: #c0c0c0;
}

    /* reset line-height for headings  */
h1, h2, h3, h4, h5, h6 {
    line-height: 1;
}


td.button {
  background: #f00;
  padding-top: 6px;
  padding-right: 10px;
  padding-bottom: 6px;
  padding-left: 10px;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  color: #fff;
  font-weight: bold;
  text-decoration: none;
  font-family: Helvetica, Arial, sans-serif;
  display: block;
}

td.button > a, td.button > * > a {
  color: #ffffff !important;
  font-size: 16px !important;
  font-weight: bold !important;
  font-family: Helvetica, Arial, sans-serif !important;
  text-decoration: none !important;
  line-height: 20px !important;
  width: 100% !important;
  display: inline-block  !important;
}


    /***************************************************
    ****************************************************
    MOBILE TARGETING
    These will not be parsed into inline css
    ****************************************************
    ***************************************************/

@media only screen and (max-device-width: 480px) {
    /* Part one of controlling phone number linking for mobile. */
    a[href^="tel"], a[href^="sms"] {
        text-decoration: none;
        color: blue; /* or whatever your want */
        pointer-events: none;
        cursor: default;
    }

    .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
        text-decoration: default;
        color: orange !important;
        pointer-events: auto;
        cursor: default;
    }

}

    /* More Specific Targeting */

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
    /* You guessed it, ipad (tablets, smaller screens, etc) */
    /* repeating for the ipad */
    a[href^="tel"], a[href^="sms"] {
        text-decoration: none;
        color: blue; /* or whatever your want */
        pointer-events: none;
        cursor: default;
    }

    .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
        text-decoration: default;
        color: orange !important;
        pointer-events: auto;
        cursor: default;
    }
}

@media only screen and (-webkit-min-device-pixel-ratio: 2) {
    /* Put your iPhone 4g styles in here */

}

    /* Android targeting */
@media only screen and (-webkit-device-pixel-ratio:.75) {
    /* Put CSS for low density (ldpi) Android layouts in here */
}

@media only screen and (-webkit-device-pixel-ratio:1) {
    /* Put CSS for medium density (mdpi) Android layouts in here */
}

@media only screen and (-webkit-device-pixel-ratio:1.5) {
    /* Put CSS for high density (hdpi) Android layouts in here */
}

    /* end Android targeting */

'; ?>
</style>

<!-- Targeting Windows Mobile -->
<!--[if IEMobile 7]>
<style type="text/css"><?php echo '

'; ?>
</style>
<![endif]-->

<!-- ***********************************************
****************************************************
END MOBILE TARGETING
****************************************************
************************************************ -->

<!--[if gte mso 9]>
<style><?php echo '
        /* Target Outlook 2007 and 2010 */
'; ?>
</style>
<![endif]-->
</head>
<body>


<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
    <tr>
        <td valign="top">
            <!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->
            <br/>


            <!-- start: you can remove this -->
            <br/>

            <?php if (! $this->_tpl_vars['MAILBEEZ_TEMPLATE_MANAGER_INSTALLED']): ?>
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
                    <tr valign="top">
                        <td>
                            <br/>
                            please adjust the templates to your needs, you find the templates:<br/>
                            template: [SHOPROOT]/mailhive/common/templates/email_html.tpl<br>
                            header picture: [SHOPROOT]/mailhive/common/images/default_emailheader.gif<br>
                            <br/>
                            Upgrade to the <b><a
                                        href="http://www.mailbeez.com/documentation/configbeez/config_tmplmngr/?a=<?php echo @MH_ID; ?>
"
                                        target="_blank">MailBeez Template Manager</a></b> to edit all your MailBeez Templates in
                            your MailBeez
                            Admin
                            <br/><br/>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            <!-- end: you can remove this -->
<!--
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
                <tr valign="top">
                    <td>
            <span rel="WEBVERSION_HIDE">
                <a href="<?php echo $this->_tpl_vars['webversion_url']; ?>
" target="_blank">view online</a>
            </span>
                    </td>
                </tr>
            </table>
-->
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
                <tr valign="top">
                    <td height="120"><a href="<?php echo $this->_tpl_vars['catalog_server']; ?>
"><img
                                    src="<?php echo $this->_tpl_vars['catalog_server']; ?>
mailhive/common/images/default_emailheader.gif" border="0" hspace="0"
                                    vspace="0"
                                    width="600" height="120"></a></td>
                </tr>
            </table>
            <table width="600" border="0" cellspacing="0" cellpadding="1" align="center" id="emailTable">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
                               class="emailBodyTable">
                            <tr valign="top">
                                <td width="15"><img src="<?php echo $this->_tpl_vars['blank_img']; ?>
" border="0" hspace="0" vspace="0" width="15"
                                                    height="0"></td>
                                <td class="emailBody"><?php echo $this->_tpl_vars['body']; ?>
</td>
                                <td width="15"><img src="<?php echo $this->_tpl_vars['blank_img']; ?>
" border="0" hspace="0" vspace="0" width="15"
                                                    height="0"></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="3" height="1" class="h_divider"><img src="<?php echo $this->_tpl_vars['blank_img']; ?>
" border="0"
                                                                                  hspace="0"
                                                                                  vspace="0" width="1" height="1"></td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="15" align="center"
                               class="emailFooterTable">
                            <tr valign="top">
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
                                        <tr>
                                            <td align="right" style="width: 100px;"><img
                                                        src="<?php echo $this->_tpl_vars['catalog_server']; ?>
mailhive/common/images/footer_fb.png"
                                                        border="0" hspace="0" vspace="0" width="32" height="32">
                                            </td>
                                            <td><b><a href="http://www.facebook.com/yoursite" class="social_link"> find
                                                        us on
                                                        facebook</a></b>

                                            </td>
                                            <td align="right"><b><a href="http://www.twitter.com/yourtweets"
                                                                    class="social_link">tweet
                                                        with us </a></b>
                                            </td>
                                            <td align="left" style="width: 100px;"><img
                                                        src="<?php echo $this->_tpl_vars['catalog_server']; ?>
mailhive/common/images/footer_tw.png"
                                                        border="0" hspace="0" vspace="0" width="32" height="32">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td align="center">
                                    <div class="footer">
                                        <b><?php echo $this->_tpl_vars['storename']; ?>
 | Street address | State /County | Country</b>
                                        <br/>
                                        Tax id: XXXXX |
                                        Company Registration Number: XXXXX |
                                        <!-- Working with telephone numbers (including sms prompts).  Use the "mobile" class to style appropriately in desktop clients
                                     versus mobile clients. -->
                                        Phone: <span class="mobile_link">123-456-7890</span>
                                        <br/>
                                        <br/>
                                        <hr width="490" size="1" style="color:#cccccc">
                                        <br/>

                                        You are receiving this email on <?php echo $this->_tpl_vars['email_address']; ?>
 because you subscribed through
                                        <br/>
                                        registering with <?php echo $this->_tpl_vars['storename']; ?>
<br/>
                                                                                If you feel that you have received this email in error, then please <a
                                                href="<?php echo $this->_tpl_vars['page_contact_us']; ?>
" style="color: #53535c">contact us</a>.
                                        <br/>
                                        <br/>
                                        <?php if ($this->_tpl_vars['noblock']): ?>
                                            You can not unsubscribe this notification
                                        <?php else: ?>
                                            <a href="<?php echo $this->_tpl_vars['block_url']; ?>
" style="color: #53535c">click to unsubscribe</a>
                                            <?php if ($this->_tpl_vars['block_all_url']): ?>
                                                                                            |
                                                <a href="<?php echo $this->_tpl_vars['block_all_url']; ?>
" style="color: #53535c">click to unsubscribe
                                                    ALL</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <br/>
                                </td>
                            </tr>
                        </table>

                        <!-- http://www.mailbeez.com/documentation/configbeez/config_email_rate/ -->
                        <!--
                        <?php echo $this->_tpl_vars['email_rating_footer']['html']; ?>

                        -->

                    </td>
                </tr>
            </table>


            <!-- if you remove this, the copyright footer will be added at the end and might destroy the layout -->
            <!-- to whitelabel your emails use: MailBeez Copyright Remover http://www.mailbeez.com/documentation/configbeez/config_copyright_remover/ -->
            [COPYRIGHT]
            <br/>
        </td>
    </tr>
</table>
<!-- End of wrapper table -->
</body>
</html>
<!--

-->