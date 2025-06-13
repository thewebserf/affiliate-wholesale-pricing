<?php 
add_action('wp_ajax_nopriv_awp_ajax_login', 'awp_handle_ajax_login');
function awp_handle_ajax_login() {
    check_ajax_referer('awp_ajax_login', 'awp_nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = esc_url_raw($_POST['redirect_to'] ?? '');

    // Validate
    if (empty($email)) {
        wp_send_json_error(__('Email is required.', 'affiliate-wholesale-pricing'));
    }

    // Rate limiting
    $transient_key = 'awp_login_attempts_' . md5($email);
    $attempts = get_transient($transient_key) ?: 0;

    if ($attempts >= 5) {
        wp_send_json_error(__('Too many attempts. Try again in 15 minutes.', 'affiliate-wholesale-pricing'));
    }

    // Authenticate
    $user = wp_signon([
        'user_login'    => $email,
        'user_password' => $password,
        'remember'      => true
    ]);

    if (!is_wp_error($user)) {
        // Reset attempts on success
        delete_transient($transient_key);
        wp_send_json_success([
            'message' => __('Login successful!', 'affiliate-wholesale-pricing'),
            'redirect' => $redirect ?: home_url()
        ]);
    } else {
        // Increment attempts on failure
        set_transient($transient_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
        wp_send_json_error(__('Invalid email or password.', 'affiliate-wholesale-pricing'));
    }
}

add_action('wp_enqueue_scripts', function () {
    // Only enqueue on pages where needed
    wp_enqueue_script('awp-login', plugin_dir_url(__FILE__) . 'js/awp-login.js', array(), AWP_PLUGIN_VERSION, true);

    wp_localize_script('awp-login', 'awp_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('awp_ajax_login'),
    ]);
});
