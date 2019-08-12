<?php

/**
 * Fired during plugin activation
 *
 * @link       gianthatworks.com
 * @since      1.0.0
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/includes
 * @author     Reid Burnett <reid@gianthatworks.com>
 */
class Plugin_Manifest_Wp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

	}
  
  public function is_requirements_met() {
      $min_wp = '4.6' ; // minimum WP version
      $min_php = '7.1' ; // minimum PHP version
      // Check for WordPress version
      if ( version_compare( get_bloginfo('version'), $min_wp, '>' ))
      {
          return false ;
      }
      // Check the PHP version
      if ( version_compare(PHP_VERSION, $min_php, '>'))
      {
          return false ;
      }
      return true ;
  }

}
