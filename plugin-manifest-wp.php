<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              1905newmedia.com
 * @since             1.0.0
 * @package           Plugin_Manifest_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Manifest
 * Plugin URI:        pluginmanifest-wp.com
 * Description:       See WordPress plugin status at a glance, whenever, wherever.
 * Version:           1.0.0-alpha.1
 * Author:            1905 New Media
 * Author URI:        1905newmedia.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-manifest-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_MANIFEST_WP_VERSION', '1.0.0-alpha.1' );

/**
 * Adds a link to the plugins page for easy access to PM-WP Settings.
 */
function plugin_manifest_wp_settings_link( $actions ) {

    $settings = array('settings' => '<a href="options-general.php?page=plugin-manifest-wp">' . __('Settings', 'General') . '</a>');
    $site_link = array('support' => '<a href="https://1905newmedia.com" target="_blank">Support</a>');

    $actions = array_merge($settings, $actions);
    $actions = array_merge($site_link, $actions);
            
    return $actions;
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'plugin_manifest_wp_settings_link', 10, 5);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-manifest-wp-activator.php
 */
function activate_plugin_manifest_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-manifest-wp-activator.php';
	Plugin_Manifest_Wp_Activator::activate();
  if ( is_plugin_active( plugin_basename(__FILE__)))
    {
        deactivate_plugins( plugin_basename(__FILE__)) ;
        // Hide the default "Plugin activated" notice
        if ( isset ($_GET['activate']))
        {
            unset ($_GET['activate']) ;
        }
    }
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-manifest-wp-deactivator.php
 */
function deactivate_plugin_manifest_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-manifest-wp-deactivator.php';
	Plugin_Manifest_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_manifest_wp' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_manifest_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-manifest-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_manifest_wp() {

	$plugin = new Plugin_Manifest_Wp();
	$plugin->run();

}
run_plugin_manifest_wp();
