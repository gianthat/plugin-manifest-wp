<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       gianthatworks.com
 * @since      1.0.0
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/admin
 * @author     Reid Burnett <reid@gianthatworks.com>
 */
class Plugin_Manifest_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
		 * The options name to be used in this plugin
		 *
		 * @since  	1.0.0
		 * @access 	private
		 * @var  	string 		$option_name 	Option name of this plugin
		 */
		private $option_name = 'plugin_manifest_wp';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Manifest_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Manifest_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-manifest-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Manifest_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Manifest_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-manifest-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
		 * Add an options page under the Settings submenu
		 *
		 * @since  1.0.0
		 */
		public function add_options_page() {

			$this->plugin_screen_hook_suffix = add_options_page(
				__( 'Plugin Manifest Settings', 'plugin-manifest-wp' ),
				__( 'Plugin Manifest', 'plugin-manifest-wp' ),
				'manage_options',
				$this->plugin_name,
				array( $this, 'display_options_page' )
			);

		}

		/**
			 * Render the options page for plugin
			 *
			 * @since  1.0.0
			 */
			public function display_options_page() {
				include_once 'partials/plugin-manifest-wp-admin-display.php';
			}

		/**
			 * Render the options page for plugin
			 *
			 * @since  1.0.0
			 */
			public function register_setting() {

				//
				// Add a General section
				//
					add_settings_section(
						$this->option_name . '_general', // TODO Might rename this more specifically later
						__( 'General', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_general_cb' ),
						$this->plugin_name
					);

					// Add Radio buttons for Frequency
						add_settings_field(
							$this->option_name . '_frequency',
							__( 'Push frequency', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_frequency_cb' ),
							$this->plugin_name,
							$this->option_name . '_general',
							array( 'label_for' => $this->option_name . '_frequency' )
						);

					// Add Radio buttons for Frequency
						add_settings_field(
							$this->option_name . '_day',
							__( 'Preferred day for push', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_day_cb' ),
							$this->plugin_name,
							$this->option_name . '_general',
							array( 'label_for' => $this->option_name . '_day' )
						);

					// Add input field for License Key
						add_settings_field(
							$this->option_name . '_license_key',
							__( 'License Key', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_license_key_cb' ),
							$this->plugin_name,
							$this->option_name . '_general',
							array( 'label_for' => $this->option_name . '_license_key' )
						);

				//
				// End General Setting section
				//

				//
				// Add Extra Settings Section
				//
					add_settings_section(
						$this->option_name . '_extra', // TODO Might rename this more specifically later
						__( 'Extra', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_extra_cb' ),
						$this->plugin_name
					);

					// Add button for force push
						add_settings_field(
							$this->option_name . '_force_push',
							__( 'Force Push', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_force_push_cb' ),
							$this->plugin_name,
							$this->option_name . '_extra',
							array( 'label_for' => $this->option_name . '_force_push' )
						);

				// TODO Registers settings in wp db
				register_setting( $this->plugin_name, $this->option_name . '_frequency', array( $this, $this->option_name . '_sanitize_frequency' ) );
				register_setting( $this->plugin_name, $this->option_name . '_day', array( $this, $this->option_name . '_sanitize_day' ) );
				register_setting( $this->plugin_name, $this->option_name . '_license', 'intval' );
			}

		/**
			 * Render the text for the general section
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_general_cb() {
				echo '<p>' . __( 'Please change the settings accordingly.', 'plugin-manifest-wp' ) . '</p>';
			}

		/**
			 * Render the text for the extra section
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_extra_cb() {
				echo '<p>' . __( 'To force this plugin to push and send the site\'s plugin list, click this button.', 'plugin-manifest-wp' ) . '</p>';
			}

		/**
			 * Render the radio input field for frequency option
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_frequency_cb() {
				?>
					<fieldset>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" id="<?php echo $this->option_name . '_frequency' ?>" value="daily">
							<?php _e( 'Daily', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="weekly" checked>
							<?php _e( 'Weekly', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="biweekly">
							<?php _e( 'Bi-weekly', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="monthly">
							<?php _e( 'Monthly', 'plugin-manifest-wp' ); ?>
						</label>
					</fieldset>
				<?php
			}

		/**
			 * Render the radio input field for day option
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_day_cb() {
				?>
					<fieldset>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" id="<?php echo $this->option_name . '_day' ?>" value="monday">
							<?php _e( 'Monday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="tuesday">
							<?php _e( 'Tuesday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="wednesday">
							<?php _e( 'Wednesday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="thursday">
							<?php _e( 'Thursday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="friday">
							<?php _e( 'Friday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="saturday">
							<?php _e( 'Saturday', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_day' ?>" value="sunday" checked>
							<?php _e( 'Sunday', 'plugin-manifest-wp' ); ?>
						</label>
					</fieldset>
				<?php
			}

			/**
				 * Render the License key input for this plugin
				 *
				 * @since  1.0.0
				 */
				public function plugin_manifest_wp_license_key_cb() {
					echo '<input type="text" size="80" name="' . $this->option_name . '_license_key' . '" id="' . $this->option_name . '_license_key' . '"> ';
				}

			/**
				 * Render the Force Push button for this plugin
				 *
				 * @since  1.0.0
				 */
				public function plugin_manifest_wp_force_push_cb() {
					echo '<input type="button" class="button button-secondary button-large" name="' . $this->option_name . '_force_push' . '" id="' . $this->option_name . '_force_push' . '" value="Force push"> ';
				}


}
