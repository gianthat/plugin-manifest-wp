<?php

/**
 * Runs Plugin tasks.
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
 * Defines the plugin name, version
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/admin
 * @author     Reid Burnett <reid@gianthatworks.com>
 */

class Plugin_Manifest_Wp_Plugin_Tasks {

	public $item_type         = 'plugin';
	public $upgrade_refresh   = 'wp_update_plugins';
	public $upgrade_transient = 'update_plugins';

	public $obj_fields = array(
		'name',
		'status',
		'update',
		'version',
	);

  protected function get_upgrader_class( $force ) {}

  protected function get_item_list() {}

  public function __construct() {
		add_action( 'wp_ajax_nopriv_get_all_items', array( &$this,'get_all_items') );
		add_action( 'wp_ajax_get_all_items', array( &$this, 'get_all_items') );
		add_action( 'init', array( &$this,'manifest_cron_mail' ) );
		add_action( 'plugin_manifest_wp_cron', array( &$this, 'get_all_items' ) );
		add_action( 'plugin_manifest_wp_next_event', array( &$this, 'get_all_items' ) );
	}

	/**
	 * Checks for and creates the scheduled event.
	 *
	 * @param object
	 * @return mixed bool/int
	 */
	public function manifest_cron_mail() {
		$setting_schedule = get_option( 'plugin_manifest_wp_frequency' );
		$next_run = get_option( 'plugin_manifest_wp_day' );
		$next_run = strtotime( $next_run );
		$schedule = get_option( 'plugin_manifest_wp_frequency' );
		$event_schedule = wp_get_schedule( 'plugin_manifest_wp_cron' );
		$next_event = wp_get_scheduled_event( 'plugin_manifest_wp_cron' );

		if ( ! wp_next_scheduled( 'plugin_manifest_wp_cron' ) ) {
			wp_schedule_event( $next_run, $schedule, 'plugin_manifest_wp_cron' );
		} else {
			wp_clear_scheduled_hook( 'plugin_manifest_wp_cron');
			wp_clear_scheduled_hook( 'plugin_manifest_wp_next_event');
			wp_schedule_single_event( $next_run, 'plugin_manifest_wp_next_event' );
			wp_reschedule_event( $next_run, $schedule, 'plugin_manifest_wp_cron' );
		}
	}

  /**

   * @param array List of update candidates

   * @param array List of item names

   * @return array List of update candidates

   */

  protected function filter_item_list( $items, $args ) {}

  protected function get_status( $file ) {}

  protected function status_single( $args ) {}

  public function get_plugin_name() {
    return $this->plugin_name;
  }

	public function get_all_items() {
		$items = $this->get_item_list();

		$duplicate_names = [];

		foreach ( $this->get_all_plugins() as $file => $details ) {
			$all_update_info = $this->get_update_info();
			$update_info = ( isset( $all_update_info->response[ $file ] ) && null !== $all_update_info->response[ $file ] ) ? (array) $all_update_info->response[ $file ] : null;
			$name = $details['Name'];

			if ( ! isset( $duplicate_names[ $name ] ) ) {
				$duplicate_names[ $name ] = array();
			}

			$plugin_status = is_plugin_active($file); // is the plugin active?

			$duplicate_names[ $name ][] = $file;
			$items[ $file ]             = [
				'name'           => $details['Name'],
				'status'         => $plugin_status,
				'update'         => (bool) $update_info,
				'update_version' => $update_info['new_version'],
				'update_package' => $update_info['package'],
				'version'        => $details['Version'],
				'update_id'      => $file,
				'title'          => $details['Name'],
				'description'    => wordwrap( $details['Description'] ),
				'file'           => $file,
			];

			if ( null === $update_info ) {

				// Get info for all plugins that don't have an update.
				$plugin_update_info = isset( $all_update_info->no_update[ $file ] ) ? $all_update_info->no_update[ $file ] : null;

				// Compare version and update information in plugin list.
				if ( null !== $plugin_update_info && version_compare( $details['Version'], $plugin_update_info->new_version, '>' ) ) {
					$items[ $file ]['update'] = 'version higher than expected';
				}
			}
		}

		foreach ( get_mu_plugins() as $file => $mu_plugin ) {
			$mu_version = '';
			if ( ! empty( $mu_plugin['Version'] ) ) {
				$mu_version = $mu_plugin['Version'];
			}
			$item_name = $mu_plugin['Name'];

			$items[ $file ] = array(
				'name'           => $item_name,
				'status'         => 'must-use',
				'update'         => false,
				'update_version' => null,
				'update_package' => null,
				'version'        => $mu_version,
				'update_id'      => '',
				'title'          => '',
				'description'    => '',
				'file'           => $file,
			);
		}

		$raw_items = get_dropins();
		$raw_data  = _get_dropins();

		foreach ( $raw_items as $name => $item_data ) {
			$description    = ! empty( $raw_data[ $name ][0] ) ? $raw_data[ $name ][0] : '';
			$items[ $name ] = [
				'name'           => $item_data['Title'],
				'title'          => $item_data['Title'],
				'description'    => $description,
				'status'         => 'dropin',
				'version'        => $item_data['Version'],
				'update'         => false,
				'update_version' => null,
				'update_package' => null,
				'update_id'      => '',
				'file'           => $name,
			];
		}

		foreach ( $duplicate_names as $name => $files ) {
			if ( count( $files ) <= 1 ) {
				continue;
			}
			foreach ( $files as $file ) {
				$items[ $file ]['name'] = str_replace( '.' . pathinfo( $file, PATHINFO_EXTENSION ), '', $file );
			}
		}

		// Encode Plugin List
		$plugin_list = json_encode($items, JSON_FORCE_OBJECT);

		// Then decode it
		$plugin_list_decode_list = json_decode($plugin_list);

		// Who should get the email? This one.
		$to_email = get_option( 'plugin_manifest_wp_email_address' );

		// Who is the email from.
		$from_email_option = get_option( 'plugin_manifest_wp_wordpress_notification_email' );

		// The name of the sender.
		$from_name_option = get_option( 'plugin_manifest_wp_wordpress_notification_name' );

	/**
	 * WordPress Core info
	 */
		$wp_version = get_bloginfo( 'version' );
		$wp_core_updates = get_core_updates();
		$wp_core_msg = '<p>WordPress version <strong>' . $wp_version . '</strong> is installed.</p>';

	/**
	 * Adds a new directory in uploads and puts the JSON file in there.
	 */
		$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'plugin-manifest-wp';
		wp_mkdir_p( $uploads_dir ); // creates the directory
		$uploads_dir_file = $uploads_dir.'/plugin-manifest-'.current_time('timestamp').'.json'; // create a new file with current_time timestamp
		file_put_contents( $uploads_dir_file, $plugin_list ); // put the file in the new directory

	/**
	 * Creates an email with HTML table in body and JSON file attached.
	 */
		$attachments = array(wp_upload_dir()['basedir'] . '/plugin-manifest-wp/plugin-manifest-'.current_time('timestamp').'.json');
		$site_name = get_bloginfo('name');
		$admin_email = get_option('admin_email');

		// Check if _wordpress_notification_name is set
		if ( empty($from_name_option) ) {
			$from_name = $site_name;
		} else {
			$from_name = $from_name_option;
		}

		// Check if _wordpress_notification_email is set
		if ( empty($from_email_option) ) {
			$from_email = $admin_email;
		} else {
			$from_email = $from_email_option;
		}

		$site_url = get_bloginfo('url');
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . $from_name . ' <' . $from_email . '>' . "\r\n");
		$to = $to_email;
		$msg = '<h1>Plugin Manifest for ' . $site_name;
		$msg .= '<br><span style="font-size:0.75em;"><a href="' . $site_url . '">' . $site_url . '</a></span>'; 
		$msg .= '</h1>';
		$msg .= '<h2>WordPress Summary</h2>';
		$msg .= $wp_core_msg;

