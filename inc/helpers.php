<?php
function awp_is_affiliate_user() {
    if (!is_user_logged_in()) return false;
    $user = wp_get_current_user();
    return in_array('affiliate', $user->roles);
}