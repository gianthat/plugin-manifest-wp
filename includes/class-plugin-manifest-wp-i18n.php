<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       gianthatworks.com
 * @since      1.0.0
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/includes
 * @author     Reid Burnett <reid@gianthatworks.com>
 */
class Plugin_Manifest_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'plugin-manifest-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
