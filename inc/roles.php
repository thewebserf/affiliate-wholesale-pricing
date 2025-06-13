<?php
function awp_add_affiliate_user_role() {
    if (!get_role('affiliate')) {
        
        $customer_role = get_role('customer');
        $capabilities = $customer_role ? $customer_role->capabilities : [
            'read' => true,
            'level_0' => true
        ];

        
        add_role(
            'affiliate',
            __('Affiliate', 'affiliate-wholesale-pricing'), // Translatable
            $capabilities
        );
    }
}

function awp_remove_affiliate_user_role() {
    if (get_role('affiliate')) {
        remove_role('affiliate');
    }
}
