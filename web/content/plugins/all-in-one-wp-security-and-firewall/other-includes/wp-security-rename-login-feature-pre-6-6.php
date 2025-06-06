<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

// phpcs:disable WordPress.WP.I18n.MissingArgDomain -- PCP error. Use default WordPress translations.

// Make sure that the WordPress bootstrap has run before continuing.
// AIOS - for our special case we do not want to include wp-load.php
// require __DIR__ . '/wp-load.php';

// Redirect to HTTPS login if forced to use SSL.
if (force_ssl_admin() && ! is_ssl()) {
	$request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
	if (0 === strpos($request_uri, 'http')) {
		wp_safe_redirect(set_url_scheme($request_uri, 'https'));
		exit;
	} else {
		wp_safe_redirect('https://' . isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '' . $request_uri);
		exit;
	}
}

/**
 * Output the login page header.
 *
 * @since 2.1.0
 *
 * @global string      $error         Login error message set by deprecated pluggable wp_login() function
 *                                    or plugins replacing it.
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 * @global string      $action        The action that brought the visitor to the login page.
 *
 * @param string   $title    Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string   $message  Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
 */
function login_header($title = 'Log In', $message = '', $wp_error = null) {
	global $error, $interim_login, $action;

	// Don't index any of these forms.
	add_filter('wp_robots', 'wp_robots_sensitive_page');
	add_action('login_head', 'wp_strict_cross_origin_referrer');

	add_action('login_head', 'wp_login_viewport_meta');

	if (! is_wp_error($wp_error)) {
		$wp_error = new WP_Error();
	}

	// Shake it!
	$shake_error_codes = array('empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password', 'retrieve_password_email_failure');
	/**
	 * Filters the error codes array for shaking the login form.
	 *
	 * @since 3.0.0
	 *
	 * @param array $shake_error_codes Error codes that shake the login form.
	 */
	$shake_error_codes = apply_filters('shake_error_codes', $shake_error_codes);

	if ($shake_error_codes && $wp_error->has_errors() && in_array($wp_error->get_error_code(), $shake_error_codes, true)) {
		add_action('login_footer', 'wp_shake_js', 12);
	}

	$login_title = get_bloginfo('name', 'display');

	/* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
	$login_title = sprintf(__('%1$s &lsaquo; %2$s &#8212; WordPress'), $title, $login_title);

	if (wp_is_recovery_mode()) {
		/* translators: %s: Login screen title. */
		$login_title = sprintf(__('Recovery Mode &#8212; %s'), $login_title);
	}

	/**
	 * Filters the title tag content for login page.
	 *
	 * @since 4.9.0
	 *
	 * @param string $login_title The page title, with extra context added.
	 * @param string $title       The original page title.
	 */
	$login_title = apply_filters('login_title', $login_title, $title);

	?><!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php echo esc_html($login_title); ?></title>
	<?php

	wp_enqueue_style('login');

	/*
	 * Remove all stored post data on logging out.
	 * This could be added by add_action('login_head'...) like wp_shake_js(),
	 * but maybe better if it's not removable by plugins.
	 */
	if ('loggedout' === $wp_error->get_error_code()) {
		?>
		<script>if ("sessionStorage" in window) {try{for(var key in sessionStorage) {if (key.indexOf("wp-autosave-")!=-1) {sessionStorage.removeItem(key)}}}catch(e) {}};</script>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the login page.
	 *
	 * @since 3.1.0
	 */
	do_action('login_enqueue_scripts');

	/**
	 * Fires in the login page header after scripts are enqueued.
	 *
	 * @since 2.1.0
	 */
	do_action('login_head');

	$login_header_url = 'https://wordpress.org/';

	/**
	 * Filters link URL of the header logo above login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $login_header_url Login header logo URL.
	 */
	$login_header_url = apply_filters('login_headerurl', $login_header_url);

	$login_header_title = '';

	/**
	 * Filters the title attribute of the header logo above login form.
	 *
	 * @since 2.1.0
	 * @deprecated 5.2.0 Use {@see 'login_headertext'} instead.
	 *
	 * @param string $login_header_title Login header logo title attribute.
	 */
	$login_header_title = apply_filters_deprecated(
		'login_headertitle',
		array($login_header_title),
		'5.2.0',
		'login_headertext',
		__('Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead.') // phpcs:ignore UpdraftPlus.Translation.MultipleSentence.MultipleSentenceInsideTranslationFunction -- ignore this to use WordPress translation
	);

	$login_header_text = empty($login_header_title) ? __('Powered by WordPress') : $login_header_title;

	/**
	 * Filters the link text of the header logo above the login form.
	 *
	 * @since 5.2.0
	 *
	 * @param string $login_header_text The login header logo link text.
	 */
	$login_header_text = apply_filters('login_headertext', $login_header_text);

	$classes = array('login-action-' . $action, 'wp-core-ui');

	if (is_rtl()) {
		$classes[] = 'rtl';
	}

	if ($interim_login) {
		$classes[] = 'interim-login';
		?>
		<style type="text/css">html{background-color: transparent;}</style>
		<?php

		if ('success' === $interim_login) {
			$classes[] = 'interim-login-success';
		}
	}

	$classes[] = ' locale-' . sanitize_html_class(strtolower(str_replace('_', '-', get_locale())));

	/**
	 * Filters the login page body classes.
	 *
	 * @since 3.5.0
	 *
	 * @param array  $classes An array of body classes.
	 * @param string $action  The action that brought the visitor to the login page.
	 */
	$classes = apply_filters('login_body_class', $classes, $action);
	?>
	</head>
	<body class="login no-js <?php echo esc_attr(implode(' ', $classes)); ?>">
	<script type="text/javascript">
		document.body.className = document.body.className.replace('no-js','js');
	</script>
	<?php
	/**
	 * Fires in the login page header after the body tag is opened.
	 *
	 * @since 4.6.0
	 */
	do_action('login_header');
	?>
	<div id="login">
		<h1 role="presentation" class="wp-login-logo"><a href="<?php echo esc_url($login_header_url); ?>"><?php echo esc_html($login_header_text); ?></a></h1>
	<?php
	/**
	 * Filters the message to display above the login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $message Login message text.
	 */
	$message = apply_filters('login_message', $message);

	if (! empty($message)) {
		echo wp_kses_post($message) . "\n";
	}

	// In case a plugin uses $error rather than the $wp_errors object.
	if (! empty($error)) {
		$wp_error->add('error', $error);
		unset($error);
	}

	if ($wp_error->has_errors()) {
		$errors   = '';
		$messages = '';

		foreach ($wp_error->get_error_codes() as $code) {
			$severity = $wp_error->get_error_data($code);
			foreach ($wp_error->get_error_messages($code) as $error_message) {
				if ('message' === $severity) {
					$messages .= '	' . $error_message . "<br />\n";
				} else {
					$errors .= '	' . $error_message . "<br />\n";
				}
			}
		}

		if (! empty($errors)) {
			/**
			 * Filters the error messages displayed above the login form.
			 *
			 * @since 2.1.0
			 *
			 * @param string $errors Login error message.
			 */
			echo '<div id="login_error">' . wp_kses_post(apply_filters('login_errors', $errors)) . "</div>\n";
		}

		if (! empty($messages)) {
			/**
			 * Filters instructional messages displayed above the login form.
			 *
			 * @since 2.5.0
			 *
			 * @param string $messages Login messages.
			 */
			echo '<p class="message" id="login-message">' . wp_kses_post(apply_filters('login_messages', $messages)) . "</p>\n";
		}
	}
} // End of login_header().

