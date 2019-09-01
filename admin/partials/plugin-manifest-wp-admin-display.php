<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       gianthatworks.com
 * @since      1.0.0
 *
 * @package    Plugin_Manifest_Wp
 * @subpackage Plugin_Manifest_Wp/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <?php
    if( isset( $_GET[ 'tab' ] ) ) {
      $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'settings';
    }
  ?>
  <?php settings_errors(); ?>

    <div class="nav-tab-wrapper">

      <a href="?page=plugin-manifest-wp&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
      <a href="?page=plugin-manifest-wp&tab=license" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>">License</a>
      <a href="?page=plugin-manifest-wp&tab=tools" class="nav-tab <?php echo $active_tab == 'tools' ? 'nav-tab-active' : ''; ?>">Tools</a>

    </div>

    <form action="options.php" method="post">

      <?php

        if( $active_tab == 'settings' ) {

          settings_fields( $this->plugin_name );
          do_settings_sections( $this->plugin_name . '_settings' );

        } elseif( $active_tab == 'license' ) {

          settings_fields( $this->plugin_name );
          do_settings_sections( $this->plugin_name . '_license' );

        }

        if( $active_tab == 'settings' || $active_tab == 'license' ) {

          submit_button();

        }

      ?>

    </form>

    <?php

      if( $active_tab == 'tools' ) {

          settings_fields( $this->plugin_name );
          do_settings_sections( $this->plugin_name . '_options' );

        }

    ?>

</div>