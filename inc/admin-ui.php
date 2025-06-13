<?php

// Add affiliate price to simple products (admin)
add_action('woocommerce_product_options_pricing', function() {
    global $product_object;
    
    // Only show for simple products
    if ($product_object->is_type('simple')) {
        woocommerce_wp_text_input([
            'id'          => 'affiliate_price',
            'class'       => 'wc_input_price short',
            'label'       => __('Affiliate Price', 'affiliate-wholesale-pricing'), 
            'desc_tip'    => true,
            'description' => __('Price displayed to users with the "Affiliate" role.', 'affiliate-wholesale-pricing'),
            'data_type'   => 'price',
            'value'       => $product_object->get_meta('affiliate_price', true) 
        ]);
    }
});

// Save affiliate price for simple products
add_action('woocommerce_process_product_meta', function($post_id) {
    if (isset($_POST['affiliate_price'])) {
        $price = wc_format_decimal(sanitize_text_field($_POST['affiliate_price'])); 
        update_post_meta($post_id, 'affiliate_price', $price);
    }
});

// Add affiliate price to variations (admin)
add_action('woocommerce_variation_options_pricing', function ($loop, $variation_data, $variation) {
    woocommerce_wp_text_input([
        'id' => "affiliate_price_{$loop}",
        'name' => "affiliate_price[{$loop}]",
        'value' => wc_format_decimal($variation->get_meta('affiliate_price', true)),
        'label' => __('Affiliate Price ($)', 'woocommerce'),
        'desc_tip' => true,
        'description' => __('Enter affiliate-specific price for this variation.'),
        'data_type' => 'price',
        'type' => 'number',
        'custom_attributes' => [
            'step' => '0.01',
            'min' => '0'
        ]
    ]);
}, 10, 3);

// Save affiliate price for variations
add_action('woocommerce_save_product_variation', function ($variation_id, $i) {
    if (isset($_POST['affiliate_price'][$i])) {
        $price = wc_format_decimal($_POST['affiliate_price'][$i]);
        update_post_meta($variation_id, 'affiliate_price', $price);
    }
}, 10, 2);

// Clear cache when affiliate prices are updated
add_action('woocommerce_save_product_variation', function($variation_id) {
    $product_id = wp_get_post_parent_id($variation_id);
    delete_transient('awp_min_price_' . $product_id);
}, 10);

add_action('woocommerce_process_product_meta', function($product_id) {
    delete_transient('awp_min_price_' . $product_id);
});