/**
 * Outputs the footer for the login page.
 *
 * @since 3.1.0
 *
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 * @global AIO_WP_Security $aio_wp_security
 *
 * @param string $input_id Which input to auto-focus.
 */
function login_footer($input_id = '') {
	global $interim_login, $aio_wp_security;

	// Don't allow interim logins to navigate away from the page.
	if (! $interim_login) {
		?>
		<p id="backtoblog"><a href="<?php echo esc_url(home_url('/')); ?>">
		<?php

		/* translators: %s: Site title. */
		printf(esc_html_x('&larr; Go to %s', 'site'), esc_html(get_bloginfo('title', 'display')));
		?>
		</a></p>
		<?php

		the_privacy_policy_link('<div class="privacy-policy-page-link">', '</div>');
	}

	?>
	</div><?php // End of <div id="login">. ?>
	
	<?php
		/**
		 * Filters the Languages select input activation on the login screen.
		 *
		 * @since 5.9.0
		 *
		 * @param bool Whether to display the Languages select input on the login screen.
		 */
	if (!$interim_login && apply_filters('login_display_language_dropdown', true)) {
		$languages = get_available_languages();

		if (!empty($languages)) {
			?>
			<div class="language-switcher">
				<form id="language-switcher" action="" method="get">

					<label for="language-switcher-locales">
						<span class="dashicons dashicons-translation" aria-hidden="true"></span>
						<span class="screen-reader-text">
							<?php
							/* translators: Hidden accessibility text. */
							esc_html_e('Language');
							?>
						</span>
					</label>

					<?php
					$args = array(
						'id'                          => 'language-switcher-locales',
						'name'                        => 'wp_lang',
						'selected'                    => determine_locale(),
						'show_available_translations' => false,
						'explicit_option_en_us'       => true,
						'languages'                   => $languages,
					);

					/**
					 * Filters default arguments for the Languages select input on the login screen.
					 *
					 * The arguments get passed to the wp_dropdown_languages() function.
					 *
					 * @since 5.9.0
					 *
					 * @param array $args Arguments for the Languages select input on the login screen.
					 */
					wp_dropdown_languages(apply_filters('login_language_dropdown_args', $args));
					?>

					<?php if ($interim_login) { ?>
						<input type="hidden" name="interim-login" value="1" />
					<?php } ?>

					<?php // phpcs:disable WordPress.Security.NonceVerification.Recommended -- PCP warning. No nonce. ?>
					<?php if (isset($_GET['redirect_to']) && '' !== $_GET['redirect_to']) { ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr(sanitize_url(wp_unslash($_GET['redirect_to']))); ?>" />
					<?php } ?>

					<?php if (isset($_GET['action']) && '' !== $_GET['action']) { ?>
						<input type="hidden" name="action" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET['action']))); ?>" />
					<?php } ?>
					<?php // phpcs:disable WordPress.Security.NonceVerification.Recommended -- PCP warning. No nonce. ?>

					<?php if (!get_option('permalink_structure')) { ?>
						<input type="hidden" name="<?php echo esc_attr($aio_wp_security->configs->get_value('aiowps_login_page_slug'));?>" value="" />
					<?php } ?>
					
						<input type="submit" class="button" value="<?php esc_attr_e('Change'); ?>">

					</form>
				</div>
		<?php } ?>
	<?php } ?>
	<?php

	if (!empty($input_id)) {
		?>
		<script type="text/javascript">
		try{document.getElementById('<?php echo esc_js($input_id); ?>').focus();}catch(e) {}
		if (typeof wpOnload=='function')wpOnload();
		</script>
		<?php
	}

	/**
	 * Fires in the login page footer.
	 *
	 * @since 3.1.0
	 */
	do_action('login_footer');
	?>
	<div class="clear"></div>
	</body>
	</html>
	<?php
}

/**
 * Outputs the Javascript to handle the form shaking on the login page.
 *
 * @since 3.0.0
 */
function wp_shake_js() {
	?>
	<script type="text/javascript">
	document.querySelector('form').classList.add('shake');
	</script>
	<?php
}

/**
 * Outputs the viewport meta tag for the login page.
 *
 * @since 3.7.0
 */
function wp_login_viewport_meta() {
	?>
	<meta name="viewport" content="width=device-width" />
	<?php
}


//
// Main.
//

$action = isset($_REQUEST['action']) ? sanitize_text_field(wp_unslash($_REQUEST['action'])) : 'login';
$errors = new WP_Error();

