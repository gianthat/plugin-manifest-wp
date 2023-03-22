<?php
/*
Plugin Name: Plugin Manifest
Description: Generates a summary of all installed plugins and sends an email to specified recipients.
Version: 1.0
Author: Your Name
*/

// Include the options page file
require_once( plugin_dir_path( __FILE__ ) . 'includes/plugin-manifest-options.php' );

// Define a function to check for plugin updates and send the email
function plugin_manifest_send_email() {
    // Get the email recipients and update interval from options
    $recipients = get_option('plugin_manifest_recipients', '');
    $interval = get_option('plugin_manifest_interval', 'weekly');

    // Get all installed plugins
    $plugins = get_plugins();

    // Initialize an array to store plugin information
    $plugin_data = array();

    // Loop through the plugins and check for updates
    foreach ($plugins as $plugin_path => $plugin) {
        // Get the plugin update information
        $update_data = get_plugin_data($plugin_path, false, false);

        // Check if the plugin is currently active
        $is_active = is_plugin_active($plugin_path);

        // Add the plugin information to the array
        $plugin_data[] = array(
            'name' => $plugin['Name'],
            'version' => $plugin['Version'],
            'update_version' => $update_data->update->new_version,
            'is_active' => $is_active
        );
    }

    // Prepare the email message
    $message = '<table>';
    $message .= '<tr><th>Plugin Name</th><th>Current Version</th><th>Update Available</th><th>Active</th></tr>';
    foreach ($plugin_data as $data) {
        $message .= '<tr>';
        $message .= '<td>' . $data['name'] . '</td>';
        $message .= '<td>' . $data['version'] . '</td>';
        if ( ! empty($data['update_version']) ) {
            $message .= '<td>' . $data['update_version'] . '</td>';
        } else {
            $message .= '<td>No Update Available</td>';
        }
        if($data['is_active'] ) {
            $message .= '<td>Active</td>';
        } else {
            $message .= '<td>Inactive</td>';
        }
        $message .= '</tr>';
    }
    $message .= '</table>';

    // Send the email
    $to = explode(',', $recipients);
    $subject = 'Plugin Manifest Summary';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $send_result = wp_mail($to, $subject, $message, $headers);

    // Handle any errors that occurred while sending the email
    if ( $send_result === false ) {
        $error_message = error_get_last()['message'];
        error_log("Plugin Manifest: Failed to send email: $error_message");
    } else {
        error_log("Plugin Manifest: Email sent successfully to " . implode(',', $to));
    }
}

// Schedule the function to run on the selected interval
switch ($interval) {
case 'daily':
$interval_seconds = 24 * 60 * 60; // 24 hours
break;
case 'monthly':
$interval_seconds = 30 * 24 * 60 * 60; // 30 days
break;
default:
$interval_seconds = 7 * 24 * 60 * 60; // 7 days (default to weekly)
}

wp_schedule_event(time(), $interval_seconds, 'plugin_manifest_send_email');
