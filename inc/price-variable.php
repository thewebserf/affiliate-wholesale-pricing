<?php

// Show affiliate pricing on the front-end for variable products
add_filter('woocommerce_get_price_html', function($price, $product) {
    if (!awp_is_affiliate_user() || !$product->is_type('variable')) {
        return $price;
    }
	
    // Generate a unique cache key (include user role if prices vary per user)
    $cache_key = 'awp_min_price_' . $product->get_id();
    $min_price = get_transient($cache_key);

    // Cache miss: Calculate and store
    if (false === $min_price) {
        $min_price = null;
        foreach ($product->get_children() as $variation_id) {
            $aff_price = get_post_meta($variation_id, 'affiliate_price', true);
            if (is_numeric($aff_price)) {
                $min_price = ($min_price === null) ? $aff_price : min($min_price, $aff_price);
            }
        }
        set_transient($cache_key, $min_price, 12 * HOUR_IN_SECONDS); // Adjust TTL as needed
    }

    $min_price = null;
    foreach ($product->get_children() as $variation_id) {
        $aff_price = get_post_meta($variation_id, 'affiliate_price', true);
        if (is_numeric($aff_price)) {
            $aff_price = floatval($aff_price);
            $min_price = ($min_price === null) ? $aff_price : min($min_price, $aff_price);
        }
    }

    // No affiliate pricing found
    if ($min_price === null) {
        return $price . '<small> ' . esc_html__('(No affiliate pricing available)', 'affiliate-wholesale-pricing') . '</small>';
    }

    // Compare with regular price
    $regular_min_price = $product->get_variation_regular_price('min');
    if ($regular_min_price && $regular_min_price > $min_price) {
        return sprintf(
            '<span class="awp-label">%s</span> %s <del>%s</del>',
            esc_html__('From Affiliate Price:', 'affiliate-wholesale-pricing'),
            wc_price($min_price),
            wc_price($regular_min_price)
        );
    }

    // Default display
    return sprintf(
        '<span class="awp-label">%s</span> %s',
        esc_html__('From Affiliate Price:', 'affiliate-wholesale-pricing'),
        wc_price($min_price)
    );
}, 10, 2);

