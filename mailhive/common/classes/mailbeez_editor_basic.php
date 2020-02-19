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


class mailbeez_editor_engine
{
    var $editor_tags = array();
    var $mailbeez_editor_field_id = 1;
    var $mailbeez_container_field_id = 1;
    var $editor_output_delimiter = '#$#';

// class constructor
    function mailbeez_editor_engine()
    {
    }


// class methods

    // inspired by wordpress shortcodes

    function register_editor_code($tag, $func)
    {
        if (is_callable($func)) {
            $this->editor_tags[$tag] = $func;
        }
    }

    function remove_editor_code($tag)
    {
        unset($this->editor_tags[$tag]);
    }

    function remove_all_editor_codes()
    {
        $this->editor_tags = array();
    }

    function do_editor_code($content)
    {
        if (empty($this->editor_tags) || !is_array($this->editor_tags)) {
            return $content;
        }

        $pattern = $this->get_editor_code_regex();
        return preg_replace_callback('/' . $pattern . '/s', array($this, 'do_editor_code_tag'), $content);
    }


    function show_editor($content)
    {
        $content = $this->pre_parse_common_css_tags($content);

        $output = $this->do_editor_code($content);

        list($before, $editor, $after) = explode($this->editor_output_delimiter, $output);

        return $editor;
    }


    function pre_parse_common_css_tags($content)
    {
        // preparse tags in CSS with [##$var##] delimiters
        $replace_variables_common = $GLOBALS['mh_template_replace_variables_common'];
        $content = mh_template_parse_css_tags($content, $replace_variables_common);
        return $content;
    }


    function get_editor_code_regex()
    {
        $tagnames = array_keys($this->editor_tags);
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

    function do_editor_code_tag($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->editor_code_parse_atts($m[3]);

        if (isset($m[5])) {
            // enclosing tag - extra parameter
            $output = $m[1] . call_user_func($this->editor_tags[$tag], $attr, $m[5], $tag) . $m[6];
        } else {
            // self-closing tag
            $output = $m[1] . call_user_func($this->editor_tags[$tag], $attr, NULL, $tag) . $m[6];
        }

        $output = $this->do_editor_code($output);
        return $output;
    }

    function editor_code_parse_atts($text)
    {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]); elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]); elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]); elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }


    function editor_code_atts($pairs, $atts)
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

    function strip_editor_codes($content)
    {

        if (empty($this->editor_tags) || !is_array($this->editor_tags)) {
            return $content;
        }

        $pattern = $this->get_editor_code_regex();

        $output = preg_replace_callback("/$pattern/s", array($this, 'strip_editor_code_tag'), $content);

        return $output;
    }


    function strip_editor_code_tag($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            $output = substr($m[0], 1, -1);
        }

        $output = $m[5];

        $output = $this->strip_editor_codes($output);

        return $output;
//        return $m[1] . $m[6];
    }


    function _register()
    {
        $this->mailbeez_editor_field_id++;
    }

    function _register_container()
    {
        $this->mailbeez_container_field_id++;
    }


    function _dummy($atts, $content = 'default content')
    {
        return '';
    }

    function _register_editor_codes()
    {
        return false;
    }
}


if (defined('MAILBEEZ_CONFIG_EDITOR_STATUS') && MAILBEEZ_CONFIG_EDITOR_STATUS == 'True') {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_editor/classes/mailbeez_editor.php');
} else {
    class mailbeez_editor extends mailbeez_editor_engine
    {

    }
}



// end of class
?>