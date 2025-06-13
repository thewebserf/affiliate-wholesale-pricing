<?php
add_action('woocommerce_before_calculate_totals', function($cart) {
    // Skip in admin or for non-affiliates
    if (is_admin() && !defined('DOING_AJAX')) return;
    if (!awp_is_affiliate_user()) return;

    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $product_id = $cart_item['variation_id'] ?: $cart_item['product_id'];
        
        // Get and validate affiliate price
        $affiliate_price = get_post_meta($product_id, 'affiliate_price', true);
        if (!is_numeric($affiliate_price)) continue;

        // Set price (with type safety)
        $product->set_price((float) $affiliate_price);
        
        // Optional: Store original price for reference
        if (!isset($cart_item['awp_original_price'])) {
            $cart_item['awp_original_price'] = $product->get_price();
        }
    }
}, 20);