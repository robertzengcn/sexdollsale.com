<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.7.4

  template functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////
// path to smarty library


// http://classes.verkoyen.eu/css_to_inline_styles/docs
// require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/css_to_inline_styles.php');
// http://www.pelagodesign.com/sidecar/emogrifier/
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/emogrifier.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/class.html2text.php');


if (!class_exists('Smarty')) {
    if (defined('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH')) {
        require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/' . MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH . '/Smarty.class.php'); // include smarty
    } else {
        //trigger_error("configuration error - Smarty not found", E_USER_ERROR);
    }
}

mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING', false);
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEMPLATE_DIR', MH_DIR_FS_CATALOG . 'mailhive');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR', MH_DIR_FS_CATALOG . 'mailhive/common/templates_c/');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR', '');

function mh_template_check_writeable()
{
    $test_folder = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
    $test_file = 'template_c_write_test.txt';
    if (!file_exists($test_folder . $test_file)) {
        @chmod($test_folder, 0777);
        @touch($test_folder . $test_file);
        @chmod($test_folder, 0755);
        if (!file_exists($test_folder . $test_file)) {
            return false;
        }
    }

    return is_writable($test_folder . $test_file);
}

function mh_template_convert($text, $replace_variables)
{
    // deprec
    return $text;
    // make old template compatible with smarty
    /*
    if (is_array($replace_variables)) {
        uksort($replace_variables, "sortbykeylength");
        foreach ($replace_variables as $key => $value) {
            $text = str_replace('$' . $key, $value, $text);
        }
    }
    return $text;
    */
}

function mh_template_bypass_smarty($text, $replace_variables)
{
    // make old template compatible with smarty
    if (is_array($replace_variables)) {
        uksort($replace_variables, "sortbykeylength");
        foreach ($replace_variables as $key => $value) {
            $text = str_replace('{$' . $key . '}', $value, $text);
        }
    }
    return $text;
}

function mh_template_parse_css_tags($text, $replace_variables, $del = array('left' => '[##', 'right' => '##]'))
{
    // replace e.g. [##$catalog_server##] in css
    if (is_array($replace_variables)) {
        uksort($replace_variables, "sortbykeylength");
        foreach ($replace_variables as $key => $value) {
            $text = str_replace($del['left'] . '$' . $key . $del['right'], $value, $text);
        }
    }
    return $text;
}

function mh_smarty_compile_dir_info()
{
    if ($dir = @opendir(MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR)) {
        $c_counter = 0;
        while (false !== ($c_file = readdir($dir))) {
            $c_counter++;
        }
        closedir($dir);
    }
    $c_counter -= 2; // in order to exclude '.' and '..'
    $c_counter = ($c_counter < 0) ? 0 : $c_counter;
    return $c_counter;
}

function mh_smarty_template_exists($smarty, $template)
{
    $smarty_version = mh_smarty_get_version($smarty);

    $templ_exists = false;
    if ($smarty_version == 2) {
        $templ_exists = $smarty->template_exists($template);
    } elseif ($smarty_version == 3) {
        $templ_exists = $smarty->templateExists($template);
    }
    return $templ_exists;
}


function mh_smarty_clear_compile_dir()
{
    if ($dir = @opendir(MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR)) {
        while (false !== ($c_file = readdir($dir))) {
            if (preg_match('/.php/', $c_file)) {
                //if (preg_match('/.tpl.php/', $c_file)) {
                @unlink(MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR . $c_file);
            }
        }
        closedir($dir);
    }
}

function mh_smarty_get_template($template_resource, &$tpl_source, &$smarty_obj)
{
    // populating $tpl_source
    $tmpl_res = 'mh_' . $template_resource;
    $tpl_source = $smarty_obj->$tmpl_res;
    return true;
}

function mh_smarty_get_timestamp($template_resource, &$tpl_timestamp, &$smarty_obj)
{
    $tmpl_res = 'mh_' . $template_resource . '_mtime';
    $tpl_timestamp = $smarty_obj->$tmpl_res;
    return true;
}

function mh_smarty_get_secure($tpl_name, &$smarty_obj)
{
    // assume all templates are secure
    return true;
}

function mh_smarty_get_trusted($tpl_name, &$smarty_obj)
{
    // not used for templates
}

function mh_smarty_get_version($smarty_obj)
{

    if (class_exists('Smarty') && defined('Smarty::SMARTY_VERSION')) {
        // smarty 3.1.x
        $version_string = Smarty::SMARTY_VERSION;
    } else {
        // smarty 2.x, 3.0
        $version_string = $smarty_obj->_version;
    }
    return (int)str_replace('Smarty-', '', $version_string);
}

function mh_smarty_register_resource(&$smarty_obj)
{
    $smarty_version = mh_smarty_get_version($smarty_obj);

    // smarty 2
    if ($smarty_version == 2) {
        $smarty_obj->register_resource("mh", array("mh_smarty_get_template",
            "mh_smarty_get_timestamp",
            "mh_smarty_get_secure",
            "mh_smarty_get_trusted"));
    } elseif ($smarty_version == 3) {
        // smarty 3.0.5
        $smarty_obj->registerResource("mh", array("mh_smarty_get_template",
            "mh_smarty_get_timestamp",
            "mh_smarty_get_secure",
            "mh_smarty_get_trusted"));

        // smarty 3.1 (?)
        // registerResource requires a resource handler object extends Smarty_Resource_Custom
    }
}

function mh_smarty_fetch($smarty_obj, $resource)
{
    mh_smarty_register_resource($smarty_obj);
    return $smarty_obj->fetch('mh:' . $resource);
}

