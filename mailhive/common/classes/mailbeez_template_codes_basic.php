<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.7
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


class mailbeez_template_code_engine
{
    var $template_tags = array();
    var $mailbeez_template_field_id = 0;
    var $template_output_delimiter = '#$#';

// class constructor
    function mailbeez_template_code_engine()
    {
    }


// class methods

    // inspired by wordpress shortcodes

    function register_template_code($tag, $func)
    {
        if (is_callable($func)) {
            $this->template_tags[$tag] = $func;
        }
    }

    function remove_template_code($tag)
    {
        unset($this->template_tags[$tag]);
    }

    function remove_all_template_codes()
    {
        $this->template_tags = array();
    }

    function do_template_code($content)
    {
        if (empty($this->template_tags) || !is_array($this->template_tags)) {
            return $content;
        }

        $pattern = $this->get_template_code_regex();
        return preg_replace_callback('/' . $pattern . '/s', array($this, 'do_template_code_tag'), $content);
    }


    function show_template($content) {
        $output = $this->do_template_code($content);
        list($before, $template, $after) = explode($this->template_output_delimiter, $output);
        return $template;
    }


    function get_template_code_regex()
    {
        $tagnames = array_keys($this->template_tags);
        $tagregexp = join('|', array_map('preg_quote', $tagnames));

//        return '(.?)\[(' . $tagregexp . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';

        return
            '\\[' // Opening bracket
            . '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)" // 2: Shortcode name
            . '\\b' // Word boundary
            . '(' // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*' // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])' // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*' // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)' // 4: Self closing tag ...
            . '\\]' // ... and closing bracket
            . '|'
            . '\\]' // Closing bracket
            . '(?:'
            . '(' // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+' // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+' // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]' // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]


    }

    function do_template_code_tag($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->template_code_parse_atts($m[3]);

        if (isset($m[5])) {
            // enclosing tag - extra parameter
            return $m[1] . call_user_func($this->template_tags[$tag], $attr, $m[5], $tag) . $m[6];
        } else {
            // self-closing tag
            return $m[1] . call_user_func($this->template_tags[$tag], $attr, NULL, $tag) . $m[6];
        }
    }

    function template_code_parse_atts($text)
    {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }


    function template_code_atts($pairs, $atts)
    {
        $atts = (array)$atts;
        $out = array();
        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts)) {
                $out[$name] = $atts[$name];
            } else {
                $out[$name] = $default;
            }
        }
        return $out;
    }

    function strip_template_codes($content)
    {

        if (empty($this->template_tags) || !is_array($this->template_tags)) {
            return $content;
        }

        $pattern = $this->get_template_code_regex();

        return preg_replace_callback("/$pattern/s", array($this, 'strip_template_code_tag'), $content);
    }


    function strip_template_code_tag($m)
    {

        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        return $m[5];
//        return $m[1] . $m[6];
    }



    function _register()
    {
        $this->mailbeez_template_field_id++;
    }


    function _dummy($atts, $content = 'default content') {
        return '';
    }

    function pre_parse($content) {
        // preparse static shortcodes
        $content = str_replace('[[FIRSTNAME]]', '{$firstname}', $content);
        $content = str_replace('[[LASTNAME]]', '{$lastname}', $content);
        $content = str_replace('[[EMAIL]]', '{$email_address}', $content);
        $content = str_replace('[[CUSTOMER_ID]]', '{$customers_id}', $content);

        $content = str_replace('[[literal]]', '{literal}', $content);
        $content = str_replace('[[/literal]]', '{/literal}', $content); // workaround to use literal tags manually

        $content = preg_replace('/\[\[([^\[\]]+)\]\]/', '{$1}', $content);

        return $content;
    }
}




if (defined('MAILBEEZ_CONFIG_EDITOR_STATUS') && MAILBEEZ_CONFIG_EDITOR_STATUS == 'True') {
       require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_editor/classes/mailbeez_template_codes.php');
   } else {
    class mailbeez_template_codes extends mailbeez_template_code_engine
    {
        function _register_template_codes()
        {
            // register codes to parse out
            $mailbeez_template_codes = array();
            $mailbeez_template_codes['EDIT_TEXT'] = 'mh_edit_text';
            $mailbeez_template_codes['EDIT_SINGLE'] = 'mh_edit_single';
            $mailbeez_template_codes['VAR_FONT'] = 'mh_var_font';
            $mailbeez_template_codes['VAR_COLOR'] = 'mh_var_color';

            $method = array($this, '_dummy');
            foreach ($mailbeez_template_codes as $tag => $method_name) {
                $this->register_template_code($tag, $method);
            }
        }
    }
}



// end of class
?>