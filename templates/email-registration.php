<?php
/**
 * Email template for affiliate registration notifications.
 * 
 * Variables passed to this template:
 * - $username (string): The new user's username
 * - $email (string): User's email address
 * - $first_name (string)
 * - $last_name (string)
 * - $website (string): User's website URL
 * - $company (string)
 * - $phone (string)
 * - $password (string, optional): Only included for user-facing emails
 */

// Exit if accessed directly
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><?php esc_html_e('New Affiliate Registration', 'affiliate-wholesale-pricing'); ?></h1>
        </div>

        <!-- User Details -->
        <table>
            <tr>
                <td class="label"><?php esc_html_e('Name:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($first_name . ' ' . $last_name); ?></td>
            </tr>
            <tr>
                <td class="label"><?php esc_html_e('Username:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($username); ?></td>
            </tr>
            <tr>
                <td class="label"><?php esc_html_e('Email:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($email); ?></td>
            </tr>
            <?php if (!empty($company)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Company:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($company); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($phone)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Phone:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($phone); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($website)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Website:', 'affiliate-wholesale-pricing'); ?></td>
                <td><a href="<?php echo esc_url($website); ?>"><?php echo esc_html($website); ?></a></td>
            </tr>
            <?php endif; ?>
            <?php if (isset($password)) : ?>
            <tr>
                <td class="label"><?php esc_html_e('Password:', 'affiliate-wholesale-pricing'); ?></td>
                <td><?php echo esc_html($password); ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><?php echo esc_html(get_bloginfo('name')); ?></p>
            <p>
                <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(home_url()); ?></a>
            </p>
        </div>
    </div>
</body>
</html>