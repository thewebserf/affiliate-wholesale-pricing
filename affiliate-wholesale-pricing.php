<?php
/**
 * Plugin Name: Affiliate Wholesale Pricing
 * Description: Custom WooCommerce affiliate pricing.
 * Version: 1.0
 * Author: The Web Serf
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WooCommerce') || version_compare(WC_VERSION, '5.0.0', '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Affiliate Wholesale Pricing</strong> requires WooCommerce 5.0 or higher.</p></div>';
    });
    return;
}

define('AWP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AWP_PLUGIN_VERSION', '1.0');

$required_files = [
    'inc/roles.php',
    'inc/helpers.php',
	'inc/price-simple.php',
	'inc/price-variable.php',
	'inc/price-cart.php',
	'inc/admin-ui.php',
	'inc/shortcodes.php',
	'inc/login-ajax.php'
];

foreach ($required_files as $file) {
    if (file_exists(AWP_PLUGIN_PATH . $file)) {
        require_once AWP_PLUGIN_PATH . $file;
    } else {
        error_log("Affiliate Wholesale Pricing: Missing required file - " . $file);
    }
}

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'affiliate-wholesale-pricing',
        plugin_dir_url(__FILE__) . 'css/affiliate-wholesale-pricing.css',
        array(),
        AWP_PLUGIN_VERSION
    );
});

register_activation_hook(__FILE__, 'awp_add_affiliate_user_role');

register_deactivation_hook(__FILE__, 'awp_remove_affiliate_user_role');




