<?php

/*
 * Stores basic plugin info like all the urls and directories needed
 */
class JPIBFI_Globals {

	private static $plugin_dir;
	private static $plugin_file;
	private static $plugin_url;
	private static $version;
    private static $version_minor;

	public static function init( $plugin_file, $version, $version_minor ){
		self::$plugin_file = $plugin_file;
		self::$plugin_dir = plugin_dir_path( self::$plugin_file );
		self::$plugin_url = plugin_dir_url( self::$plugin_file );
		self::$version = $version;
        self::$version_minor = $version_minor;
	}

	public static function get_plugin_dir() {
		return self::$plugin_dir;
	}

	public static function get_plugin_file() {
		return self::$plugin_file;
	}

	public static function get_plugin_url() {
		return self::$plugin_url;
	}

	public static function get_version() {
		return self::$version;
	}

    public static function get_file_version(){
        return self::$version . self::$version_minor;
    }
}