if (isset($_GET['key'])) {
	$action = 'resetpass';
}

if (isset($_GET['checkemail'])) {
	$action = 'checkemail';
}

$default_actions = array(
	'confirm_admin_email',
	'postpass',
	'logout',
	'lostpassword',
	'retrievepassword',
	'resetpass',
	'rp',
	'register',
	'checkemail',
	'confirmaction',
	'login',
	WP_Recovery_Mode_Link_Service::LOGIN_ACTION_ENTERED,
);

// Validate action so as to default to the login screen.
if (! in_array($action, $default_actions, true) && false === has_filter('login_form_' . $action)) {
	$action = 'login';
}

nocache_headers();

header('Content-Type: ' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset'));

if (defined('RELOCATE') && RELOCATE) { // Move flag is set.
	$path_info = isset($_SERVER['PATH_INFO']) ? sanitize_text_field(wp_unslash($_SERVER['PATH_INFO'])) : '';
	$php_self = isset($_SERVER['PHP_SELF']) ? sanitize_text_field(wp_unslash($_SERVER['PHP_SELF'])) : '';

	if ('' !== $path_info && $path_info !== $php_self) {
		$_SERVER['PHP_SELF'] = str_replace($path_info, '', $php_self);
	}

	$url = dirname(set_url_scheme('http://' . isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '' . $php_self));

	if (get_option('siteurl') !== $url) {
		update_option('siteurl', $url);
	}
}

setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);

if (SITECOOKIEPATH !== COOKIEPATH) {
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
}

