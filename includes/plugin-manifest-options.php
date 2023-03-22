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

    // Save new options values if the form is submitted
    if (isset($_POST['submit'])) {
        $recipients = sanitize_text_field($_POST['recipients']);
        $interval = sanitize_text_field($_POST['interval']);

        update_option('plugin_manifest_recipients', $recipients);
        update_option('plugin_manifest_interval', $interval);

        echo '<div class="notice notice-success"><p>Options updated successfully.</p></div>';
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
            </table>
            <p class="submit"><input type="submit" name="submit" class="button-primary" value="Save Changes"></p>
        </form>
    </div>
    <?php
}
