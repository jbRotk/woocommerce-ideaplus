<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Ideaplus_Plugin
 * @subpackage Ideaplus_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author     brazz <767502630@qq.com>
 * @package    Ideaplus_Plugin
 * @subpackage Ideaplus_Plugin/includes
 */
if(!defined('ABSPATH')){ exit; }
class Ideaplus_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//add_dashboard_page('Ideaplus', 'Ideaplus Setting', 'administrator','display_copyright', 'display_copyright_html_page');
	}

}