		if ( ! isset( $wp_core_updates[0]->response ) || 'latest' == $wp_core_updates[0]->response ) {
			$msg .= '<p>';
			$msg .= 'This is the latest version of WordPress!';
			$msg .= '</p>';
		} else {
			$wp_core_update_version = $wp_core_updates[0]->version;
			$msg .= '<p>';
			$msg .= 'An updated version of WordPress is available: ';
			$msg .= '<strong>' . $wp_core_update_version . '</strong>';
			$msg .= '</p>';
		}

		$msg .= '<h2>Plugin Manifest</h2>';
		$msg .= '<table width="100%" border="1" align="center" cellspacing="0" cellpadding="8" style="font-family:Arial, Helvetica, sans-serif; border-width: 1px; border-collapse: collapse; border-color: #ddd;">
		  <tr style="background:lightgray;">
			<th>Plugin Name</th>
			<th>Status</th>
			<th>Version</th>
			<th>Update Available</th>
			<th>Update Version</th>
		  </tr>';

		foreach($plugin_list_decode_list as $plugin_list_decode) {

			if($plugin_list_decode->update == 1) {
				$tr_style = 'style="background:#d2ffd2;"';
			} elseif($plugin_list_decode->update == '') {
				$tr_style = 'style="background:#f1f1f1;"';
			}

			$msg .= '<tr ' . $tr_style . '>

		    <td align="center" valign="middle">' . $plugin_list_decode->name . '</td>

				<td align="center" valign="middle">';

					if($plugin_list_decode->status == 1) {
						$status = 'Active';
					}
					elseif($plugin_list_decode->status == '') {
						$status = 'Inactive';
					}
					else{
						$status = $plugin_list_decode->status;
					}

					$msg .= $status;

				$msg .= '</td>

				<td align="center" valign="middle">' . $plugin_list_decode->version . '</td>

				<td align="center" valign="middle">';

					if($plugin_list_decode->update == 1) {
						$update = 'Yes';
					}
					elseif($plugin_list_decode->update == '') {
						$update = 'No';
					}
					else {
						$update = $plugin_list_decode->update;
					}

					$msg .= $update;

				$msg .= '</td>

				<td align="center" valign="middle">';

					if($plugin_list_decode->update_version == ''){
						$update_version = '';
					}
					else {
						$update_version = $plugin_list_decode->update_version;
					}

					$msg .= $update_version;

				$msg .= '</td>

			</tr>';
		}

		$msg .= '</table>';

		// Email all of it.
		wp_mail($to, 'Plugin Manifest for ' . $site_name, $msg, $headers, $attachments);

	}

	/**
	 * Gets the details of a plugin.
	 *
	 * @param object
	 * @return array
	 */
	private function get_details( $file ) {
		$plugin_folder = get_plugins( '/' . plugin_basename( dirname( $file ) ) );
		$plugin_file   = Utils\basename( $file );

		return $plugin_folder[ $plugin_file ];
	}

	/**
	 * Gets all available plugins.
	 *
	 * Uses the same filter core uses in plugins.php to determine which plugins
	 * should be available to manage through the WP_Plugins_List_Table class.
	 *
	 * @return array
	 */
	public function get_all_plugins() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Calling native WordPress hook.
		return apply_filters( 'all_plugins', get_plugins() );
	}

	/**
	 * Get the available update info
	 *
	 * @return mixed
	 */
	protected function get_update_info() {
		return get_site_transient( $this->upgrade_transient );
	}

}