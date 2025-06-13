<?php
/**
 * Admin notification email template for new affiliate registrations.
 * 
 * Variables passed:
 * - $username (string)
 * - $email (string)
 * - $first_name (string)
 * - $last_name (string)
 * - $website (string)
 * - $company (string)
 * - $phone (string)
 */
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php esc_html_e('New Affiliate Registration', 'affiliate-wholesale-pricing'); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #777; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px 0; vertical-align: top; }
        td.label { font-weight: bold; width: 30%; }
        .highlight { background-color: #f8f8f8; padding: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><?php esc_html_e('New Affiliate Registration', 'affiliate-wholesale-pricing'); ?></h1>
            <p><?php esc_html_e('A new user has registered as an affiliate.', 'affiliate-wholesale-pricing'); ?></p>
        </div>

        <!-- Highlighted User Summary -->
        <div class="highlight">
            <p><strong><?php esc_html_e('Affiliate:', 'affiliate-wholesale-pricing'); ?></strong> <?php echo esc_html($first_name . ' ' . $last_name); ?></p>
            <p><strong><?php esc_html_e('Email:', 'affiliate-wholesale-pricing'); ?></strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
            <?php if (!empty($company)) : ?>
                <p><strong><?php esc_html_e('Company:', 'affiliate-wholesale-pricing'); ?></strong> <?php echo esc_html($company); ?></p>
            <?php endif; ?>
        </div>

        <!-- Detailed Information -->
        <table>
            <?php if (!empty($website)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Website:', 'affiliate-wholesale-pricing'); ?></td>
                <td><a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($phone)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Phone:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($phone); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="label"><?php esc_html_e('Username:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($username); ?></td>
            </tr>
            <tr>
                <td class="label"><?php esc_html_e('Registration Date:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html(date_i18n(get_option('date_format'))); ?></td>
            </tr>
        </table>

        <!-- Admin Actions -->
        <div style="margin-top: 25px; text-align: center;">
            <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" style="background: #2271b1; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block;">
                <?php esc_html_e('View User Profile', 'affiliate-wholesale-pricing'); ?>
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><?php echo esc_html(get_bloginfo('name')); ?></p>
            <p><a href="<?php echo esc_url(admin_url('users.php')); ?>"><?php esc_html_e('Manage Users', 'affiliate-wholesale-pricing'); ?></a></p>
        </div>
    </div>
</body>
</html>