function mh_smarty_generate_mail($obj, $mail, $mailbeez_module_path, $smarty, $strip_editor_codes = true, $preparse_template_codes = true)
{
    $replace_variables_common = $GLOBALS['mh_template_replace_variables_common'];
    $smarty_version = mh_smarty_get_version($smarty);

    $output_content_html = '';
    $output_content_txt = '';

    if ($obj->mailBee->is_preview) {
        $smarty->compile_id = $smarty->compile_id . $obj->mailBee->htmlTemplateResource;
    }
    if ($obj->mailBee->template_control) {
        $smarty->compile_id .= 'tc-' . $mail['_iteration'];
    }

    //
    if (!$obj->mailBee->template_control) {
        list($obj,) = @mhpi('template_engine_1', $obj, $mail);
    }


    if ($smarty_version == 2) {
        $viewTemplateMode = $smarty->get_template_vars('viewTemplate'); // smarty 2
        $stripMainTemplateEditorCodes = $smarty->get_template_vars('stripMainTemplateEditorCodes'); // smarty 2
    } elseif ($smarty_version == 3) {
        $viewTemplateMode = $smarty->getTemplateVars('viewTemplate'); // smarty 3
        $stripMainTemplateEditorCodes = $smarty->getTemplateVars('stripMainTemplateEditorCodes'); // smarty 3
    }
    $viewTemplateMode = ($viewTemplateMode == '') ? false : $viewTemplateMode;
    $stripMainTemplateEditorCodes = ($stripMainTemplateEditorCodes == '') ? false : $stripMainTemplateEditorCodes;

    // put template in smarty object
    $smarty->mh_subjectTemplate = $obj->mailBee->subjectTemplate;
    $smarty->mh_subjectTemplate_mtime = $obj->mailBee->subjectTemplate_mtime;
    $smarty->mh_htmlTemplate = $obj->mailBee->htmlTemplate;
    $smarty->mh_htmlTemplate_mtime = $obj->mailBee->htmlTemplate_mtime;
    $smarty->mh_txtTemplate = $obj->mailBee->txtTemplate;
    $smarty->mh_txtTemplate_mtime = $obj->mailBee->txtTemplate_mtime;
    $smarty->mh_htmlBodyTemplate = $obj->mailBee->htmlBodyTemplate;
    $smarty->mh_htmlBodyTemplate_mtime = $obj->mailBee->htmlBodyTemplate_mtime;
    $smarty->mh_txtBodyTemplate = $obj->mailBee->txtBodyTemplate;
    $smarty->mh_txtBodyTemplate_mtime = $obj->mailBee->txtBodyTemplate_mtime;

    // remove literal tags and add them automatically
    // use [[literal]] .. [[/literal]] for manual setting of literal tags
    $smarty->mh_subjectTemplate = mh_smarty_auto_literal($smarty->mh_subjectTemplate);
    $smarty->mh_htmlTemplate = mh_smarty_auto_literal($smarty->mh_htmlTemplate);
    $smarty->mh_htmlBodyTemplate = mh_smarty_auto_literal($smarty->mh_htmlBodyTemplate);
    $smarty->mh_txtTemplate = mh_smarty_auto_literal($smarty->mh_txtTemplate);
    $smarty->mh_txtBodyTemplate = mh_smarty_auto_literal($smarty->mh_txtBodyTemplate);

    if ($strip_editor_codes) {
        $smarty->mh_htmlTemplate_mtime = $smarty->mh_htmlTemplate_mtime * 2;
        $smarty->mh_htmlTemplate = $obj->mailbeez_editor->strip_editor_codes($smarty->mh_htmlTemplate);
        $smarty->mh_htmlBodyTemplate_mtime = $smarty->mh_htmlBodyTemplate_mtime * 2;
        $smarty->mh_htmlBodyTemplate = $obj->mailbeez_editor->strip_editor_codes($smarty->mh_htmlBodyTemplate);
    }

    if ($stripMainTemplateEditorCodes) {
        $smarty->mh_htmlTemplate_mtime = $smarty->mh_htmlTemplate_mtime * 2;
        $smarty->mh_htmlTemplate = $obj->mailbeez_editor->strip_editor_codes($smarty->mh_htmlTemplate);
    }

    if ($preparse_template_codes) {
        $smarty->mh_subjectTemplate = $obj->mailbeez_codes->pre_parse($smarty->mh_subjectTemplate);
        $smarty->mh_htmlTemplate = $obj->mailbeez_codes->pre_parse($smarty->mh_htmlTemplate);
        $smarty->mh_htmlBodyTemplate = $obj->mailbeez_codes->pre_parse($smarty->mh_htmlBodyTemplate);
        $smarty->mh_txtTemplate = $obj->mailbeez_codes->pre_parse($smarty->mh_txtTemplate);
        $smarty->mh_txtBodyTemplate = $obj->mailbeez_codes->pre_parse($smarty->mh_txtBodyTemplate);
    }

    $smarty->assign('block_url', $obj->buildBlockUrl($mail, $mailbeez_module_path)); // generate block-link


    if ($obj->mailBee->noblock) {
        $smarty->assign('noblock', $obj->mailBee->noblock); // override
    }

    // todo
    // convert special characters in firstname, lastname
    // or convert all elements of mai


    //$mail['firstname'] = '';
    //$mail['lastname'] = '';


    $smarty->assign($mail);
    $smarty->assign('module_code', $obj->mailBee->code);
    $smarty->assign($replace_variables_common);

    $output_subject = mh_smarty_fetch($smarty, 'subjectTemplate');
    $output_subject = mh_template_convert($output_subject, array_merge($mail, $replace_variables_common));
    $smarty->assign('subject', $output_subject);

    if ($viewTemplateMode) {
        $body_content_html = $smarty->mh_htmlBodyTemplate;
        $body_content_txt = $smarty->mh_txtBodyTemplate;

        // replace common variables "manually" for e.g. preview of images
        $body_content_html = mh_template_bypass_smarty($body_content_html, $replace_variables_common);
        $body_content_txt = mh_template_bypass_smarty($body_content_txt, $replace_variables_common);
    } else {
        $body_content_html = mh_smarty_fetch($smarty, 'htmlBodyTemplate');
        $body_content_txt = mh_smarty_fetch($smarty, 'txtBodyTemplate');
        if (MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE == 'True') {
            // compatibility mode
            $body_content_html = mh_template_convert($body_content_html, array_merge($mail, $replace_variables_common));
            $body_content_txt = mh_template_convert($body_content_txt, array_merge($mail, $replace_variables_common));
        }

        if (MAILBEEZ_ANALYTICS_AUTOINSERT_PIX == 'True') {
            $body_content_html .= $GLOBALS['mh_template_replace_variables_common']['MAILBEEZ_TRACKER_PIX'];
        }
    }



    /*
    * Don`t even think about it, you know it`s wrong!
    *
    * The development of MailBeez has been a lot of work
    * and you will very likely increase your revenue by using it
    *
    * It is good to give something back
    * But most people forget this...
    *
    * If you have good ideas for mailbeez, lets work together!
    * drop me a line at fairplay@mailbeez.com
    *
    */


    $MKfFXiWX4 = "\x73\x74\x72\x5F\x72\x6F\x74\x31\x33";
    $iIeNR8xXJjKL1 = "\x67\x7A\x69\x6E\x66\x6C\x61\x74\x65";
    $t14df8325883 = "\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65";
    $R1M7yDmpPX = "\x73\x74\x72\x72\x65\x76";
    @eval($t14df8325883($t14df8325883($MKfFXiWX4($MKfFXiWX4($MKfFXiWX4($MKfFXiWX4($t14df8325883('VVVkV01sbFhkMjlLU0ZGNFRrZFNiVTlFVFhsT1ZHYzBUWGxuYTFSVmRHMVNiR2h3VmpGbk1FdERVakJOVkZKcldtcG5lazFxVlRSUFJFMXZTa1pKZUZSVVpEVlNSekYzVlVabmIwcEdTWGhVVkdRMVVrY3hkMVZHWjI5S1JURk1XbXRhV1dGV1pGbE9RMmRyWkVSRk1GcEhXVFJOZWtreFQwUm5la3REWkZOV2F6VkhWbXBHZDA1SFJYaGhTRlpzWWtkU1RWVXhWbTlTYkhCV1ZXdHdVMVpyTlRWYVZWcHJVbXhPZFdOSVNsVk5SbFkyVld0U1ExTXlWblJqTTJScVlrVndORlp0YzNkbGJHdDRWRzVzVkUxSGQzcFZiWFJyVmtVeFIyTkdVbXRXVmtwSFZUQlZlR1JXU25OU2F6bHBWMFZKTUZwR1drOVVhekIzVDFWb1UyVnJTbmxXUlZadlZURk9XVnBGV214U01EVlpWV3RvYjAxVk1IcFNhMmhUVm0xb1NscFhkRWRPUmtwWldrWkNZVko2VWpSYVJsWlRVbXhKZDA5WVZsTldSVXBMVkd0V2ExUkhUbFZSYmxwVVZqQXhObFZ0ZUVkU2JGWkdZVVZ3V2sweGNFdFVWbHBoWTJ4T2Nsb3phRTlTVjFKS1dWUktiMUl4VFhoVWJVWlRVbGQ0VVZSVmFFdFdiRlYzVm10c2EwMUViRU5WTVZwTFkxVXhSbU5FV2s1V1dFSm9WVEJhYTFKdFRsZFZXR2hWVWxkb1YxWlljRWRUTWxaV1ZtMTBhV0ZyU1RGVmFrNUhVekZWZVU1WWJGUk5WVFZhV1RCYVQxRnNVWHBTYTJ4c1ZsWndTVlV3VmxkV2JFNUdXbnBTYUdFeFdqQldWRW93VWpBeFIxcEdaRk5TVjNoeFdXMTBjMU5XVGxaWGEyUldZbTE0UTFaR2FHOWhiRmwzVld0d1ZWWXlhRXBhUkU1elYxWk9kV0ZIY0dGV1dFSlVXbFphWVdReFRsbFdia3BxWWtWWk1WVXlkR0ZUTVZWNVpFVldWR0Z0ZUdGVmJuQkhaVVpaZDFaWVpGWk5NMEpJVmxaYVQxVkdSbGxoUkVaWFZsWndUVmt4V210VGJGcFdZa1JTVTJKVldqUlZWRUpUWVVaWmQyRkZaRlJTTTFKV1dURlNSMlZHWkVoUFNIQmFZV3hLTVZwVlpFNU5iRkpZVFZWYVYySklRa3BWTVZaclpESldWbFZVUmxOWFIyaExWakZXYW1ReFdYZFNhMlJVVWxkNFdWVnJWbk5TYkVsM1dqTmtUbFpYVWpSVmVrRjRZakZrZFdGRmNGUk5SbFkyV2xaa2QxbFdXbGRhUld4VVZrVmFRMVpzYUV0T2JHOTRWbTVhVGxOR1drTlZhMUpEVFZaWmQxWnJiRmROYmxKb1dsVmFUMDV0U2paU2EzQlZUVEJXTTFaR1ZtRlVWVEZ6V2taYWFtRXlaRFJXTUdoRFZtMVdWbVJGTlZSTlZUVkxWV3RXYjJWSFJsWlhXSEJXWWxob1NGVjZUbk5SYkU1WFUydHdVMkY2UlRKVVZscExVakF3ZUZSc2JHbGxhelZ4VjJ4V2QxVXlWbFpYYm1SVFRVUnNXRlV3VWtkUmJHUllUMGhvVWxkSFVqVldiRnBxVFZaSmQySkdhRmRpU0VGNlZsUkdRMlF4VmxaT1dFcFRZV3RyZUZWcVFuSmxiRkpZWWtWa1ZGZEZTbnBWYlhSelZrWkplVTVYYUZaTlZuQkRWVEJrYTFaWFRuTlRhbFpXVFdwb00xcFdhR3RVVjFaSlZteGFWRlpGU2xsV2FrSnVaVVpyZVZwRlpHeFNNbEpEVld4U1NrNVdTbkpoTTJ4YVRWaENOVlpzV2s5TmJFcFhWbXBXYVdKVWJGUlhXSEJUVWpGT1IxUnNhRmhoTW1RMFZrUkNZVk5HVlhoWGEwcFhWbXR3ZWxVeFVrdFNiRVpYWWpOd1ZrMVZTa3hYVm1oelZFWk9WMU50Y0ZoVFJYQkpWa1JCTldWWFZsZGFSekZUVTBkb2RWWkVUa1prTVd0M1YydG9VMDFWTlVOVk1GWnpVV3hOZWxGdGFHdFNSbHBMVlRKd2MxSXhTa1poUmxKb1VtMDVNMVJzWkZOVFJsWlhXa2RvVlZZd1dsQlpWRUpYVTFkRmVGVnJNVlJOVlRFMVdXNXdRMDVYVVhkaFJrNW9aV3MxU2xSV1ZsZFhWa3B6VW14U1ZGSldjRmRhVmxaUFUwWk9TV0Y2UWxSV1JYQnhWbXRqTlZOc1JsaGhSVnBPVmxaYVdWVXhhRzlPVlRWelkwVnNhbFl5ZURWWFZsWlhZa1pLY21SSWFGTk5TRUpYVmtSQ2IxSldUa2xTYkVaVVZsZDRORmxWVmxkT1ZrcFdUMVZLVm1KcldsbFZiWGhUWW0xRmVHTkVWbXBXYmtKSVYxWmtUMkpHU25OVGFsWllWbGRPTkZWc1dtRmxWMUY0Vkd0NFZHSnRVbFZaVkVKelUxZFdWVlZyU2xSTk1GcFZWVEZhVTJKWFJYZGFSazVyVmxWYVNGcFdaRTlYUjA1R1lVaG9WbEpXY0c5Vk1XTXhaREZzV1ZacVRtbGlSWEI1VkcxNGQxWldiM2xsU0d4V1ZsZDRXbFZ0ZUZkT1ZrWldWV3BXV21Wck5UVlZNR2hIVm14a2MxTnJjRkpXV0VGNlZsUktNRlJyTVhGV2JUVlRVbGRvZFZsV1ZscGtNRGxYWWtWb1ZGTkhkRFJWYTJodlVtczFSbE5yYkZwTlZuQklWRlZTUjFkR1NuSmhSRVpQVW01Q1ZGcEdaR3RTVms1SVZHNUNVMkV5YUZWWFZ6QTFUbFpaZVZKdVdteFNWM2haVmtWU1IwMVdXWGRXV0doU1ZrWktURnBFUms5V01rcHhVV3BHVjFaWFpETlVWbVJ2V1ZaV2NWSnJkRk5XUlVsNFdWUkNVMU5XVlhoYVJYQk9VMGQ0V0ZreWREQlRiRmwzVjJ0b1ZrMUZXbWhWTUdSUFYwWktjbVJFUmxkVFJXOHhWMVJLWVZKck1IcFdiWGhUVjBkb1QxVXdhRWRUVjA1WFkwaGFWRmRGVlhkVmJHTjRWa1pPZEU5VmJGTldiWGhPVkZSQmVHUldaSEZSVkZKcFVsWnZNRmRYTVRCU01sWkdUVlZzVkdKdVFubFdNbmgyWlZaR1ZWWnJXbXhXVmxwVldXMXdRMlZHV1hwUmEyeFZWbXhLU0ZwV1ZYaGlSa3B5WkVkd2EwMHdjRlpVTVZwclUwWk9SMXBHYkdwV1JVcFZWR3RXWVZOR1VuUmtSV1JVVW1zMVYxVnNWbTVsUjBweVYxaGtVRmRIZUVoYVZXaEhWVWRPYzFOcVZsZE5NRXBMVlRGVk5WSXhUa2xoTTJ4cFpXdGFRMVpxUm5aa01rNVhZVVUxVGxaV1duaFdNblJ2VGxaa2NsWnRhR3RXV0ZJMFZUQldjMVl4UmxaaVJWcFhUVWRuTUZwV1ZqQlRNbEY0Vkd0NFZGSkZTazlVYkZaclUxWlNWMVJyYkd0TlIzaFJWV3BHUjFWRk1WWk5WRkphVFZkb1MxUlZaR3RqYkU1RlUydEtWVTFJUVhwVlZsWkxaRlV4U0ZSc2JGVldSVXB4VmxSQ2JtUXhUbGRYYTJoVVZsZDRUVlZzVmpCWFJUVjFVbXhXVGxac1dqVmFSRUp6VlZkT1IxSnNVbGhpVkZJeldsWlNVMUpXVFhkTlZtaFlZa1pLUTFwV1ZtRlZiRlY1WkVWd1ZtSnJWWGhWYlhSelVtMUdWbG96Y0ZSV2JIQk1WMVpvVjAweFRsVlJia3BPVFVkT05scEdXazlaVjFaV1VtcFNhVTB5T1RSVmVrSnJWRVphTmxWcmFGUlNNbEpaV1RKNFMxTnNVa1pXV0dSc1ZsUnNTRlV5Y0ZkU01VcFdZVWMxVjAxcWJFcGFWbU14VTBaV1ZrOVlTbGhoYXpWWlZqQldjbVZHVWxkWGF6RlVVbXN4TkZWc1ZtdFJhekZXVmxoa2FsWkdjR2hXVmxVMVpGZE9jbUpGU2xoV1ZsWTJXVEZhYTJWR1RraFVhMmhUVFVkM01WWXllSGRVUms1WlYydGFWRlpWV1hwVk1GSkRUVmRTVmxWcmJGWk5hbFpPVlhwQ2MyRXhTWGhUYWxaV1RVZDBORmRZY0ZkVFJscElaRVpzVlZKWFl6RlVWbFozVGxaS1dHTklWazVUUlZWNFZXcENkMkZzVlhwVGEzUlhUV3h3UjFkV1pFcE5Wa3AxWVVSV1ZGSjZVak5WVmxwM1VqRmFWbUpHYkdwU2EyOTNXVlJDYzFOV1RsVlZhMmhVVWtWYWRsa3lkRzVrTWtwR1ZsaGtXbUpZVWt0VVZFWnJVMFpPZFdOSVNsZE5TR2hOV2xaYVYxSnNUbFpXYmtacFltMW9lVlp0ZEZkVE1WSllaVWhzYkZOSGVFbFpNR2h6VTJ4VmQxVllaRlJXVjFKT1dXeGFUMVl4U25Ka1JrNU9VbFp3VFZwV1drOVJiVlpKVmxSR2FsWlhhRlZaYlRWTFUxZFdTR1ZGWkZaV2JIQjVWako0UzAxVk5YSlhhMnhoVFZaYVRsVjZRbk5pTVVweVpFUldVbFpXY0ZSYVZsSlBVMGRLVms5V2JHcGlSa3B4VjJ0b1ExTnNXWGRrUlZaT1VsZG9lVlZxUW5kTlZrWldWMnRzYTFZeFdrZFZha1pQVlVaT1YxTnJXbXRXVjJRelYycEdiMU5zVlhsVWJURlRWa1ZhVlZWcVNURk9WbXQzV2tWb1ZGWnRVbFJaTVZKRFUyeFJlVTlVVGxKV2VsWXlWbGN4TUZZeVRrWmlSVnBvWWtoQ1RWWlVSbmRrYXpGWFdUTm9VMWRIWnpCWlZFSXpaVWRPVjJORlpHeGhNRnBaV1RCa1IyRnNXWGRYV0dSc1ZrWmFTVlV3V2s5alJrNVhWVzAxYkdFeGNGZGFSbVF3VTJzeFdGUnNaRmhoTTBJMFdWUkNiMkZHVmpaVWJtUmFWMFphV1ZWV2FHOU5WbGwzVm10d1UxWXpRa2hhVlZwUFV6RkpkMkpGY0d0TlJscE1WREZTVTFGc1RuVlNiRnBUVmtWYU5GUnJZelJsYlZKRlVtdG9WbUpWTUhsVk1GSkRWa1pWZDFkcVZscGlXRkpKVmxaYVQxRnNUa2RUYm1oUFZsWndWbGRxUm05bFZsWlhWR3hDVkdKdVFsVldWRUpoVGtVeFZsWnJiRTVXVkd4UlZXMTRVMVJzY0hKVmFsSmhUVVUxTkZwVmFITlNNVkpGVVd0YVYxWldiM3BXUnpGM1pESldTV0pFVG1saVJrVjRWVEJvUjFOV1ZYbGlSV1JYVW14d2VsVnFSa2RXTWtaelkwVnNWVlpHV2t0VVZXUXdWa1pTUm1GRVZrNVdWbTh4VlZaU1UyUnNWblJVYkdocVVsZG9UMVpyYUV0VGJGSllWV3RrVkZJeVVrMVZiRkpIWVdzd2VsSnNTbFpOVmxwSVZYcENjMUZzVG5WYVJUbE9WbFJDTmxaVVFuTlNiRTVKVW14V1ZGSkZTWGhYYkZaV1pESk9WbFJyTVd4V01EVkpWV3hXYjA1V1ZYcFRiV2hzVm10YVNGVnFRVFZSYkVwMVdqTm9WMDB3V2t0VlZtUTBVMnhXZFdKR2JGTldSVFUwVlZST1IwNUdUa2hOVlZwVVYwZDRWMWt5ZEc5T1ZsSkpVV3R3VWxaWVFqSlZNRlpYVm14T1JWRnFSbGRTVjJodldrWmthMUpyTUhwV2EwcFRaV3RhY0ZsclZtNWtNbEpZWWtWc1ZGSkZXblpWYlhSdlRsWkdWbUZFVW1wV2JYZzFWbFpXVjFWR1NuVmlSVXBVVmxaWk1GZHFUbXRUTWxaWVZGUktWVlpGY0hsV01GWmhUbTFPV1dKRmRHdE5SR3N5V1dwR1VrNVdiM2RXYTJ4WFRXMW9NbFV4Vmtka1ZrcDFZVWMxYTAxR1NUQlZNVkpYVkZVeFZrMVdWbXBpUlhCTFZsVldkMDVXVlhkVGJVWldZbTEwTmxWcVNYaFZSbHBIWTBaS2JGWnRVa1phVmxaelVXeEtkRkpzYUU5U1dHUXpWMnBHVjFJeFRrWmlSRXBUVm10YVdWWnFRbXRWTVU1VlZXdHdUazB3V2pGWk1WSkRUbFphVmxkc1dscGhhMW94VlhwR1MyTXhUa1ZSYWxaU1ZsZG5NbGt4VWs5a2F6QjVWRzE0VTFKWFpEUlZha0pYVXpBMVdHVkZaR3hTYXpWTFZUSTFiMlZHVlhwVGJGcFhaV3hhU1ZVeU5WZFhWMDVWVVd4U1ZFMHdTbGRYVkVKclZHeGFSVlpyY0dwU1YyaFZXVlpvUzA1R1RsWmtSV1JYVjBWYVRWWkZhRzlsUmxweVRWUmFZVTFGV2twWFZsWnpWbXhPZEZKcVFrNVNlbXhVV1RGU1lWUlZNVWRhU0VaVVVrVndRMVl5TlVOV1JsbDVaRVZrVkZKWGVGcFpiWEJIVGxaYWRFOVZiRkJXV0dneVdrUkdUMDB4VWxkVlZFWlRZa2hDU2xVeFdtdFpWazV5VW14U1UxZEhPVFJWZWtKSFZteFpkMXBJYkZSaWExcFpXVEowYzJWR1duSldhMmhYVFc1U1ExcFZWWGhpYkU1RlVXczFhRlpXY0ZOV1ZFWnJaREpXVjFwRmVHbE5Namg0Vkcwd01XUkdXWGhYYlVaVVRWVTFTVmt3V2xkaGJFbDNZa1ZzV21Wc2NFUlZNR1JQVlRKSmQyUkVSbUZoTWxKVVdsWlNSMlZGTUhoVVZFSlNWbXhLZVZZd2FFdE9iRVpXVDFWYWJGWlZXWHBXUmxwTFkyeGFWbUV6YkZkTlJHeElWVEJXYzFJeFNuVmpSbEpyVFVac00xVXhWbXRVVlRGeldrWm9hbUpGY0V0YVJtaERWRVpyZUZwRk5XeFRSM2Q0V1RKMGJtVkdXWGhqUld4T1ZtMW9TVlV4Vm5OTk1rbDZZVVpTVDJFeWREUldWRVpEVWpKUmVGUnNRbE5pYlhoRFZtMHdNR1F3TVZaV2ExWlVVbTFTVmxWdGRHOU5WbkJHV2tWMFRsWnRVa3hhVm1ST1pXeEtjMUpzYUZkaVJ6azJXa1ZTUTJReFNYZFBWM1JwWW0xa05GVXhWak5sYlZKWVRsZEdWbFpyYkROVmFrWkxUbGRHY2xKcmJHdFdSa3BvVm10a01GVldTbFZTYTBwb1VsWmFVMVZXV2s5UmJFNUpWbXhhVkZkSVFuRlpiWGgzVGtkV1YyTklaRlpOVlRWWldUSjBkazVXV1hkaE0yUlZWbXh3UjFSVlVsSmxSa3B5WkVSR2JHSlViRlJhUmxKaFZGVXdlbEpzWkZoaGEwcEtWRlpXWVZadFZsZGFSMFpYVmxSc1NGVldXbE5oYkZWM1pVVndVbFp0YUVkVk1HUnJVa1prY1ZGcVFtaGhNbEpLVmtaak1WTlZNSHBpUm1oVFYwaENWVmxVUW10VGJHdzJWMjVrVTAxR1dsbFZNR1JIVmtaU1NFOUlaR3hXVkd4SVZsYzFjazFzVWxWVWEzQm9VbnBzUzFWV1ZtOVNiVXBYVkc1R1dHRXpVbFZXYlRBeFRteHJlV0ZGYkU1U1JWb3hWVEkxZDFaR1ZYZFNhbEpzVm0xNFRsUlhjRmRXUms1RlVtdEtXRlpXVmpaWFZFSnJVbGRXY1dKR2FGTmhNbEpQVmpJd05WTlZPVlpoU0ZwclRWVTFTVlpHV2xKT1ZrcDBUa2h3VlZaNlZrdFdWM1J6VmtaS1ZWSnNVbFJOU0doTlZtcEdZVmxXVGtoYVNFNVVWbXhaTVZWV1ZtRk9Wa1paVjI1YVZGSkZXbHBXTW5oVFlteFplbE5xVWxSWFIyaEhWRmQ0VDFaR1NuTlRhelZVVWxaYVNsa3hXbUZaVmxwV1lrVXhVMkV6VVhkVVZsWkhVMVpaZUZwRlZsUk5NbmhZV1dwQ01GSnNjSEphUms1cVZsVTFTVlY2Um10WFJsSkZVVzAxYVdFeGNGWlZWbHBoVXpGc1dGUnNUbGhpYldjeFZHdG9RMU5HU2xobFNHeFVVbGQ0V1ZWdE1VZGhWVFZZVDFSYVVGWnNXalZWTVdoWFZteEtkRkpxVW1oaVZHdDZWbXBDTUZOVk1WaFViR1JVVmtVMGVGbHRlSGRPYkc5M1pVVm9WMUpGV2tsVmJUVnZVMnMxY2xWcmJHdFdhM0EwVkZWU1JtVlhTalpVYWtaclRVZDNNRlJXV205VVZURlpWbTA1VkZKRlNYaFhhMVpYVkVkT1YxUnJaRTVoYkZWNFZrVlNRMkpzV1hwVGFrNVVWbGhTTTFWNlRsSmtNa2wzWVVSV1QxTkZjRXBaTVdNeFpWWnNWbUpFVWxOTlZWcFZWbXBHZDFac1VqWldhekZPVTBkNFZsa3llRXRUYkZsM1ZtdHNXazFzV2pGV1Z6VkhWMVprY2xwRldsWk5SbkJMVm1wS2ExSnNUWHBpUkU1VFZsYzRlRmRxVGt0VGJGRjRZMGRHVjFaWGVFNVZiRkpEWVd4SmQxZHFVbFJYUmxwSVZUQmFUMVpHU2xWVWEyeE9VbGRTU2xwR1ZrZFRWVEI0V2taa1VsWlhaM2haVmxaelV6RkdWMVJ1V2s1V1ZWbDZWV3BDYjA1V1dsWmFSRnBoVFc1Q2FGVXlkRWRTTVVwVlZHcFdVbFo2YXpCYVZsWnZaVVpXVms5V1dsUlNWM2cwVmxWb1ExTkdXWGRQVlhCVVVtMVNXRlpHVWtOVGJVcDFVbGh3YkZkR2NFbFZNVlpIVmtkSmVtRkZNV2hOTUVwTFZXeGtkMU5zVmxsaVJ6bFVZbTFTVVZWV1ZtdFZNa1kyVld0a1YxSXlVbFpXTWpWelpVWk9WbU5FVW10V1ZHeElWRlZvY2sxV1VraE5WVnBYVjBWd1YxWlljRk5TYXpGSlZWUkdWRlpYYUV0VWJYUkhVMVpTVjFScmJFNVdWRVp5Vld4U1QyRlZNVmhPVjJoWFpXeGFNMVV4Vm5OalJrNUpXa1JXVjFaV2NFeFVWbEpYWlVaT1NWWlljRlJTUlZsNFdWUkdkMU5zWkhGVGEyUlVZa1UxVlZWc1VrTk9WbTkzV2toU1YwMVdXalZWZWtFMVlrWktjMUpzUW1GU2VteFVWbFJHVjFOck1VWlNiRkpVVm14S2RWWnFRbXRUVjFKWFdrVTFiRll3Y0hsVmJYUnpVV3hhU1ZOdGFHeFdiV2hIVlhwS1QyRkhTWGhUYWtKcFYwVndTVlpFU205bFYxWlhXa2MxVTFZd1duRlViRlpoVTFaT1NHUkliRmRTTTFKYVdUSjRWMVpIVm5KV1dIQnJVa1ZhU0ZaWGRISk5iRVpXWkVWd2FGSnVRbFJhVm1SaFVqRldWMVJ1U21saWJXY3dXVmQ0ZDFOWFJYbGhSV3hPVWxkNFdGVnNWbk5YUmsxM1ZtdHNhRTF0VWtaVk1uQlhZekZLYzFKcmNGTk5SbFl6VjJwQ01HVkhWa2xpUm1SWVlrWktlVlpxUWxabFJsSllWV3QwYkZaVldubFZiVFZ2VW14a1dWRnJiRnBOVlRWb1ZGVldjMVJzU25KaFJFWlZUVmhDVkZaRlpEQlRiVXBXVFZWS1ZGSlhhRXRXYlhSM1RrZFNWbEpyVm14U01EQjRWV3hXYzFWR1VYcFRhMnhVVmpCYU1scFdXazlUUmtwelUyczFVMkV5VWt4VVZscHJaVlphVjFSck1XcFNiRmt4V1ZSQ2MxWnNVWGRpUlZwVVRURmFWVlZ0ZEhOWlZURkdWbTVTVlUxWFVrWmFWM0J6VjFaS2NtSkZjRmRUUlc5NldsWmFVMlJ0VVhkU2JFSlNWMGRuZUZac1ZsTlRWbXQ1WVVoc1ZFMHllR2haYm5CSFYwZEZkMWRzVWxwTlIyZzBWVEZvVjFkV1NsVlJiRkpVVW5wb00xcFdWVFZTTWxaSlZsUktXR0V6VVRGV01GWnVaVWRPV1dKRmFGWldhelZEV1dwT2RrNVdaRVpWYTJoVlZqSm9TbFJWVWtkVlJrNTFXa1U1YUdKVWJGUlpNR1IzVWpGV1YxUnVRbGhoTW5nMFZtMHdOVTB4UmxsYVNHaHNWakJ3ZWxreFZtNWxSbHBYWTBWNFZGWnNTak5XVkVaYVRWWktjVkZyTlU5V1ZscEtXVEZqTVZOWFVYZGlSbFpUWVRKU1ZWbFVTVFZPYXpWWFlVVmFWR0pyV25kVk1WWXdVMnN3ZDFkcmFGZE5hMXBvV2xWb1YxWnNUbFZSYlhCb1RWaEJNVlZXV21GU01VNUpVbXhDYWxaWGFIRlZNR2hEVTBaR1YxcEhSbFpoTW5oU1ZXeFNUMkZzU25SUFZsWlFWbFp3VDFSVlpHdFdWazVGVVZob2EwMUdjRXRWVm1Rd1pHMVdTVlZ1UmxKV1YzaERWVlpqTlZOc1JsVlRhMlJUVFZVMGVsVXhVa05PVmxwV1ZXdHNiRlo2Vmt0V1YzUnpVakZLYzFacVZtdE5TRUV4VjFST1YxTkZNWEZTYkZwcVlUSm5NVmxWVmxkT1IxSklZMGhXVGxORlZqWlZiWFF3VTJ4YWMySXpaRlJXTVZwSVZURldjMDB4VWtsaWVrWlRZa2hDVmxSV1pIZFRWVEI0Vkd4Q1ZHSlZXalJXYWtKelZUSkdObFJyVmxSaGVrWjRWakkxYjFKc2IzZFhhM2hhWVd4YU5WWlhjM2hTTVU1VlVteG9XR0V4Y0ZkVk1WWnZVekpSZDA1RVJsUldWemd4VlRGb1MxUkhUbGhpUlZwT1ZtNUNNRlZ0ZUV0bFJrVjVUMVY0Vm1Wc1NtaFZNalZYVmtkT2MxVnROVTVXVmxwSlZWWmFUMU5YVmtsVmJrNVlZa1pLY1ZsdGRHOU9iR3Q1VjJ0MGJGTkhlRWxWYkdoellXeE9SazFVV21GTlYxSkhWRlpvUjFGdFRrbGlSbWhzWWxSVk1GZFljRTlUVlRCM1RWYzVhVTFIWnpGWGExWnJVMnhaZUZwRldteFNWM2haVmtWamVGUnNXWGRYV0dST1ZrVTFNMWRXYUhOVVIwNVdZVWhvV0ZkRmNFbFdSRVpYVTFVd2VtSkVXbE5oTTFFeFZYcE9SMU5XVGxWV2F6RnNZbGRTVjFreU5YZGxSMVp5Vm1wU2JGWXpVa2xXVjNCSFUyeEpkMlJGY0ZaTlIyUTBXVEZhUjFOR1drVlNhMUpTVm14YVZWWXdWbmRPYXpGWFYydGtUbEpGV2xwVk1qRkhUbFpHVmxacmJHcFdiRnBMVkZjMWMyTlhUbkppUjNCaFVucG9OVlJXV210U1ZrNUdZa1prVWxaWGVFZFZWbFpoVlRGT1dHRklXbHBYUmtWNFZXMTBiMVJyTlZaVmEzaHFWbXMxU2xSWGRFWmxSa3B5WkVSR1ZrMUhhRmRXYWtKdlVteE9jbUpHWkdwaWJrSjFXVzEwZDA1V1ZYZGFSVFZXWW0xNFdGa3hWbk5TYkZaMVUycFNWazFyV2pKVVZsWnpVMGRKZW1GRmNHdFdWMDR6Vm1wR1lWSXlVWGRpUm14cVVqQlpNVnBITURWVk1sWlhZa2hvVkZJd05WbFZiWGhUVVd4YWNsZHJlR3hXYlZKTFZteGFhMUl4Um5KaVJYQm9WbGQ0UzFreFdtdFNNVWwzVDFWS1ZHSnRaM2hXTUdNd1pXMU9XRTVYUmxSU2F6VnZWVEkxYzFOck1WWmpSRlphVFVad1ExVXlNVEJYUms1WFZXdEthMkV4V2xOV2FrSnJWR3hPUjFwR1dsTk5Semg0V1cxNGQxTlZNVmhXYTFwT1VqQTFiMVpHYUc5amJHUkdXak5rYkZaNlZraGFWelZHVFVaT2RXRkVWbEpXVkVaS1dURlNWMlZHVGxsV2JHUllZa1ZhVVZkclZtRlZNVzkzVW10YWJGTkhlRXBaTVZaM1lXeEdWbG96Y0ZSV2EwcEhWVEZXUjFNeVNYZGhSRlpQWVhwQ05sVXhXazlaVmxwR1VteHdVMDFXV2pSVmVrSnpWbXhWZUZwRmFGZFNNbEpWV1RKNFUxRnNXblJQU0hCYVRXdGFhRnBWYUhOWFJrbDNZbnBHVjAxSFp6SlhWRXByVW1zd2VtSklTbWxOTWpoNFZHeFdjbVZIVmxkaFNHeHNZbXRaZVZscVNYaFdSbFYzWWtaT2FsWnNXa2RWTUdoelZrWktjVlJyY0ZOTlJuQkxWbXBLTUZOc1ZuTlVWRVpVVmtWd05GWXdhRXRUYkVaVlZHdGFWRTFyTVRSWmJYaExUbFphVmxremNHdFdhelUxVlRGYWEyRXlUa2RVYTFwclRVWktTbFpHVm10VFJrNXlZa1prYW1FeVp6RlZNRlpYVm0xV1ZrOVZhRlpoTVZsNFZURlNUMlZHWkVaaFJGSk9WbGRvU0ZVeFZuSk5WbVJ6VTJ0c2FFMUhkRFJWTVZVMVVtc3hTV0pHWkZOaE0xRjRXa1JDYjFVeVVraGpSV1JVVW0xU2VGa3lkSE5VYkhCSVQwaG9WMDFWTlRSYVZXaHpVMWRLY1ZKcldsZFhSVzh5V1RGa2ExSldUbFpTYTJoVFRWWkZNVlZxUWxOVVIxSlhWR3R3Vm1FeWFIaFZiWFJyVjBad1ZrMVhhRlppV0ZKTFdXeFZlRlV5VGxWUlZGWnBZVE5DYjFwR2FHRmtiRloxVW10YVZGWkZjRlZaVkVaM1RrWk9XV0pJWkZaTlJWcE5WV3hvYzJGck5YVlJhMmhWVmxWYVNGcEVSazlUVjA1SFUyMXdWR0pVYkZSYVJsSmhaVlpPU1dKSVFsTmhhMFV4VlZaV1ZtUXhhM2xXYmxwc1UwWmFTbFZyVm5kTlZsWnlaVVZzVkZaRldrZFZNVnByVFRKT1Zsb3phRlJTV0dRelZrWmtjMlZXVmxsaVJuQlRVbGRuZUZSV2FFdFZNbFpWVjI1V1RsSnRVbGxWTUdodlUyeFNSbU5FVmxKV1JscERXbFZrVGsxWFRrWmhSbWhXVFVaV00xcFdaRTlUUmxaWFdrUk9VMU5IT0hoV01GWnlaVmRTV0dORmNGZFNWVlY0VlcwMWMxSlZOVWRqUms1YVRWWmFTMWxzVmxkV1ZrNUZVbXRLV0dFeFdUSldWRUpIVWpKV1JtSkZhRlZUU0VKeFZteG9TMVJHVlhkbFNHUlRUVlUxUTFVeGFHOU9WVFYxVTJ0NFZWWjZWa2hWTVdoSFZsWktjMU50YkdoTk1IQlVXa1pXYTJReFRraGFSbEpxWVRKak1WWkVRbGRUUmxWM1Vtc3hUbE5HVmpaVk1GSkxUbGRLUmxkcmVGWk5WMUpHV2xWb1IxVkhUbFZSYlhCVFlUSjBORlpxUm1GbFYxWjBXWHBDYWxKcmJ6RlpWRUphWlVacmVHSkZaRlJTUlZwM1dUSjRSMVF5UlhkYVJXeHJWa1ZhVDFwVldrOVRSa3B5WWtaU1ZsSldWalJaTVZwdlVqRk9jVkpyYUZWV1JVbzBWR3RXWVZOV1RsWlBWV1JXVjBkNFMxa3dWbk5UYkUxM1YxaGtWRlpYYURSVk1XaHpWbXhLYzFOcmNGaFNlbWd6V2xaYVQxRnRWa1poZWtKcFlXdEtTMVpxUW05T1JURllVbXRvVkZaVldrTlZhMmh2VWxkR2MyTkdWazVXTW5oS1drUkdUMVZXVG5WaVJtUk9WbFpaTUZsWWNFOVRSVEZ6V2toV1ZGSkZjRmxYYTFaM1RrZFNWVlpyYUZSaGJFcDZWVEkxYTFWR1pFbFRhMnhPVmpKb1IxcEVSbXRUUmxKWllVVTFUMUpYVWtwYVJscHJVMWRSZUZSdE1WTmlSVnBSV1cxMFIxWkdiRFpXYXpGcFZsUkdWbGt5ZUVkVGF6QjNWMnhXVGxaRldtaFVWV2hEWTFaSmQyRkZOV2hOTUhCTldrWmtZVkpyTVVsU2JGSnBUVEk0ZUZSclZuSmxSbEY0VjJ0a2EwMUhlRU5WTWpGSFRVVXhWbEpxVW1wV1JsbzBWVEJhVDJOR1RsZFZiVEZwVmxad1NWWlljRWRrVjFaR1lVaEtWRkpGYjNoV2JHaExVMVU1VlZKcmRHeFdWM2hFV1dwR1MyRnNXbFppU0ZKVFZteGFTbFJWVm5OWFJrNTFZVWhvVlUwd1drcGFWbEpoVW14T2RGUnNaRmhpUlhCTFdsYzFRMVJIVmxkYVJUVnNVMFZaZUZWcVFqQmxSbGwzVjFod1ZVMXNTa2RVVkU1WFZrWlNWbUpIY0U5WFJYQk1WVEZrYzFOWFZuUmFSbWhVWW01Q1ZWWlVSbmRWTWxaV1YydGtWR0ZyV2xGVmJYUnpVV3haZDJORVVtRmxhMW8wVlRGa1RrMVdTWGRqU0doU1ZsWndUVlV4V205a01WWjFZa1ZLVlZaRlJqUlhha0pxWkRGT1YyRkZiR3hYUlZwRFZXMTBjMWRHV1hkU2JGWlFVak5vUjFVd2FITldSMDV6VW14U1RrMUdXazFYVkVwWFdWWk9TVkpZY0ZOTk1sSlZWbFJDYm1ReVZsaFRhMlJYVjBaV05Ga3hWblpPVm1SSVRsUk9WVTFXY0RWYVZ6VkRZMVpLYzFSclNtbGlWRlp2Vmtjd2VGTnJNSHBTYkZKWVlXdEZNVlpHVmxkT2F6RldXa1ZhV2xadFVrbFZNVkpMVW14a1JsZFliRkpXYkhCTVdsWldSMWRHUmxkVGJYQlBVbFp3U1ZaRVNqQmxWMVpaVW10c1UwMUhlRmxWVmxaVFRtczVWMkpGYUdsV1ZscFhXVEo0VjFOc1ZrWldXR1JzVmtaYVNGUlZWbk5OVms1RlVWaG9hV0V5ZUVwVU1WcHpVakZPU1ZadVJsSldWemd4Vkd4V1lWWldiM2hYYm1oT1VrZDRWMVZzV2xKbFJURklUbFJTYkZJemFFOVpiRlpYVmxaS2RXRkdVbGROTUVwTFZURmFUMUpYVmtaaVJtUlZVa1Z2TVZac2FFdFZNVTVYVVc1YWFWWnJOSHBWYWtaTFUyeGtWbUV6WkZkTlYxSm9XbFZXYzJKR1NYaFdibWhUVFVSRk1sUXhXbUZSTVU1SVZHeGFWVkpYYUV0WlZWWjNUbFpPVlZKcmNHeFNNRFZaV1RCYVIxVkhTa1phTTJSc1ZqQmFNbHBFUWtkTk1VcEpZbnBXVDFaV2NFbFZNVnBoV1ZkV2RGcEZNV3BTVjNoWVdWUkNSMDVHVWxWV2JtaFVVbXMxZDFreU1VZFRiVXB5Vmxod2ExWnRVa3hXVnpWRFkyeEtSbUZJU2xaTlJsWXpWREZTUjJReGJGaFViV2hwVFZWdk1WVnFUa2RUYkVwWFZtMUdWR0V3V21oVmJGWXdUVlp3VmxaWWFGcE5WMmd4VkZWa01GZEdTbk5UV0doV1UwVktWMWRVUVRWU01sWnhZVWhTVlZKRlNsVldWRVoyWld4S1dGcEZaR3hUUlZwUldXcE9kazFWTlhKWGEzUlRWakpvU2xSV2FFWmxWazUxV2taU1UwMXFiRlJYV0hCUFUyc3hSMVJ1U2xoaWJXaFZWVzEwWVZaV1ZYbGtSVnBPWVd4VmQxVXhVa2RWUmxwMVVsaG9hbGRIZUROV2JHaHpVV3hPV1dGSGNHdFdWMDQxVkd4a05HVldUa2hrUnpGVFRVZDRSbGxVUm5kV1JtdDNZVVZ3YVZaVVJuZFZNR2gzVVd4YVZtTkVWbGROTWxJeFZGVm9WazFXU1hkaFJtaG9WbGRrTmxwV1dtdFNhekI2Vm14U2FsWlhhRTlWYlhSSFUwWkdWMVZ1V2xSV1ZWa3lWVzEwYjJGc1dYZGhSRkpzVmtaS00xVXljRmRrVm1SMVdrUkdZV0V4VmpaWk1WWnJWRzFXUm1KRmFGUmliVGswV1ZaV2IwNUhWbFZTYTFwc1ZsWmFWVmxxUW05T1ZscDFVV3R3VTFZeWFFaGFWVnBQVTJ4S2RXRklhRlJOUmxZeldsWmFWMUl4U1hkUFYwWnFZVE5TVlZZd2FFTldSbHB4Vm10a1dsWlViRXBWVjNSdlVXeGtSbUpGYkZCV1ZuQklWV3BHYTJGR1VsbGhSRVpYWVRCYVMxVldWa2RUVms1RlVteEdhVTB5VWtOV1ZFSldaREF4VmxacmJFNWlSVFV4VlcxMGMyVkdTWGRYYTNoclZqRmFSbFV4WkU5VGJVNVdZa1Z3VjAxSGFFcFdWRVpQWkRGSmVGUnJaR2xpYldRMFZXcENjbVZ0VGxoT1dHeHNZVE5vZVZWdGVGZGxSa1YzWWtaT1ZtVnNXa1pWTW5ONFZqSk9jbVJFVmxWV1dFSnZWMVJHVDFGdFZrVlNiR3hWVjBoQ2RWbFdhRXRPUjFaWFZtNWtWRlpWV2tsVmExWjJUVlphV1ZOcmFGVldiV2hJV2xkMFIwMVhUa2hTYWtKT1ZsWldNMVpxUmxkVFJURkpWbTA1YW1FeVpETlpWRUpoVTJ4YU5sSnJjRmRXYlZKSlZURm9kMk5zVlhkbFJXeFFWa1Z3TWxSV1ZrZE5iR1J5WVVSR1QySlVVak5WTVdRd1dWZFdXVkpxVmxOV1dGRXhWVlpXYTFOWFVsaGFTR3hVVWpKU1dsWkZWbmRSYkZsNlVXeGFiRlpzY0RGVVZXaHpWMnhTUlZGc1VsZE5XRUpLV2xWU1MxSnRTbGRVYkdoVlVsaFNXVlpxUW5Ka01rVjVZa1ZzVGxKWGREUlpha3BIVFVkR2RFOVZiR2hOYlZKR1ZUQlNjMlJYVG5KaFJFWmhZVEZhVFZkWWNFZGtiVlpHWVVoT2FXRnJTbFZXTW5oMlpWWkdWVlZ1V2xwWFJrVjRWVzE0UzJGck5VaE9TSEJVVmpKNFNGUldaRTlpYkVweVlVaG9WRTFJWnpCV1ZFWnZVbXhPU1Zac2JGTldSVVV4VmpGV1YxTnNTbFZSYmxac1VrVmFXRlZzVmpCTlZtUkdWMnBXVWxaWFVrZFVWRXBPWlVaS1JtSjZWbGRpU0VKSlZrWmFWMUl4VGtsaVJrWlRZbTE0UjFaRVFuTk9Sa3BYWWtob1ZGSlZXbFZXTW5CUFUyeHdjbFpZYUZaTlJHeExWbGMxVjFZeFpISmhSelZwWVRGd1NsVXhVazlTYkU1eFVteG9WVlpGU25sV2JYUmhVMFpSZVU1WFJsUmlSMUp5Vld0V2IyRlZNVmxUYTNSUVZsZFNTbGxzVmxkV2JHUnlaRVpTV0ZKV1dYcFdha0pyVWpKV2NXSkZiRlZTUlZreFdXMTRkMVpIVGxoWGExcE9UVEowTkZaR2FIWk9SMFYzVld0MFUxWnJjRFJVVmxaSFYwWktjbUpGV2xaTlIzUXpXVmN4ZDJWR1RrWmlTRlpZWVRKNE5GWXlkRmRXVmxWM1drVmtiRll3TkhoVmFrbDRWMFprUmxkcmJHeFdhelV6VjFab1UyTnNTbkZSYWtaT1RVWktURlJzWXpGbFZrNVpVbXBhVTJKdVFsVmFSRUp6VlRKRmVGcEZaRlJXVlZwUlZXMTBiazVXV2taalIyaHFWbFZ3TVZwVlVrZFdNazVXV25wV1ZVMUdjRlpYYlhCTFVtc3dlbFp0ZUZOU1YzaHhWakZXYW1WR1ZYaGpTR2hPVW1zMWRsa3dXa2RXUmxsM1RWUlNiRmRHV2tsVVZXaEhZMFpLYzFKc1RrNWhNWEJ2VjFSS1IxUldiRmRhUm14VFZteEtlVlpWVmxabFJrWlZVbXRhVTAxVk5VMVZhMmh2VGxaYWNsZHJkRTVXZWxaS1ZGUkdUMWRXU25Ka1JGWnJUVWRTUzFkVVRsZFRSVEZaVm0xR1dHSnVRalJVYTFaWFZtMVdWMVJyWkZaaVZUQjRWVzV3VDFOdFJsWlhhM0JxVmpCYVIxUlVUbk5XUmtsM1pFWm9UMkV4Y0VwWlZFcDNVMVV3ZUZSWWNGUmliV2N4V2tSQ2ExVXlSblJrU0doVVZsWmFXRll5TlhOVWJIQnlWbTFvYWxaVldrZFZNbkJ5VFVaU1JWTnFSbGhoTVhCS1dsVmplR1F4VFhwVlZFWlRZVEprTkZkcVFsZFRWbGw0VkcxR2JHSldiRE5WYlhoWFpVWk5kMkpHVG1wV1JuQktWRmQwYzJOR1RsZFZhMHBvVW5wc2IxcFdWakJTYkZaMFZGaHdWRlpGY0V0WmJYaDNWRWRXVjJKRlZteFRSbHBOV1RGU1ExTnJOVVphUld4YVRWZFNhRlV3VWtkVVZrcEdZa1ZLVjAxR1ZqTldha1pYVTJzeFdGUnVWbXBpUlZwTFdsZDBZVlJIVWxoa1NGcFVWakJ3ZWxWc1dsTmpiVXBHWVVkb2JGWnRVa2hXVmxaSFZrWmtkV0ZFVW1oTk1GcExWa1JHVTFJeVZsWlNhM0JUVmtVMGVGcEVRbXRVUmxvMlZtdHNhVlpVUmtOVmJYUXdVMnhPUmxWcmJHeFdWR3hLV2xWU1IySnNUbFZUVkVaWFRUQndiMVV4V2t0VFJrNVdZa1JPVTFJd1dsbFdiWFJMVTJ4U1YxVnViRlpoTW5neFZUSTFiMDVXWkhST1NHUnNWbXhhUzFSVlZuTmpiR1J5V2pOb2JGSldWalphUm1oclpVVXhjVlpVU21wU1YxSkRWbXhXWVZVd01WbFhibHBwVmxSck1sbHFSa3RXUms1WlUydDRiRll5ZURWV1ZFNUhWbXhLVlZSdGNHRldWbTh4VjFSR2ExSnNXa2hhU0VKVVZsaENRMVpWVmxkVGJGcHhVbXRLVkZORlNubFpNRlozWVd4VmQySkdUbFpoYTNCSlZXcENSbVZHU2taaFJYQllWbGRTTUZVeFdsZFNNVTEzVGxoS1UxWllVVEZhUkVKYVpWVTFWbFpyV2xSU1YzaGFWVEJTUjFkR2NISldibEpXWld0YU1scFZhRzlqTVVwR1lVaEtWMUpXYkRaYVZscERVakpXVjFwR2FGSldhM0J4Vkd4V1YxTnNTbFpQV0d4c1YwVlpNRlZ0ZUZkaFYwVjNWMnhXVDFJelFrTlZNRnByVjFkT1ZWSnJTbFpOTUVwTldsWlZOVlJzVm5WV1ZFcFVVa1UxVDFacVNUVlVSMVpXVW10a1ZtSlZOSHBaTW5oTFRrZEZkMXBHVmxwTlJHeElWbFphVDFWV1NuSmhSRlpyVFVkb1NsZFVSbXRUUmsxNlZteFdhbUpGV1RGV01GWmhWRWRXVjFSclZsUldNRFZMVlc1d1MyRnNaRWxUYWxwcVZrWktTRlpVUWxka1YwcDFZVWR3VDFKdVFrcGFSbHByWlZac1ZsWnROVk5XUlVwd1dWZDBSMVpHYTNoYVJYQnNZbGRTZGxVeFZqQlRhekI2VVcxb1dtVnJXa2RhVlZKSFYwZE9WbVJJYUdoaWEyOHhWVlphZDFKck1IcFZia3BUVWxkbk1WZHFUa3RWYkZGNFkwaGFWRkpIWkROVmJGSlBZV3hOZDAxVmVGVk5SM2cwVlhwT1YxWkdTbFZVYWxKcFVsWndWRmRVVG5ka1ZURkdZa1ZzVTFac1NuRlZWbFp1WkRBeFZWWnVXazVOYXpWVlZrWlNRMkZyTUhkVmEzQnFWbnBXYUZaclZYaGliVW8yVW10YVRsWldiRE5hVldRMFpGVXhSMXBHYUdwaE0xSlFXVlJDWVdGSFVraGtSV2hXWVRGWmVGVnRkRzlpYlVaV1YycFNWRlpXY0VkWmJGVTBaVWRPYzFWVVJrOVdWbkJLV2xaa2QxbFZNSGhVYkd4VFZsaFJNVlpxUWtkVFYxWlZWR3RXVkdKRk5URlZNRkpQVGtVeGNsZHJlR3RXYlZKSVZGZHdWazFHVWtWUmExcFhUVmhDVFZaRVNsZFNiRTVKVlZob1ZWWkZTbGxXYWtKcVpWVTFWMXBGTVZSU1IyZ3dWVzE0UjFWSFJYZGxSRkpVVmxkU1IxVXhWbGRXYlU1elZtcFdWbFpXYnpGV2FrNXJVV3hXZFZKcmNGVlNSVFI0V1d0b1MxWldVbGhXYmxwT1ZsWmFTVlZ0ZEhaTlIwcFdWbXQwVlZac2NFaFZNR2h6WVRGS2MxTnRjRlZOUm5CVVdURlNZVkV4VGtoVWJrSlRZV3MxUzFWc1ZtRlRSbGw2V2tWc1RrMVhVa3BWTUZKSFVXMUdWbUZIYUZSV1JYQk1WMVpvYzAweVNuVmhTR2hQVmxaS1NWWkVTbk5sVms1R1lrVndVMkV6VVRGV1JFNUxVMVpyZDFkcmFGUmlWMUpaVlcxMGJtVkdXa2hQVkZKc1ZsVmFTRnBWYUhKTmJFWldXak5vYUZaWGVFcFViR1JUVW1zd2VtSkdVbXBpUlhCTFZUQldZVk5zUmxoaFJYQldUVVZhV2xWdGREQk9WazEzVFZaT2FFMVdjRWRWTVZaSFpGWk9SVkZyV21GU1ZscE5XbFpTUjJSdFZuRlZibEpxVW14R05GVldWbUZVUms1WVZHNWtXbFl3TlRGWmFrWkxUVlUxUmxWcmJGVldlbFpLVkZab1IxVldTbGhOVmxKU1ZsWkpNRlpGWkRSU1ZrNUpWbTA1YVUxSGFGVldSbFozVGxaV05sWnJiRTVUUlZwSFZURm9hMU50U25KYU0zQnNVa1Z3UjFSVmFFZFVSa3AxWVVWd1lVMUdjRXRWVmxwclVqQXdlbFZZWkZOU1YzaERXVzB3TVU1SFRsVlZhM0JPVWxWYVZsVXdhSE5sUjBweVZsaG9WazFGTlVaWFZscExZMVpHVm1KRlNsZFNWbXd6VkZaYWEyUnRWbkZTYkdoWVlrVnZlRlJyVm10VFZsSllZa2RHVkZKWGVGbFZiR2h2WlVaTmQxZHFVbHBOUm5CRFZURmFUMVp0VG5OVGEzQldUVWhCTUZkVVFUVlViRTVKVmxSQ2FsSlhhRFJaVmxaaFZUQXhWMVpyYUZaV1ZHeFZWVEZhVDJGWFJYZGFSbFpvVFVSc1IxUlZhSE5WVms1MVlVYzFVazFHUmpOWlZFWnJVakZPV1ZKdVRsTmhNbVEwVlRGak5VNVdTbGxhUlZwVVlXMXplRlpGVWtOamJHUnlWMnRzYTFZeFdreFhWbWhYWVRKS2RXRklhRmRXVm5CS1YxUkdhMU5WTVhKU2JsWlRWMGM1TkZWNlNURk9SMDVWVm10d1RsWnJOWGxWTUdSSFZrVXhXRTlJYUZkaGExb3hXbFZrVDAxV1VrVlRhelZvVmxkNFZGVXhXbGRTVjFaSlZteFNhbUV5T0RGVmJYUkhWV3hWZUdORldrNVNSM2h5Vld4b2ExWkhSWGRrTTNCV1RVWndhRlV3YUZkVk1rbDNaRVJTYUdFeVVsUlhWRXBIVTBaTmVGbDZSbXBoTW1oMVZtMTBWbVF3T1ZkU2EyUlVVakpTTVZZeWVFdGxSbGwzVm10c1YwMXRlRWhXVmxaelVqRkpkMXBGU2xKV1YxSktXbFpXWVZOWFVYZFBWbWhxWW0xU1MxUnJWbUZWYkZsM1QxVktWRTFWTlV0VmJuQkRVV3hWZDFkc1NsUldNbWhKVmxaVk5XTldSbFpoUjNCWVVsWndTMVZzV2t0VVZrNUZVbXhDVTFaWE9UUlZha0pUVTFVeFZsWnJWbFJTYlZKVVdUSjBNRlpHVG5SUFZGWlRWa1ZhU0ZSWGNITlhiRXBXWWtSR1dHRXhiekpaTVZKVFpERlZkMVp1Vm1sTlZYQlpWbXhXVjFNd05WZGFSMFpzWVROb2VWVnROVzlsUmsxNlVsaHdWMlZzY0V0VVZXaEhWVlpLY1ZGcmNGZGhNWEJMVm1wT2ExRnNWblZpUm1oVVltMVNSMWxyYUV0VFYxWlhZVWhrVmsxRldrTlZiRlp6WVd4T1JsWnJhRlJXYldoSVZYcEdUMkl4U2xWUmFsWm9UVVJHU2xreFVtRlRWVEZJVkd4b1UyRnJXbkZYYlhSaFUwWlJlRlJyY0ZkV2JWSlpWVEJqZUZSc1JsWmlSa3BVVmxWYVRGcEVSazlaVm1SeVlVaG9UazFIZEROV1JtUTBVakZXVmxadE1WTk5SemswVmxSQ1YwNXJOVmRpUlZwVFRVUkdWMVV3Vm01TlIwVjNZMFJXVWxaVk5VaFdWRXBQVW14a2MxVnNVbFZTVmxwdldrWm9hMU14Vm5KTlJFSlZVa1ZaZUZacmFFdFZiVTVGVFVRd2JrdFRhM0JMVTJ0d1MxTnJOdz09'))))))));


    if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_editor/template_library/common.css.tpl')) {
    $mailbeez_common_css = file_get_contents(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_editor/template_library/common.css.tpl');
        if (isset($obj->mailBee->styling)) {
            // preparse CSS Styling parameters
            $mailbeez_common_css = mh_template_parse_css_tags($mailbeez_common_css, $obj->mailBee->styling, array('left' => '/*stylevar:', 'right' => '*/'));
        }

        $smarty->assign('template_source', $mailbeez_common_css);
        $mailbeez_common_css = $smarty->fetch(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_editor/classes/smarty_eval.tpl');

    } else {
        $mailbeez_common_css = '';
    }


    // preparse tags in CSS with [##$var##] delimiters
    $output_content_html = mh_template_parse_css_tags($output_content_html, array('MAILBEEZ_COMMON_CSS' => $mailbeez_common_css));


    if (isset($obj->mailBee->styling)) {
        // preparse CSS Styling parameters
        $output_content_html = mh_template_parse_css_tags($output_content_html, $obj->mailBee->styling, array('left' => '/*stylevar:', 'right' => '*/'));
    }

    // preparse tags in CSS with [##$var##] delimiters
    $output_content_html = mh_template_parse_css_tags($output_content_html, $replace_variables_common);

    // process inline styles
    // replace & with &amp; (but not &amp; with &amp;amp;)
    // to avoid DOM warnings


    $output_content_html = preg_replace('/&(?![A-Za-z0-9#]{1,7};)/', '&amp;', $output_content_html);

    // thanks to markdown
    // Remove UTF-8 BOM and marker character in input, if present.
    $output_content_html = preg_replace('{^\xEF\xBB\xBF|\x1A}', '', $output_content_html);

    list( $output_content_html, $m) = mhpi('template_engine_2', $output_content_html);

    if (MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY == 'True') {
        // engine Emogrifier
        $output_content_html = mh_convert_characters($output_content_html);
        $emogrifier = new Emogrifier($output_content_html);
        $output_content_html = @$emogrifier->emogrify();
        $output_content_html = str_replace('</title>', "</title>\n" . $m, $output_content_html);
    }


    // fix invalid html causing broken layouts in outlook e.g. width="600px" => width="600"

    $output_content_html = preg_replace('/"([0-9]+)px"/', '"$1"', $output_content_html);


    $output_subject = strip_tags($output_subject); // remove html tags from subject

    // convert complete subject
    if (MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT == 'True' && !(isset($mail['subject_plain']) && $mail['subject_plain'] == true)) {
        $output_subject = mh_convert_characters_subject_utf_8($output_subject);
    }



    if ($output_content_txt == '') {
        // auto generate txt version if empty
        $h2t = new html2text($output_content_html);
        $output_content_txt = $h2t->get_text();
    }

    // preserve links
    $output_content_txt =  preg_replace("#<a href=\"(([a-zA-Z]+://)([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "$1" . "\n<a", $output_content_txt);


    // make sure txt version is not containing any html
    $output_content_txt = strip_tags($output_content_txt);

    // remove multiple empty lines
//    $output_content_txt = preg_replace("/[\r\n]+/", "\n", $output_content_txt);
//    $output_content_txt = preg_replace("/[\n]+/", "\n", $output_content_txt);
    $output_content_txt = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\n\n", $output_content_txt);

    return array($output_subject, $output_content_html, $output_content_txt);
}


function mh_smarty_auto_literal($content)
{
    // remove literal
    $content = str_replace('{literal}', '', $content);
    $content = str_replace('{/literal}', '', $content);

    // and insert it from scratch
    $content = preg_replace('#(<style[^>]*>)([^<]*)(</style>)#', "$1{literal}$2{/literal}$3", $content);

    // doing some fixing as well - shouldn�t be here, but for now...

    // fixing ' inside smarty code
    $content = str_replace('&#39;', "'", $content);

    // fixing " inside smarty code
    $content = str_replace('&quot;', '"', $content);

    return $content;
}


if (!function_exists('mh_convert_characters_subject_utf_8')) {

    function mh_convert_characters_subject_utf_8($subject)
    {
        // very painful topic ahead!!!


        // arrrgh...
        // UTF-8 encoding of subject
        // http://stackoverflow.com/questions/341708/how-to-handle-utf-8-email-headers-like-subject-using-ruby
        // http://ncona.com/2011/06/using-utf-8-characters-on-an-e-mail-subject/
        //
        // doesnt work with sending through gmail reading on thunderbird, but shows on iphone
        // seems to work

        //$subject = str_replace('&amp;', '&', $subject);
        //$subject = html_entity_decode($subject);

        //    $subject = preg_replace_callback("/(&#[0-9]+;)/", create_function('$m', 'return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");' ), $subject);
        //    $subject = preg_replace_callback("/(&#[0-9]+;)/", create_function('$m', 'html_entity_decode(mb_convert_encoding($m[1], "UTF-8", "UTF-8"), ENT_COMPAT, "UTF-8");' ), $subject);

        //    $subject = html_entity_decode(mb_convert_encoding($subject, 'UTF-8', 'UTF-8'), ENT_COMPAT, "UTF-8");

        // todo
        // automatic db encoding detection
        if (false) {
            // works at valentin for all special characters / languages
            $subject = preg_replace_callback("/(&#[0-9]+;)/", create_function('$m', 'return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");'), $subject);
            return "=?utf-8?B?" . base64_encode($subject) . "?=";
        } else {
            // works with � ...
            return mb_encode_mimeheader($subject, 'UTF-8');
        }
    }
}


function mh_convert_characters_table()
{

    $convert_array = array('￀' => '&Agrave;', '￠' => '&agrave;', '�?' => '&Aacute;', '￡' => '&aacute;', 'ￂ' => '&Acirc;', '￢' => '&acirc;', '?' => '&Atilde;', '?' => '&atilde;', 'ￄ' => '&Auml;', '￤' => '&auml;', 'ￅ' => '&Aring;', '￥' => '&aring;', 'ￆ' => '&AElig;', '￦' => '&aelig;', 'ￇ' => '&Ccedil;', '￧' => '&ccedil;', '?' => '&ETH;', '?' => '&eth;', '￈' => '&Egrave;', '￨' => '&egrave;', '￉' => '&Eacute;', '￩' => '&eacute;', '�?' => '&Ecirc;', '￪' => '&ecirc;', 'ￋ' => '&Euml;', '￫' => '&euml;', '?' => '&Igrave;', '?' => '&igrave;', '�?' => '&Iacute;', '￭' => '&iacute;', '�?' => '&Icirc;', '￮' => '&icirc;', '�?' => '&Iuml;', '￯' => '&iuml;', '￑' => '&Ntilde;', '￱' => '&ntilde;', '?' => '&Ograve;', '?' => '&ograve;', 'ￓ' => '&Oacute;', '￳' => '&oacute;', 'ￔ' => '&Ocirc;', '￴' => '&ocirc;', '?' => '&Otilde;', '?' => '&otilde;', 'ￖ' => '&Ouml;', '￶' => '&ouml;', '￘' => '&Oslash;', '￸' => '&oslash;', 'ﾌ' => '&OElig;', 'ﾜ' => '&oelig;', '￟' => '&szlig;', '?' => '&THORN;', '?' => '&thorn;', '￙' => '&Ugrave;', '￹' => '&ugrave;', '�?' => '&Uacute;', '￺' => '&uacute;', 'ￛ' => '&Ucirc;', '￻' => '&ucirc;', 'ￜ' => '&Uuml;', '￼' => '&uuml;', '?' => '&Yacute;', '?' => '&yacute;', 'ﾟ' => '&Yuml;', '￿' => '&yuml;');
    return $convert_array;
}

if (!function_exists('mh_convert_characters')) {

    function mh_convert_characters($content)
    {
        // purpose of this function is to replace characters with their entity fully controlled

        // todo
        // automatic db encoding detection
        if (false) {
            // works at valentin for all special characters / languages
            return $content;
        } else {
            $convert_array = mh_convert_characters_table();

            // somehow special characters get lost in the code, so lets reconstruct them from their html entity
            $convert_array_trans = array();
            foreach ($convert_array as $key => $value) {
                $convert_array_trans[html_entity_decode($value)] = $value;
            }

            $convert_values = array_values($convert_array_trans);
            $convert_keys = array_keys($convert_array_trans);
            $content = str_replace($convert_keys, $convert_values, $content);

            return $content;
        }
    }
}

?>