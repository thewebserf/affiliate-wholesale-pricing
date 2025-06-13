<?php

// Shortcode to display registration form
add_shortcode('affiliate_customer_registration', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['affiliate_registration_nonce'])) {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['affiliate_registration_nonce'], 'affiliate_registration')) {
            return '<p>Security check failed. Please try again.</p>';
        }

        // Sanitize and collect form data
		$first_name = sanitize_text_field($_POST['first_name']);
		$last_name  = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $website = esc_url_raw($_POST['website'] ?? '');
        $company = sanitize_text_field($_POST['company'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
		
		$base_username = sanitize_user(strtolower($first_name . '.' . $last_name), true);
		$username = $base_username;
		
		$user = get_user_by('email', $email);
		
		if ($user) {
		
			echo '<div id="ajax-login-container">';
			echo '<p>This email is already registered. Please log in below.</p>';
			echo do_shortcode('[awp_ajax_login_form email="' . esc_attr($email) . '"]');
			echo '</div>';
			exit;
			
		}else{
			 while (username_exists($username)) {
				$username = $base_username . '-' . strtoupper(wp_generate_password(3, false, false));
			}
			$password = wp_generate_password(12, true);
			
			$user_id = wp_create_user($username, $password, $email);
			if (is_wp_error($user_id)) {
				return '<p>Registration error: ' . $user_id->get_error_message() . '</p>';
			}
			$user = new WP_User($user_id);
			$user->set_role('customer');
			
			update_user_meta($user_id, 'first_name', $first_name);
			update_user_meta($user_id, 'last_name', $last_name);
			update_user_meta($user_id, 'billing_company', $company);
			update_user_meta($user_id, 'billing_phone', $phone);
			wp_update_user(['ID' => $user_id, 'user_url' => $website]);
			
			$created = true;
		}
		
		if (is_wp_error($user_id)) {
			wp_die('Registration failed: ' . $user_id->get_error_message(), 'Error');
		}


		
		$to = get_option('admin_email');
		$subject = 'New Affiliate Registration';
		$message = '
			<html>
			<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			</head>
			<body>
				<table bgcolor="#fafafa" style="border-collapse: collapse; width: 100%!important; height: 100%; background-color: #fafafa; padding: 20px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, 'Lucida Grande', sans-serif;  font-size: 100%; line-height: 1.6;">
					<tr>
					<td bgcolor="#FFFFFF" style="border: 1px solid #eeeeee; background-color: #ffffff; border-radius:5px; display:block!important; max-width:600px!important; margin:0 auto!important; clear:both!important;">
					<div style="padding:20px; max-width:600px; margin:0 auto; display:block;">
					<table style="width: 100%;   border-collapse: collapse;  border: none;">
					<thead>
						<tr>
							<td colspan="4">
								<p style="text-align: center; display: block;  padding-bottom:20px;  margin-bottom:40px; border-bottom:1px solid #dddddd;"><img src="https://three.health/wp-content/uploads/2024/09/three-health-logo-small.png" alt="ThreeHealth logo" width="300" height="72"/></p>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								<h1 style="font-weight: 200; font-size: 36px; margin: 20px 0 30px 0; color: #333333;">New Affiliate Registration</h1>
							</td>
						</tr>
						<tr>
							<td colspan="1">
								<p style=" font-weight: bold; font-size:16px; color: #333333;">Name:</p>
							</td>
							<td colspan="3">
								<p style=" font-weight: normal; font-size:16px; color: #333333;">' . esc_html($username) . '</p>
							</td>
						</tr>
						<tr>
							<td colspan="1">
								<p style=" font-weight: bold; font-size:16px; color: #333333;">:Phone</p>
							</td>
							<td colspan="3">
								<p style=" font-weight: normal; font-size:16px; color: #333333;">[phone]</p>
							</td>
						</tr>
						<tr>
							<td colspan="1">
								<p style=" font-weight: bold; font-size:16px; color: #333333;">:Email</p>
							</td>
							<td colspan="3">
								<p style=" font-weight: normal; font-size:16px; color: #333333;">[your-email]</p>
							</td>
						</tr>
						<tr>
							<td colspan="1">
								<p style="font-weight: bold; font-size:16px; color: #333333;">Company:</p>
							</td>
							<td colspan="3">
								<p style="font-weight: normal; font-size:16px; color: #333333;">[SelectService]</p>
							</td>
						</tr>
						<tr>
							<td colspan="1">
								<p style="font-weight: bold; font-size:16px; color: #333333;">Website:</p>
							</td>
							<td colspan="3">
								<p style="font-weight: normal; font-size:16px; color: #333333;">[preferreddate], [SelectTime]</p>
							</td>
						</tr>
			<tr><td colspan="4"><div style="height:40px;"></div></td></tr>

					</tbody>
					<tfoot style="background: #f1f1f1;">
						<tr>
							<td colspan="4">
								<p><a href="https://three.health/" style="font-size:20px; text-decoration:none; text-align: center; display: block; padding-top:20px; font-weight: bold; margin:10px 0 10px 0; color: #333333;">Three Health</a></p>
								<p><a href="tel:+14256060022" style="font-size:24px; text-decoration:none; text-align: center; display: block; margin-bottom:20px; color: #333333; padding-bottom:10px;">(425) 606-0022</a></p>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<p style="text-align:center; font-size:12px; line-height: 1.35; margin-bottom:20px">
								<span style="font-weight:bold; font-size:14px">Lynnwood Clinic</span><br>
								3500 188th St SW, Ste 250<br>
								Lynnwood, WA 98037
								</p>
							</td>
							<td colspan="2">
								<p style="text-align:center; font-size:16px; font-size:12px; line-height: 1.35; margin-bottom:20px">
								<span style="font-weight:bold; font-size:14px">Bellingham Clinic</span><br>
								1313 E. Maple St.<br>
								Bellingham, Wa 98225
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<p style="text-align:center; padding:20px 0;">
									<a href="mailto:contactus@three.health" style="text-decoration:none; display: inline-block; margin:4px;"><img src="https://three.health/wp-content/uploads/2024/09/email.png" alt="email us" height="36" width="36" /></a>
									<a href="https://open.spotify.com/show/3bBMJGnTxPu1G8xOOv7qZ6?si=7d63a637d8644173" style="text-decoration:none; display: inline-block; margin:4px;"><img src="https://three.health/wp-content/uploads/2024/09/spotify.png" alt="listen to For Fats Sake" height="36" width="36" /></a>
									<a href="https://www.facebook.com/threehealthclinic/" style="text-decoration:none; display: inline-block; margin:4px;"><img src="https://three.health/wp-content/uploads/2024/09/facebook.png" alt="View us on Facebook" height="36" width="36" /></a>
									<a href="https://www.instagram.com/threehealth/" style="text-decoration:none; display: inline-block; margin:4px;"><img src="https://three.health/wp-content/uploads/2024/09/instagram.png" alt="View us on Instagram" height="36" width="36" /></a>
							</td>
						</tr>
					
					</tfoot>
					</table>
					</div>
					</td>

					</tr>
				</table>
			</body>
			</html>
		
		
		
			<html>
			<head>
			  <title>New Customer Registration</title>
			</head>
			<body>
			  <h2>New Customer Registered</h2>
			  <p><strong>Username:</strong> ' . esc_html($username) . '</p>
			  <p><strong>Email:</strong> ' . esc_html($email) . '</p>
			  <p><strong>Website:</strong> ' . esc_html($website) . '</p>
			  <p><strong>Company:</strong> ' . esc_html($company) . '</p>
			  <p><strong>Phone:</strong> ' . esc_html($phone) . '</p>
			</body>
			</html>
			';
		$headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($to, $subject, $message, $headers);

        return '<p>Thank you for registering! You can now <a href="' . wp_login_url() . '">log in</a>.</p>';
    }

    return affiliate_customer_registration_form_html();
});

