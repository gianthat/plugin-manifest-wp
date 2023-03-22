<?php
// Add an options page to configure the email recipients and update interval
function plugin_manifest_options_page() {
    add_options_page('Plugin Manifest', 'Plugin Manifest', 'manage_options', 'plugin_manifest_options', 'plugin_manifest_options_page_callback');
}
add_action('admin_menu', 'plugin_manifest_options_page');

function plugin_manifest_options_page_callback() {
    // Get the current options values
    $recipients = get_option('plugin_manifest_recipients', '');
    $interval = get_option('plugin_manifest_interval', 'weekly');
    $notification_email = get_option('plugin_manifest_notification_email', '');
    $notification_name = get_option('plugin_manifest_notification_name', '');

    // Save new options values if the form is submitted
    if (isset($_POST['submit'])) {
        $recipients = sanitize_text_field($_POST['recipients']);
        $interval = sanitize_text_field($_POST['interval']);
        $notification_email = sanitize_email($_POST['notification_email']);
        $notification_name = sanitize_text_field($_POST['notification_name']);

        update_option('plugin_manifest_recipients', $recipients);
        update_option('plugin_manifest_interval', $interval);
        update_option('plugin_manifest_notification_email', $notification_email);
        update_option('plugin_manifest_notification_name', $notification_name);

        echo '<div class="notice notice-success"><p>Options updated successfully.</p></div>';
    }

    // Run the plugin and send an email outside the set interval if the "Force Push" button is clicked
    if (isset($_POST['force_push'])) {
        plugin_manifest_send_email();
        echo '<div class="notice notice-success"><p>Email report sent successfully.</p></div>';
    }

    // Render the options form
    ?>
    <div class="wrap">
        <h2>Plugin Manifest Options</h2>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Email Recipients:</th>
                    <td><input type="text" name="recipients" value="<?php echo esc_attr($recipients); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Update Interval:</th>
                    <td>
                        <select name="interval">
                            <option value="daily"<?php if ($interval == 'daily') echo ' selected'; ?>>Daily</option>
                            <option value="weekly"<?php if ($interval == 'weekly') echo ' selected'; ?>>Weekly</option>
                            <option value="monthly"<?php if ($interval == 'monthly') echo ' selected'; ?>>Monthly</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Notification Email:</th>
                    <td><input type="email" name="notification_email" value="<?php echo esc_attr($notification_email); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Notification Name:</th>
                    <td><input type="text" name="notification_name" value="<?php echo esc_attr($notification_name); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="Save Changes">
                <input type="submit" name="force_push" class="button-secondary" value="Force Push">
            </p>
        </form>
    </div>
    <?php
}
