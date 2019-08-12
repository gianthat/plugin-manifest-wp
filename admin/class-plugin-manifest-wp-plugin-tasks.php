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
				'update'         => false,
				'update_version' => null,
				'update_package' => null,
				'update_id'      => '',
				'file'           => $name,
			];
		}

		$duplicate_names = [];

		foreach ( $this->get_all_plugins() as $file => $details ) {
			$all_update_info = $this->get_update_info();
			$update_info     = ( isset( $all_update_info->response[ $file ] ) && null !== $all_update_info->response[ $file ] ) ? (array) $all_update_info->response[ $file ] : null;
			$name 					 = $details['Name'];

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

		foreach ( $duplicate_names as $name => $files ) {
			if ( count( $files ) <= 1 ) {
				continue;
			}
			foreach ( $files as $file ) {
				$items[ $file ]['name'] = str_replace( '.' . pathinfo( $file, PATHINFO_EXTENSION ), '', $file );
			}
		}

		// $file = fopen('results.json','w');

		// fwrite($file, json_encode($items, JSON_FORCE_OBJECT));
		$json_body = json_encode($items, JSON_FORCE_OBJECT);

      echo '<div id="plugin-list-json" class="hidden"><code>'. $json_body .'</code></div>';


		// $attach = './results.json';
  //   $content = file_get_contents($attach);
  //   $content = chunk_split(base64_encode($content));
		// $email = "reid@gianthatworks.com, ";//hardcoded default. admin email will be appended
		// $site = preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
		// $msg = "site: ".$site . " \r\n ";
		// $msg .= "json: <pre>" .$json_body. "</pre>";
		// $msg .= $content;

		// mail($email,"Plugin Manifest",$msg);

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