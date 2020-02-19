<?php

if (!class_exists('JPIBFI_Options')){

    abstract class JPIBFI_Options {

        protected abstract function get_option_name();
        protected abstract function get_default_options();

        private function get_db_options(){
            $db_options = get_option($this->get_option_name());
            return $db_options;
        }

        public function get_options(){
            $db_options = $this->get_db_options();
            $db_options = $db_options != false ? $db_options : array();
            $defaults = $this->get_default_options();
            $merged = array_merge($defaults, $db_options);
            return $merged;
        }
    }
}