if (isset($_GET['wp_lang'])) {
	setcookie('wp_lang', sanitize_text_field(wp_unslash($_GET['wp_lang'])), 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
}

/**
 * Fires when the login form is initialized.
 *
 * @since 3.2.0
 */
do_action('login_init');

/**
 * Fires before a specified login form action.
 *
 * The dynamic portion of the hook name, `$action`, refers to the action
 * that brought the visitor to the login form. Actions include 'postpass',
 * 'logout', 'lostpassword', etc.
 *
 * @since 2.8.0
 */
do_action("login_form_{$action}");

$http_post = (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
$interim_login = isset($_REQUEST['interim-login']);

/**
 * Filters the separator used between login form navigation links.
 *
 * @since 4.9.0
 *
 * @param string $login_link_separator The separator used between login form navigation links.
 */
$login_link_separator = apply_filters('login_link_separator', ' | ');

switch ($action) {

	case 'confirm_admin_email':
		/*
		 * Note that `is_user_logged_in()` will return false immediately after logging in
		 * as the current user is not set, see wp-includes/pluggable.php.
		 * However this action runs on a redirect after logging in.
		 */
		if (! is_user_logged_in()) {
			wp_safe_redirect(wp_login_url());
			exit;
		}

		if (! empty($_REQUEST['redirect_to'])) {
			$redirect_to = sanitize_url(wp_unslash($_REQUEST['redirect_to']));
		} else {
			$redirect_to = admin_url();
		}

		if (current_user_can('manage_options')) {
			$admin_email = get_option('admin_email');
		} else {
			wp_safe_redirect($redirect_to);
			exit;
		}

		/**
		 * Filters the interval for dismissing the admin email confirmation screen.
		 *
		 * If `0` (zero) is returned, the "Remind me later" link will not be displayed.
		 *
		 * @since 5.3.1
		 *
		 * @param int $interval Interval time (in seconds). Default is 3 days.
		 */
		$remind_interval = (int) apply_filters('admin_email_remind_interval', 3 * DAY_IN_SECONDS);

		if (! empty($_GET['remind_me_later'])) {
			if (! wp_verify_nonce(sanitize_key(wp_unslash($_GET['remind_me_later'])), 'remind_me_later_nonce')) {
				wp_safe_redirect(wp_login_url());
				exit;
			}

			if ($remind_interval > 0) {
				update_option('admin_email_lifespan', time() + $remind_interval);
			}

			$redirect_to = add_query_arg('admin_email_remind_later', 1, $redirect_to);
			wp_safe_redirect($redirect_to);
			exit;
		}

		if (! empty($_POST['correct-admin-email'])) {
			if (! check_admin_referer('confirm_admin_email', 'confirm_admin_email_nonce')) {
				wp_safe_redirect(wp_login_url());
				exit;
			}

			/**
			 * Filters the interval for redirecting the user to the admin email confirmation screen.
			 *
			 * If `0` (zero) is returned, the user will not be redirected.
			 *
			 * @since 5.3.0
			 *
			 * @param int $interval Interval time (in seconds). Default is 6 months.
			 */
			$admin_email_check_interval = (int) apply_filters('admin_email_check_interval', 6 * MONTH_IN_SECONDS);

			if ($admin_email_check_interval > 0) {
				update_option('admin_email_lifespan', time() + $admin_email_check_interval);
			}

			wp_safe_redirect($redirect_to);
			exit;
		}

		login_header(__('Confirm your administration email'), '', $errors);

		/**
		 * Fires before the admin email confirm form.
		 *
		 * @since 5.3.0
		 *
		 * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
		 *                         credentials. Note that the error object may not contain any errors.
		 */
		do_action('admin_email_confirm', $errors);
		?>

		<form class="admin-email-confirm-form" name="admin-email-confirm-form" action="<?php echo esc_url(site_url('wp-login.php?action=confirm_admin_email', 'login_post')); ?>" method="post">
			<?php
			/**
			 * Fires inside the admin-email-confirm-form form tags, before the hidden fields.
			 *
			 * @since 5.3.0
			 */
			do_action('admin_email_confirm_form');

			wp_nonce_field('confirm_admin_email', 'confirm_admin_email_nonce');
			?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />

			<h1 class="admin-email__heading">
				<?php esc_html_e('Administration email verification'); ?>
			</h1>
			<p class="admin-email__details">
				<?php esc_html_e('Please verify that the <strong>administration email</strong> for this website is still correct.'); ?>
				<?php

				/* translators: URL to the WordPress help section about admin email. */
				$admin_email_help_url = 'https://wordpress.org/support/article/settings-general-screen/#email-address';

				/* translators: Accessibility text. */
				$accessibility_text = sprintf('<span class="screen-reader-text"> %s</span>', __('(opens in a new tab)'));

				printf(
					'<a href="%s" rel="noopener" target="_blank">%s%s</a>',
					esc_url($admin_email_help_url),
					esc_html__('Why is this important?'),
					wp_kses_post($accessibility_text)
				);
				?>
			</p>
			<p class="admin-email__details">
				<?php

				printf(
					/* translators: %s: Admin email address. */
					esc_html__('Current administration email: %s'),
					'<strong>' . esc_html($admin_email) . '</strong>'
				);
				?>
			</p>
			<p class="admin-email__details">
				<?php esc_html_e('This email may be different from your personal email address.'); ?>
			</p>

			<div class="admin-email__actions">
				<div class="admin-email__actions-primary">
					<?php

					$change_link = admin_url('options-general.php');
					$change_link = add_query_arg('highlight', 'confirm_admin_email', $change_link);
					?>
					<a class="button button-large" href="<?php echo esc_url($change_link); ?>"><?php esc_html_e('Update'); ?></a>
					<input type="submit" name="correct-admin-email" id="correct-admin-email" class="button button-primary button-large" value="<?php esc_attr_e('The email is correct'); ?>">
				</div>
				<?php if ($remind_interval > 0) : ?>
					<div class="admin-email__actions-secondary">
						<?php

						$remind_me_link = wp_login_url($redirect_to);
						$remind_me_link = add_query_arg(
							array(
								'action'          => 'confirm_admin_email',
								'remind_me_later' => wp_create_nonce('remind_me_later_nonce'),
							),
							$remind_me_link
						);
						?>
						<a href="<?php echo esc_url($remind_me_link); ?>"><?php esc_html_e('Remind me later'); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</form>

		<?php

		login_footer();
		break;

	case 'postpass':
		if (! array_key_exists('post_password', $_POST)) {
			wp_safe_redirect(wp_get_referer());
			exit;
		}

		require_once ABSPATH . WPINC . '/class-phpass.php';
		$hasher = new PasswordHash(8, true);

		/**
		 * Filters the life span of the post password cookie.
		 *
		 * By default, the cookie expires 10 days from creation. To turn this
		 * into a session cookie, return 0.
		 *
		 * @since 3.7.0
		 *
		 * @param int $expires The expiry time, as passed to setcookie().
		 */
		$expire = apply_filters('post_password_expires', time() + 10 * DAY_IN_SECONDS);

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Not recommended to sanitize password. It'll be hashed anyway.
		setcookie('wp-postpass_' . COOKIEHASH, $hasher->HashPassword(wp_unslash($_POST['post_password'])), $expire, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);

		wp_safe_redirect(wp_get_referer());
		exit;

	case 'logout':
		check_admin_referer('log-out');

		$user = wp_get_current_user();

		wp_logout();

		if (! empty($_REQUEST['redirect_to'])) {
			$redirect_to           = sanitize_url(wp_unslash($_REQUEST['redirect_to']));
			$requested_redirect_to = $redirect_to;
		} else {
			$redirect_to = add_query_arg(
				array(
					'loggedout' => 'true',
					'wp_lang'   => get_user_locale($user),
				),
				wp_login_url()
			);

			$requested_redirect_to = '';
		}

		/**
		 * Filters the log out redirect URL.
		 *
		 * @since 4.2.0
		 *
		 * @param string  $redirect_to           The redirect destination URL.
		 * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
		 * @param WP_User $user                  The WP_User object for the user that's logging out.
		 */
		$redirect_to = apply_filters('logout_redirect', $redirect_to, $requested_redirect_to, $user);

		wp_safe_redirect($redirect_to);
		exit;

	case 'lostpassword':
	case 'retrievepassword':
		if ($http_post) {
			$errors = retrieve_password();

			if (! is_wp_error($errors)) {
				$redirect_to = ! empty($_REQUEST['redirect_to']) ? sanitize_url(wp_unslash($_REQUEST['redirect_to'])) : 'wp-login.php?checkemail=confirm';
				wp_safe_redirect($redirect_to);
				exit;
			}
		}

		if (isset($_GET['error'])) {
			if ('invalidkey' === $_GET['error']) {
				// translators: %s: 'ERROR'
				$errors->add('invalidkey', sprintf(__('%s: Your password reset link appears to be invalid.') . ' ' . __('Please request a new link below.'), '<strong>' . __('ERROR') . '</strong>'));
			} elseif ('expiredkey' === $_GET['error']) {
				// translators: %s: 'ERROR'
				$errors->add('expiredkey', sprintf(__('%s: Your password reset link has expired.') . ' ' . __('Please request a new link below.'), '<strong>' . __('ERROR') . '</strong>'));
			}
		}

		$lostpassword_redirect = ! empty($_REQUEST['redirect_to']) ? sanitize_url(wp_unslash($_REQUEST['redirect_to'])) : '';
		/**
		 * Filters the URL redirected to after submitting the lostpassword/retrievepassword form.
		 *
		 * @since 3.0.0
		 *
		 * @param string $lostpassword_redirect The redirect destination URL.
		 */
		$redirect_to = apply_filters('lostpassword_redirect', $lostpassword_redirect);

		/**
		 * Fires before the lost password form.
		 *
		 * @since 1.5.1
		 * @since 5.1.0 Added the `$errors` parameter.
		 *
		 * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
		 *                         credentials. Note that the error object may not contain any errors.
		 */
		do_action('lost_password', $errors);

		login_header(__('Lost Password'), '<p class="message">' . __('Please enter your username or email address. You will receive an email message with instructions on how to reset your password.') . '</p>', $errors); // phpcs:ignore UpdraftPlus.Translation.MultipleSentence.MultipleSentenceInsideTranslationFunction -- ignore this to use WordPress translation

		$user_login = '';

		if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below.
			$user_login = wp_unslash($_POST['user_login']); // Remove slashes first

			if (is_email($user_login)) {
				// Sanitize as an email address
				$user_login = sanitize_email($user_login);
			} else {
				// Sanitize as a username
				$user_login = sanitize_user($user_login, true);
			}
		}

		?>

		<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url(network_site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" method="post">
			<p>
				<label for="user_login"><?php esc_html_e('Username or Email Address'); ?></label>
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" autocapitalize="off" />
			</p>
			<?php

			/**
			 * Fires inside the lostpassword form tags, before the hidden fields.
			 *
			 * @since 2.1.0
			 */
			do_action('lostpassword_form');
			?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Get New Password'); ?>">
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Log in'); ?></a>
			<?php

			if (get_option('users_can_register')) {
				$registration_url = sprintf('<a href="%s">%s</a>', esc_url(wp_registration_url()), __('Register'));

				echo esc_html($login_link_separator);

				// This filter is documented in wp-includes/general-template.php
				echo wp_kses_post(apply_filters('register', $registration_url));
			}

			?>
		</p>
		<?php

		login_footer('pass1');
		break;

	case 'resetpass':
	case 'rp':
		list($rp_path) = explode('?', wp_unslash($_SERVER['REQUEST_URI']));
		$rp_cookie       = 'wp-resetpass-' . COOKIEHASH;

		if (isset($_GET['key']) && isset($_GET['login'])) {
			$value = sprintf('%s:%s', wp_unslash($_GET['login']), wp_unslash($_GET['key']));
			setcookie($rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true);

			wp_safe_redirect(remove_query_arg(array('key', 'login')));
			exit;
		}

		if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
			list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);

			$user = check_password_reset_key($rp_key, $rp_login);

			if (isset($_POST['pass1']) && ! hash_equals($rp_key, isset($_POST['rp_key']) ? $_POST['rp_key'] : '')) {
				$user = false;
			}
		} else {
			$user = false;
		}

		if (! $user || is_wp_error($user)) {
			setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);

			if ($user && $user->get_error_code() === 'expired_key') {
				wp_redirect(site_url('wp-login.php?action=lostpassword&error=expiredkey'));
			} else {
				wp_redirect(site_url('wp-login.php?action=lostpassword&error=invalidkey'));
			}

			exit;
		}

		$errors = new WP_Error();
		
		// Check if password is one or all empty spaces.
		if (!empty($_POST['pass1'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- PCP warning. Not recommended to sanitize password.
			$_POST['pass1'] = trim($_POST['pass1']);
			if (empty($_POST['pass1'])) {
				$errors->add('password_reset_empty_space', __('The password cannot be a space or all spaces.'));
			}
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- PCP warning. Not recommended to sanitize password.
		if (!empty($_POST['pass1']) && trim($_POST['pass2']) !== $_POST['pass1']) {
			$errors->add('password_reset_mismatch', __('<strong>Error:</strong> The passwords do not match.'));
		}

		/**
		 * Fires before the password reset procedure is validated.
		 *
		 * @since 3.5.0
		 *
		 * @param WP_Error         $errors WP Error object.
		 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
		 */
		do_action('validate_password_reset', $errors, $user);

		if ((! $errors->has_errors()) && isset($_POST['pass1']) && ! empty($_POST['pass1'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- PCP warning. Not recommended to sanitize password.
			reset_password($user, wp_unslash($_POST['pass1']));
			setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
			login_header(__('Password Reset'), '<p class="message reset-pass">' . __('Your password has been reset.') . ' <a href="' . esc_url(wp_login_url()) . '">' . __('Log in') . '</a></p>');
			login_footer();
			exit;
		}

		wp_enqueue_script('utils');
		wp_enqueue_script('user-profile');

		login_header(__('Reset Password'), '<p class="message reset-pass">' . __('Enter your new password below or generate one.') . '</p>', $errors);
		?>
		<form name="resetpassform" id="resetpassform" action="<?php echo esc_url(network_site_url('wp-login.php?action=resetpass', 'login_post')); ?>" method="post" autocomplete="off">
			<input type="hidden" id="user_login" value="<?php echo esc_attr($rp_login); ?>" autocomplete="off" />

			<div class="user-pass1-wrap">
				<p>
					<label for="pass1"><?php esc_html_e('New password'); ?></label>
				</p>

				<div class="wp-pwd">
					<input type="password" data-reveal="1" data-pw="<?php echo esc_attr(wp_generate_password(16)); ?>" name="pass1" id="pass1" class="input password-input" size="24" value="" autocomplete="off" aria-describedby="pass-strength-result" />

					<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e('Hide password'); ?>">
						<span class="dashicons dashicons-hidden" aria-hidden="true"></span>
					</button>
					<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php esc_html_e('Strength indicator'); ?></div>
				</div>
				<div class="pw-weak">
					<input type="checkbox" name="pw_weak" id="pw-weak" class="pw-checkbox" />
					<label for="pw-weak"><?php esc_html_e('Confirm use of weak password'); ?></label>
				</div>
			</div>

			<p class="user-pass2-wrap">
				<label for="pass2"><?php esc_html_e('Confirm new password'); ?></label>
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
			</p>

			<p class="description indicator-hint"><?php echo esc_html(wp_get_password_hint()); ?></p>
			<br class="clear" />

			<?php

			/**
			 * Fires following the 'Strength indicator' meter in the user password reset form.
			 *
			 * @since 3.9.0
			 *
			 * @param WP_User $user User object of the user whose password is being reset.
			 */
			do_action('resetpass_form', $user);
			?>
			<input type="hidden" name="rp_key" value="<?php echo esc_attr($rp_key); ?>" />
			<p class="submit reset-pass-submit">
				<button type="button" class="button wp-generate-pw hide-if-no-js skip-aria-expanded"><?php esc_html_e('Generate Password'); ?></button>
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Save Password'); ?>">
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Log in'); ?></a>
			<?php

			if (get_option('users_can_register')) {
				$registration_url = sprintf('<a href="%s">%s</a>', esc_url(wp_registration_url()), __('Register'));

				echo esc_html($login_link_separator);

				// This filter is documented in wp-includes/general-template.php
				echo wp_kses_post(apply_filters('register', $registration_url));
			}

			?>
		</p>
		<?php

		login_footer('user_pass');
		break;

	case 'register':
		if (is_multisite()) {
			/**
			 * Filters the Multisite sign up URL.
			 *
			 * @since 3.0.0
			 *
			 * @param string $sign_up_url The sign up URL.
			 */
			wp_redirect(apply_filters('wp_signup_location', network_site_url('wp-signup.php')));
			exit;
		}

		if (! get_option('users_can_register')) {
			wp_redirect(site_url('wp-login.php?registration=disabled'));
			exit;
		}

		$user_login = '';
		$user_email = '';

		if ($http_post) {
			if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- PCP warning. Santizied below.
				$user_login = wp_unslash($_POST['user_login']); // Remove slashes first

				if (is_email($user_login)) {
					// Sanitize as an email address
					$user_login = sanitize_email($user_login);
				} else {
					// Sanitize as a username
					$user_login = sanitize_user($user_login, true);
				}
			}

			if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
				$user_email = sanitize_email(wp_unslash($_POST['user_email']));
			}

			$errors = register_new_user($user_login, $user_email);

			if (! is_wp_error($errors)) {
				$redirect_to = ! empty($_POST['redirect_to']) ? sanitize_url(wp_unslash($_POST['redirect_to'])) : 'wp-login.php?checkemail=registered';
				wp_safe_redirect($redirect_to);
				exit;
			}
		}

		$registration_redirect = ! empty($_REQUEST['redirect_to']) ? sanitize_url(wp_unslash($_REQUEST['redirect_to'])) : '';

		/**
		 * Filters the registration redirect URL.
		 *
		 * @since 3.0.0
		 *
		 * @param string $registration_redirect The redirect destination URL.
		 */
		$redirect_to = apply_filters('registration_redirect', $registration_redirect);

		login_header(__('Registration Form'), '<p class="message register">' . __('Register For This Site') . '</p>', $errors);
		?>
		<form name="registerform" id="registerform" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" method="post" novalidate="novalidate">
			<p>
				<label for="user_login"><?php esc_html_e('Username'); ?></label>
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr(wp_unslash($user_login)); ?>" size="20" autocapitalize="off" />
			</p>
			<p>
				<label for="user_email"><?php esc_html_e('Email'); ?></label>
				<input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr(wp_unslash($user_email)); ?>" size="25" />
			</p>
			<?php

			/**
			 * Fires following the 'Email' field in the user registration form.
			 *
			 * @since 2.1.0
			 */
			do_action('register_form');
			?>
			<p id="reg_passmail">
				<?php esc_html_e('Registration confirmation will be emailed to you.'); ?>
			</p>
			<br class="clear" />
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Register'); ?>">
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Log in'); ?></a>
				<?php echo esc_html($login_link_separator); ?>
			<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?'); ?></a>
		</p>
		<?php

		login_footer('user_login');
		break;

	case 'checkemail':
		$redirect_to = admin_url();
		$errors      = new WP_Error();

		if ('confirm' === $_GET['checkemail']) {
			$errors->add(
				'confirm',
				sprintf(
					/* translators: %s: Link to the login page. */
					__('Check your email for the confirmation link, then visit the <a href="%s">login page</a>.'),
					wp_login_url()
				),
				'message'
			);
		} elseif ('registered' === $_GET['checkemail']) {
			$errors->add(
				'registered',
				sprintf(
					/* translators: %s: Link to the login page. */
					__('Registration complete.') . ' ' . __('Please check your email, then visit the <a href="%s">login page</a>.'),
					wp_login_url()
				),
				'message'
			);
		}

		// This action is documented in wp-login.php
		$errors = apply_filters('wp_login_errors', $errors, $redirect_to);

		login_header(__('Check your email'), '', $errors);
		login_footer();
		break;

	case 'confirmaction':
		if (! isset($_GET['request_id'])) {
			wp_die(esc_html__('Missing request ID.'));
		}

		if (! isset($_GET['confirm_key'])) {
			wp_die(esc_html__('Missing confirm key.'));
		}

		$request_id = (int) $_GET['request_id'];
		$key        = sanitize_text_field(wp_unslash($_GET['confirm_key']));
		$result     = wp_validate_user_request_key($request_id, $key);

		if (is_wp_error($result)) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP_Error is an object and cannot be escaped.
			wp_die($result);
		}

		/**
		 * Fires an action hook when the account action has been confirmed by the user.
		 *
		 * Using this you can assume the user has agreed to perform the action by
		 * clicking on the link in the confirmation email.
		 *
		 * After firing this action hook the page will redirect to wp-login a callback
		 * redirects or exits first.
		 *
		 * @since 4.9.6
		 *
		 * @param int $request_id Request ID.
		 */
		do_action('user_request_action_confirmed', $request_id);

		$message = _wp_privacy_account_request_confirmed_message($request_id);

		login_header(__('User action confirmed.'), $message);
		login_footer();
		exit;

	case 'login':
	default:
		$secure_cookie   = '';
		$customize_login = isset($_REQUEST['customize-login']);

		if ($customize_login) {
			wp_enqueue_script('customize-base');
		}

		// If the user wants SSL but the session is not SSL, force a secure cookie.
		if (! empty($_POST['log']) && ! force_ssl_admin()) {
			$user_name = sanitize_user(wp_unslash($_POST['log']));
			$user      = get_user_by('login', $user_name);

			if (! $user && strpos($user_name, '@')) {
				$user = get_user_by('email', $user_name);
			}

			if ($user) {
				if (get_user_option('use_ssl', $user->ID)) {
					$secure_cookie = true;
					force_ssl_admin(true);
				}
			}
		}

		if (isset($_REQUEST['redirect_to'])) {
			$redirect_to = sanitize_url(wp_unslash($_REQUEST['redirect_to']));
			// Redirect to HTTPS if user wants SSL.
			if ($secure_cookie && false !== strpos($redirect_to, 'wp-admin')) {
				$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
			}
		} else {
			$redirect_to = admin_url();
		}

		$reauth = empty($_REQUEST['reauth']) ? false : true;

		$user = wp_signon(array(), $secure_cookie);

		if (empty($_COOKIE[LOGGED_IN_COOKIE])) {
			if (headers_sent()) {
				$user = new WP_Error(
					'test_cookie',
					sprintf(
						// translators: 1: 'ERROR', 2: 'this documentation'(Browser cookie documentation link), 3: 'support forums'(Support forums link)
						__('%1$s: Cookies are blocked due to unexpected output.') . ' ' . __('For help, please see %2$s or try the %3$s.'),
						'<strong>' . __('ERROR') . '</strong>',
						'<a href="https://wordpress.org/support/article/cookies/">' . __('this documentation') . '</a>',
						'<a href="https://wordpress.org/support/forums/">' . __('support forums') . '</a>'
					)
				);
			} elseif (isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE])) {
				// If cookies are disabled, we can't log in even with a valid user and password.
				$user = new WP_Error(
					'test_cookie',
					sprintf(
						// translators: 1: 'ERROR', 2: 'enable cookies'(Browser cookie documentation link)
						__('%1$s: Cookies are blocked or not supported by your browser.') . ' ' . __('You must %2$s to use WordPress.'),
						'<strong>' . __('ERROR') . '</strong>',
						'<a href="https://wordpress.org/support/article/cookies/#enable-cookies-in-your-browser">' . __('enable cookies') . '</a>'
					)
				);
			}
		}

		$requested_redirect_to = isset($_REQUEST['redirect_to']) ? sanitize_url(wp_unslash($_REQUEST['redirect_to'])) : '';
		/**
		 * Filters the login redirect URL.
		 *
		 * @since 3.0.0
		 *
		 * @param string           $redirect_to           The redirect destination URL.
		 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
		 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
		 */
		$redirect_to = apply_filters('login_redirect', $redirect_to, $requested_redirect_to, $user);

		if (! is_wp_error($user) && ! $reauth) {
			if ($interim_login) {
				$message       = '<p class="message">' . __('You have logged in successfully.') . '</p>';
				$interim_login = 'success';
				login_header('', $message);
				?>
				</div>
				<?php

				// This action is documented in wp-login.php
				do_action('login_footer');

				if ($customize_login) {
					?>
					<script type="text/javascript">setTimeout(function() { new wp.customize.Messenger({ url: '<?php echo esc_js(wp_customize_url()); ?>', channel: 'login' }).send('login') }, 1000);</script>
					<?php
				}

				?>
				</body></html>
				<?php

				exit;
			}

			// Check if it is time to add a redirect to the admin email confirmation screen.
			if (is_a($user, 'WP_User') && $user->exists() && $user->has_cap('manage_options')) {
				$admin_email_lifespan = (int) get_option('admin_email_lifespan');

				// If `0` (or anything "falsey" as it is cast to int) is returned, the user will not be redirected
				// to the admin email confirmation screen.
				// This filter is documented in wp-login.php
				$admin_email_check_interval = (int) apply_filters('admin_email_check_interval', 6 * MONTH_IN_SECONDS);

				if ($admin_email_check_interval > 0 && time() > $admin_email_lifespan) {
					$redirect_to = add_query_arg(
						array(
							'action'  => 'confirm_admin_email',
							'wp_lang' => get_user_locale($user),
						),
						wp_login_url($redirect_to)
					);
				}
			}

			if ((empty($redirect_to) || 'wp-admin/' === $redirect_to || admin_url() === $redirect_to)) {
				// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
				if (is_multisite() && ! get_active_blog_for_user($user->ID) && ! is_super_admin($user->ID)) {
					$redirect_to = user_admin_url();
				} elseif (is_multisite() && ! $user->has_cap('read')) {
					$redirect_to = get_dashboard_url($user->ID);
				} elseif (! $user->has_cap('edit_posts')) {
					$redirect_to = $user->has_cap('read') ? admin_url('profile.php') : home_url();
				}

				wp_redirect($redirect_to);
				exit;
			}

			wp_safe_redirect($redirect_to);
			exit;
		}

		$errors = $user;
		// Clear errors if loggedout is set.
		if (! empty($_GET['loggedout']) || $reauth) {
			$errors = new WP_Error();
		}

		if (empty($_POST) && $errors->get_error_codes() === array('empty_username', 'empty_password')) {
			$errors = new WP_Error('', '');
		}

		if ($interim_login) {
			if (! $errors->has_errors()) {
				$errors->add('expired', __('Your session has expired. Please log in to continue where you left off.'), 'message'); // phpcs:ignore UpdraftPlus.Translation.MultipleSentence.MultipleSentenceInsideTranslationFunction -- ignore this to use WordPress translation
			}
		} else {
			// Some parts of this script use the main login form to display a message.
			if (isset($_GET['loggedout']) && sanitize_text_field(wp_unslash($_GET['loggedout']))) {
				$errors->add('loggedout', __('You are now logged out.'), 'message');
			} elseif (isset($_GET['registration']) && 'disabled' === $_GET['registration']) {
				$errors->add('registerdisabled', __('<strong>Error:</strong> User registration is currently not allowed.'));
			} elseif (strpos($redirect_to, 'about.php?updated')) {
				$errors->add('updated', __('<strong>You have successfully updated WordPress!</strong> Please log back in to see what&#8217;s new.'), 'message');
			} elseif (WP_Recovery_Mode_Link_Service::LOGIN_ACTION_ENTERED === $action) {
				$errors->add('enter_recovery_mode', __('Recovery Mode Initialized. Please log in to continue.'), 'message'); // phpcs:ignore UpdraftPlus.Translation.MultipleSentence.MultipleSentenceInsideTranslationFunction -- ignore this to use WordPress translation
			} elseif (isset($_GET['redirect_to']) && false !== strpos(sanitize_url(wp_unslash($_GET['redirect_to'])), 'wp-admin/authorize-application.php')) {
				$query_component = wp_parse_url(sanitize_url(wp_unslash($_GET['redirect_to'])), PHP_URL_QUERY);
				parse_str($query_component, $query);

				if (! empty($query['app_name'])) {
					/* translators: 1: Website name, 2: Application name. */
					$message = sprintf('Please log in to %1$s to authorize %2$s to connect to your account.', get_bloginfo('name', 'display'), '<strong>' . esc_html($query['app_name']) . '</strong>');
				} else {
					/* translators: %s: Website name. */
					$message = sprintf('Please log in to %s to proceed with authorization.', get_bloginfo('name', 'display'));
				}

				$errors->add('authorize_application', $message, 'message');
			}
		}

		/**
		 * Filters the login page errors.
		 *
		 * @since 3.6.0
		 *
		 * @param WP_Error $errors      WP Error object.
		 * @param string   $redirect_to Redirect destination URL.
		 */
		$errors = apply_filters('wp_login_errors', $errors, $redirect_to);

		// Clear any stale cookies.
		if ($reauth) {
			wp_clear_auth_cookie();
		}

		login_header(__('Log In'), '', $errors);

		if (isset($_POST['log'])) {
			$user_login = ('incorrect_password' === $errors->get_error_code() || 'empty_password' === $errors->get_error_code()) ? esc_attr(sanitize_text_field(wp_unslash($_POST['log']))) : '';
		}

		$rememberme = ! empty($_POST['rememberme']);

		$aria_describedby = '';
		$has_errors       = $errors->has_errors();

		if ($has_errors) {
			$aria_describedby = ' aria-describedby="login_error"';
		}

		if ($has_errors && 'message' === $errors->get_error_data()) {
			$aria_describedby = ' aria-describedby="login-message"';
		}

		wp_enqueue_script('user-profile');

		//aiowps - this check is necessary because otherwise if variables are undefined we get a warning!
		if (empty($user_login)) {
				$user_login = '';
		}
		if (empty($error)) {
				$error = '';
		}
		?>

		<form name="loginform" id="loginform" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
			<p>
				<label for="user_login"><?php esc_html_e('Username or Email Address'); ?></label>
				<input type="text" name="log" id="user_login"<?php echo wp_kses_post($aria_describedby); ?> class="input" value="<?php echo esc_attr($user_login); ?>" size="20" autocapitalize="off" />
			</p>

			<div class="user-pass-wrap">
				<label for="user_pass"><?php esc_html_e('Password'); ?></label>
				<div class="wp-pwd">
					<input type="password" name="pwd" id="user_pass"<?php echo wp_kses_post($aria_describedby); ?> class="input password-input" value="" size="20" />
					<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e('Show password'); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
			</div>
			<?php

			/**
			 * Fires following the 'Password' field in the login form.
			 *
			 * @since 2.1.0
			 */
			do_action('login_form');
			?>
			<p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked($rememberme); ?> /> <label for="rememberme"><?php esc_html_e('Remember Me'); ?></label></p>
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Log in'); ?>">
				<?php

				if ($interim_login) {
					?>
					<input type="hidden" name="interim-login" value="1" />
					<?php
				} else {
					?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
					<?php
				}

				if ($customize_login) {
					?>
					<input type="hidden" name="customize-login" value="1" />
					<?php
				}

				?>
				<input type="hidden" name="testcookie" value="1" />
			</p>
		</form>

		<?php

		if (! $interim_login) {
			?>
			<p id="nav">
				<?php

				if (get_option('users_can_register')) {
					$registration_url = sprintf('<a href="%s">%s</a>', esc_url(wp_registration_url()), __('Register'));

					// This filter is documented in wp-includes/general-template.php
					echo wp_kses_post(apply_filters('register', $registration_url));

					echo esc_html($login_link_separator);
				}
				
				$html_link = sprintf('<a href="%s">%s</a>', esc_url(wp_lostpassword_url()), __('Lost your password?'));

				/**
				 * Filters the link that allows the user to reset the lost password.
				 *
				 * @since 6.1.0
				 *
				 * @param string $html_link HTML link to the lost password form.
				 */
				echo wp_kses_post(apply_filters('lost_password_html_link', $html_link));
				?>
			</p>
			<?php
		}

		$login_script  = 'function wp_attempt_focus() {';
		$login_script .= 'setTimeout(function() {';
		$login_script .= 'try {';

		if ($user_login) {
			$login_script .= 'd = document.getElementById("user_pass"); d.value = "";';
		} else {
			$login_script .= 'd = document.getElementById("user_login");';

			if ($errors->get_error_code() === 'invalid_username') {
				$login_script .= 'd.value = "";';
			}
		}

		$login_script .= 'd.focus(); d.select();';
		$login_script .= '} catch(er) {}';
		$login_script .= '}, 200);';
		$login_script .= "}\n"; // End of wp_attempt_focus().

		/**
		 * Filters whether to print the call to `wp_attempt_focus()` on the login screen.
		 *
		 * @since 4.8.0
		 *
		 * @param bool $print Whether to print the function call. Default true.
		 */
		if (apply_filters('enable_login_autofocus', true) && ! $error) {
			$login_script .= "wp_attempt_focus();\n";
		}

		// Run `wpOnload()` if defined.
		$login_script .= "if (typeof wpOnload === 'function') { wpOnload() }";
		?>
		<script type="text/javascript">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- NO user input to escape. ?>
			<?php echo $login_script; ?>
		</script>
		<?php

		if ($interim_login) {
			?>
			<script type="text/javascript">
			(function() {
				try {
					var i, links = document.getElementsByTagName('a');
					for (i in links) {
						if (links[i].href) {
							links[i].target = '_blank';
							links[i].rel = 'noopener';
						}
					}
				} catch(er) {}
			}());
			</script>
			<?php
		}

		login_footer();
		break;
} // End action switch.
// phpcs:enable WordPress.WP.I18n.MissingArgDomain -- Uses default WordPress translations.