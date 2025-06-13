<?php

// Display affiliate price for simple products
add_filter('woocommerce_get_price_html', function($price, $product) {
    if (!awp_is_affiliate_user() || !$product->is_type('simple')) {
        return $price;
    }

    $affiliate_price = $product->get_meta('affiliate_price', true);
	if ($affiliate_price && is_numeric($affiliate_price)) {
        return sprintf(
            '<span class="awp-price">%s: %s</span>',
            esc_html__('Affiliate Price', 'affiliate-wholesale-pricing'),
            wc_price($affiliate_price)
        );
    }
    

    return $price;
}, 10, 2);

add_action('woocommerce_product_bulk_edit_end', function() {
    ?><div class="inline-edit-group">
        <label>
            <span class="title"><?php esc_html_e('Affiliate Price', 'affiliate-wholesale-pricing'); ?></span>
            <input type="text" name="affiliate_price" class="text wc_input_price" placeholder="<?php esc_attr_e('– No Change –', 'affiliate-wholesale-pricing'); ?>" />
        </label>
    </div><?php
});

add_action('woocommerce_product_bulk_edit_save', function($product) {
    if (isset($_REQUEST['affiliate_price'])) {
        $product->update_meta_data('affiliate_price', wc_clean($_REQUEST['affiliate_price']));
    }
});