// Form HTML as a separate function for clarity
function affiliate_customer_registration_form_html() {
    $html = '<form method="post" action="">
				<p><label>First Name<br><input type="text" name="first_name" required></label></p>
				<p><label>Last Name<br><input type="text" name="last_name" required></label></p>
				<p><label>Email<br><input type="email" name="email" required></label></p>
				<p><label>Website<br><input type="url" name="website"></label></p>
				<p><label>Company<br><input type="text" name="company"></label></p>
				<p><label>Phone<br><input type="text" name="phone"></label></p>
				' . wp_nonce_field('affiliate_registration', 'affiliate_registration_nonce', true, false) . '
				<p><input type="submit" value="Register"></p>
			</form>';
			
    return $html;
}

add_shortcode('awp_ajax_login_form', function($atts) {
    $atts = shortcode_atts(['email' => ''], $atts);
    ob_start();
    ?>
    <form id="awp-login-form">
        <input type="hidden" name="action" value="awp_ajax_login">
        <input type="hidden" name="email" value="<?php echo esc_attr($atts['email']); ?>">
        <p><label>Password<br><input type="password" name="password" required></label></p>
		<input type="hidden" name="awp_nonce" value="<?php echo wp_create_nonce('awp_ajax_login'); ?>">
        <p><button type="submit">Login</button></p>
        <div id="awp-login-message"></div>
    </form>
    <script>
        document.querySelector('#awp-login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            const res = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: data
            });

            const result = await res.text();
            document.querySelector('#awp-login-message').innerHTML = result;

            if (result.includes('success')) {
                location.reload(); 
            }
        });
    </script>
    <?php
    return ob_get_clean();
});
