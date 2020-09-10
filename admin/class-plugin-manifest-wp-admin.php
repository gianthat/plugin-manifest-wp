<?php

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

			$page_title = __( 'Plugin Manifest Settings', 'plugin-manifest-wp' );
			$menu_title = __( 'Plugin Manifest', 'plugin-manifest-wp' );
			$capability = 'manage_options';
			$slug				=	$this->plugin_name;
			$callback   = array( $this, 'display_options_page' );

			$this->plugin_screen_hook_suffix = add_options_page( $page_title, $menu_title, $capability, $slug, $callback );
		}

		/**
			 * Render the options page for plugin
			 *
			 * @since  1.0.0
			 */
			public function display_options_page() {
				include_once 'partials/plugin-manifest-wp-admin-display.php';
				include_once 'partials/plugin-manifest-wp-force-push-js.php';
			}

		/**
			 * Create and register settings for plugin
			 *
			 * @since  1.0.0
			 */
			public function register_setting() {

				//
				// Add a General section
				//
					add_settings_section(
						$this->option_name . '_general',
						__( 'General', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_general_cb' ),
						$this->plugin_name . '_settings'
					);

						// Add Radio buttons for Frequency
							add_settings_field(
								$this->option_name . '_frequency',
								__( 'Run frequency', 'plugin-manifest-wp' ),
								array( $this, $this->option_name . '_frequency_cb' ),
								$this->plugin_name . '_settings',
								$this->option_name . '_general',
								array( 'label_for' => $this->option_name . '_frequency' )
							);

						// Add Radio buttons for Frequency
							add_settings_field(
								$this->option_name . '_day',
								__( 'Next run date', 'plugin-manifest-wp' ),
								array( $this, $this->option_name . '_day_cb' ),
								$this->plugin_name . '_settings',
								$this->option_name . '_general',
								array( 'label_for' => $this->option_name . '_day' )
							);

						// Add input field for Email address
							add_settings_field(
								$this->option_name . '_email_address',
								__( 'Recipient Email', 'plugin-manifest-wp' ),
								array( $this, $this->option_name . '_email_address_cb' ),
								$this->plugin_name . '_settings',
								$this->option_name . '_general',
								array( 'label_for' => $this->option_name . '_email_address' )
							);

				//
				// Add a Notification section
				//
					add_settings_section(
						$this->option_name . '_wp_email',
						__( 'WordPress Email', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_wp_email_cb' ),
						$this->plugin_name . '_settings'
					);

						// Add input field for WP Notification Email address
							add_settings_field(
								$this->option_name . '_wordpress_notification_email',
								__( 'WordPress Notification Email', 'plugin-manifest-wp' ),
								array( $this, $this->option_name . '_wordpress_notification_email_cb' ),
								$this->plugin_name . '_settings',
								$this->option_name . '_wp_email',
								array( 'label_for' => $this->option_name . '_wordpress_notification_email' )
							);

						// Add input field for WP Notification Name
							add_settings_field(
								$this->option_name . '_wordpress_notification_name',
								__( 'WordPress Notification Name', 'plugin-manifest-wp' ),
								array( $this, $this->option_name . '_wordpress_notification_name_cb' ),
								$this->plugin_name . '_settings',
								$this->option_name . '_wp_email',
								array( 'label_for' => $this->option_name . '_wordpress_notification_name' )
							);

				//
				// End General and Notification settings sections
				//

				//
				// Add a License section
				//
					add_settings_section(
						$this->option_name . '_license',
						__( 'License', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_license_cb' ),
						$this->plugin_name . '_license'
					);

					// Add input field for License Key
						add_settings_field(
							$this->option_name . '_license_key',
							__( 'License Key', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_license_key_cb' ),
							$this->plugin_name . '_license',
							$this->option_name . '_license',
							array( 'label_for' => $this->option_name . '_license_key' )
						);

				//
				// End License Setting section
				//

				register_setting( $this->plugin_name, $this->option_name . '_frequency', array( $this, $this->option_name . '_sanitize_frequency' ) );
				register_setting( $this->plugin_name, $this->option_name . '_day', 'string' );
				register_setting( $this->plugin_name, $this->option_name . '_license_key', 'string' );
				register_setting( $this->plugin_name, $this->option_name . '_email_address', 'string' );
				register_setting( $this->plugin_name, $this->option_name . '_wordpress_notification_email', 'string' );
				register_setting( $this->plugin_name, $this->option_name . '_wordpress_notification_name', 'string' );

		}

		/**
			 * Render the options page for plugin
			 *
			 * @since  1.0.0
			 */
			public function register_option() {

				//
				// Add Extra Settings Section
				//
					add_settings_section(
						$this->option_name . '_extra', // TODO Might rename this more specifically later
						__( 'Extra', 'plugin-manifest-wp' ),
						array( $this, $this->option_name . '_extra_cb' ),
						$this->plugin_name . '_options',
						array( 
							'action'	=> $this->option_name . 'pm_wp_cli'
						)
					);

					// Add button for force push
						add_settings_field(
							$this->option_name . '_force_push',
							__( 'Force Push', 'plugin-manifest-wp' ),
							array( $this, $this->option_name . '_force_push_cb' ),
							$this->plugin_name . '_options',
							$this->option_name . '_extra',
							array( 'label_for' => $this->option_name . '_force_push' )
						);

			}

		/**
			 * Render the text for the general section
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_general_cb() {
				echo '<p>' . __( 'Change settings to who receives the email and how often.', 'plugin-manifest-wp' ) . '</p>';
			}

		/**
			 * Render the radio input field for frequency option
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_frequency_cb() {
				$frequency = get_option( $this->option_name . '_frequency' );
				?>
					<fieldset>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" id="<?php echo $this->option_name . '_frequency' ?>" value="hourly" <?php checked( $frequency, 'hourly' ); ?>>
							<?php _e( 'Hourly', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" id="<?php echo $this->option_name . '_frequency' ?>" value="daily" <?php checked( $frequency, 'daily' ); ?>>
							<?php _e( 'Daily', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="weekly" <?php checked( $frequency, 'weekly' ); ?>>
							<?php _e( 'Weekly', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="bi_weekly" <?php checked( $frequency, 'bi_weekly' ); ?>>
							<?php _e( 'Bi-weekly', 'plugin-manifest-wp' ); ?>
						</label>
						<br>
						<label>
							<input type="radio" name="<?php echo $this->option_name . '_frequency' ?>" value="monthly" <?php checked( $frequency, 'monthly' ); ?>>
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
				$day = get_option( $this->option_name . '_day' );
				echo '<input type="date" name="' . $this->option_name . '_day' . '" id="' . $this->option_name . '_day' . '" value="' . $day . '" min="' . date('Y-m-d') . '"><p>Next date to run relative relative to the last change of plugin settings. <a href="?page=plugin-manifest-wp&tab=tools" class="button button-link button-large button-force mb-3">Run now</a></p>';
			}

		/**
			 * Render the text for the License section
			 *
			 * @since  1.0.0
			 */
			public function plugin_manifest_wp_license_cb() {
				echo '<p>' . __( 'Add your license key here.', 'plugin-manifest-wp' ) . '</p>';
			}

			/**
				 * Render the License key input for this plugin
				 *
				 * @since  1.0.0
				 */
				public function plugin_manifest_wp_license_key_cb() {
					$license_key = get_option( $this->option_name . '_license_key' );
					echo '<input type="text" size="80" name="' . $this->option_name . '_license_key' . '" id="' . $this->option_name . '_license_key' . '" value="' . $license_key . '">';

				}

			/**
				 * Render the Recipient email address input for this plugin
				 *
				 * @since  1.0.0
				 */
				public function plugin_manifest_wp_email_address_cb() {
					$email_address = get_option( $this->option_name . '_email_address' );
					echo '<input placeholder="email@domain.com" type="email" size="80" name="' . $this->option_name . '_email_address' . '" id="' . $this->option_name . '_email_address' . '" value="' . $email_address . '"><p>Email address(es) of whom should receive the email. Comma-separated list is allowed.</p>';
				}

		/**
			 * Render the text for the WP Notification section
			 *
			 * @since  1.1.0
			 */
			public function plugin_manifest_wp_wp_email_cb() {
				echo '<p>' . __( 'Override WordPress notification email address and name to assist with email deliverability.', 'plugin-manifest-wp' ) . '</p>';
			}

			/**
				 * Render the WordPress Notification Email input for this plugin
				 *
				 * @since  1.1.0
				 */
				public function plugin_manifest_wp_wordpress_notification_email_cb() {
					$wordpress_notification_email = get_option( $this->option_name . '_wordpress_notification_email' );
					echo '<input placeholder="email@domain.com" type="email" size="80" name="' . $this->option_name . '_wordpress_notification_email' . '" id="' . $this->option_name . '_wordpress_notification_email' . '" value="' . $wordpress_notification_email . '"><p>This overrides the admin email address that WordPress uses as the From for notifications.</p>';
				}

			/**
				 * Render the WordPress Notification Name input for this plugin
				 *
				 * @since  1.1.0
				 */
				public function plugin_manifest_wp_wordpress_notification_name_cb() {
					$wordpress_notification_name = get_option( $this->option_name . '_wordpress_notification_name' );
					echo '<input placeholder="Cyndi Lauper" type="text" size="80" name="' . $this->option_name . '_wordpress_notification_name' . '" id="' . $this->option_name . '_wordpress_notification_name' . '" value="' . $wordpress_notification_name . '"><p>This overrides the From name of the Site Title found in Settings > <a href="options-general.php">General</a>.</p>';
				}

			/**
				 * Sanitize the text Frequency value before being saved to database
				 *
				 * @param  string $_frequency $_POST value
				 * @since  1.0.0
				 * @return string           Sanitized value
				 */
				public function plugin_manifest_wp_sanitize_frequency( $frequency ) {
					if ( in_array( $frequency, array( 'hourly', 'daily', 'weekly', 'bi_weekly', 'monthly' ), true ) ) {
				        return $frequency;
				    }
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
				 * Render the Force Push button for this plugin
				 *
				 * @since  1.0.0
				 */
				public function plugin_manifest_wp_force_push_cb() {
					echo '<button class="button button-secondary button-large button-force mb-3" name="' . $this->option_name . '_force_push' . '" id="' . $this->option_name . '_force_push' . '">Force push</button>';
					echo '<div class="send-mail-result success mt-3">Email Sent Successfully!</div>';
					echo '<div class="send-mail-result error mt-3">Something went wrong!</div>';
				}

}
