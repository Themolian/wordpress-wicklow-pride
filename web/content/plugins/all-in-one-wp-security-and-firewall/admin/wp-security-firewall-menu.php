<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

use AIOWPS\Firewall\Allow_List;

class AIOWPSecurity_Firewall_Menu extends AIOWPSecurity_Admin_Menu {

	/**
	 * Firewall menu slug
	 *
	 * @var string
	 */
	protected $menu_page_slug = AIOWPSEC_FIREWALL_MENU_SLUG;

	/**
	 * Constructor adds menu for Firewall
	 */
	public function __construct() {
		parent::__construct(__('Firewall', 'all-in-one-wp-security-and-firewall'));
	}

	/**
	 * This function will setup the menus tabs by setting the array $menu_tabs
	 *
	 * @return void
	 */
	protected function setup_menu_tabs() {
		$menu_tabs = array(
			'php-rules' => array(
				'title' => __('PHP rules', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_php_rules'),
			),
			'htaccess-rules' => array(
				'title' => __('.htaccess rules', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_htaccess_rules'),
				'display_condition_callback' => array('AIOWPSecurity_Utility', 'allow_to_write_to_htaccess'),
			),
			'6g-firewall' => array(
				'title' => __('6G firewall rules', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_6g_firewall'),
				'display_condition_callback' => array('AIOWPSecurity_Utility_Permissions', 'is_main_site_and_super_admin'),
			),
			'internet-bots' => array(
				'title' => __('Internet bots', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_internet_bots'),
				'display_condition_callback' => array('AIOWPSecurity_Utility_Permissions', 'is_main_site_and_super_admin'),
			),
			'block-and-allow-lists' => array(
				'title' => __('Block & allow lists', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_block_and_allow_lists'),
				'display_condition_callback' => array('AIOWPSecurity_Utility_Permissions', 'is_main_site_and_super_admin'),
			),
			'advanced-settings' => array(
				'title' => __('Advanced settings', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_advanced_settings'),
				'display_condition_callback' => array('AIOWPSecurity_Utility_Permissions', 'is_main_site_and_super_admin'),
			)
		);

		$this->menu_tabs = array_filter($menu_tabs, array($this, 'should_display_tab'));
	}

	/**
	 * Renders the PHP Firewall settings tab
	 *
	 * @return void
	 */
	protected function render_php_rules() {
		global $aio_wp_security;

		$aio_wp_security->include_template('wp-admin/firewall/php-firewall-rules.php');
	}

	/**
	 * Renders the Htaccess Firewall tab
	 *
	 * @return void
	 */
	protected function render_htaccess_rules() {
		global $aio_wp_security;

		$aio_wp_security->include_template('wp-admin/firewall/htaccess-firewall-rules.php');
	}
	
	/**
	 * Renders the 6G Blacklist Firewall Rules tab
	 *
	 * @return void
	 */
	protected function render_6g_firewall() {
		global $aio_wp_security, $aiowps_feature_mgr;
		$aiowps_firewall_config = AIOS_Firewall_Resource::request(AIOS_Firewall_Resource::CONFIG);

		$block_request_methods = array_map('strtolower', AIOS_Abstracted_Ids::get_firewall_block_request_methods());

		// Load required data from config
		if (!empty($aiowps_firewall_config)) {
			// firewall config is available
			$methods = $aiowps_firewall_config->get_value('aiowps_6g_block_request_methods');
			if (empty($methods)) {
				$methods = array();
			}

			$blocked_query     = (bool) $aiowps_firewall_config->get_value('aiowps_6g_block_query');
			$blocked_request   = (bool) $aiowps_firewall_config->get_value('aiowps_6g_block_request');
			$blocked_referrers = (bool) $aiowps_firewall_config->get_value('aiowps_6g_block_referrers');
			$blocked_agents    = (bool) $aiowps_firewall_config->get_value('aiowps_6g_block_agents');

			if (empty($methods) && (!$blocked_query && !$blocked_request && !$blocked_referrers && !$blocked_agents) && '1' == $aio_wp_security->configs->get_value('aiowps_enable_6g_firewall')) {
				$aio_wp_security->configs->set_value('aiowps_enable_6g_firewall', '');
				$aio_wp_security->configs->save_config();
				$aiowps_feature_mgr->check_feature_status_and_recalculate_points();
			}

		} else {
			// firewall config is unavailable
			?>
				<div class="notice notice-error">
					<p><strong><?php esc_html_e('All-In-One Security', 'all-in-one-wp-security-and-firewall'); ?></strong></p>
					<p><?php esc_html_e('We were unable to access the firewall\'s configuration file:', 'all-in-one-wp-security-and-firewall');?></p>
					<pre style="max-width: 100%;background-color: #f0f0f0;border: #ccc solid 1px;padding: 10px;white-space: pre-wrap;"><?php echo esc_html(AIOWPSecurity_Utility_Firewall::get_firewall_rules_path() . 'settings.php'); ?></pre>
					<p><?php esc_html_e('As a result, the firewall will be unavailable.', 'all-in-one-wp-security-and-firewall');?></p>
					<p><?php esc_html_e('Please check your PHP error log for further information.', 'all-in-one-wp-security-and-firewall');?></p>
					<p><?php esc_html_e('If you\'re unable to locate your PHP log file, please contact your web hosting company to ask them where it can be found on their setup.', 'all-in-one-wp-security-and-firewall');?></p>
				</div>
			<?php

			//set default variables
			$methods           = array();
			$blocked_query     = false;
			$blocked_request   = false;
			$blocked_referrers = false;
			$blocked_agents    = false;
		}

		$advanced_options_disabled = '1' != $aio_wp_security->configs->get_value('aiowps_enable_6g_firewall');
		$settings = array_merge(array('methods' => $methods), compact('blocked_query', 'blocked_request', 'blocked_referrers', 'blocked_agents', 'block_request_methods', 'aiowps_firewall_config', 'advanced_options_disabled'));
		$aio_wp_security->include_template('wp-admin/firewall/6g.php', false, $settings);
	}

	/**
	 * Renders the Internet Bots tab
	 *
	 * @return void
	 */
	protected function render_internet_bots() {
		global $aio_wp_security;

		$aio_wp_security->include_template('wp-admin/firewall/internet-bots.php');
	}


	/**
	 * Renders the Advanced settings tab.
	 *
	 * @return void
	 */
	protected function render_advanced_settings() {
		global $aio_wp_security;

		$aio_wp_security->include_template('wp-admin/firewall/advanced-settings.php');
	}

	/**
	 * Renders ban user tab for blacklist IPs and user agents
	 *
	 * @global $aio_wp_security
	 * @global $aiowps_feature_mgr
	 *
	 * @return void
	 */
	protected function render_block_and_allow_lists() {
		global $aio_wp_security, $aiowps_feature_mgr;
		$result = 1;

		$aiowps_firewall_allow_list = AIOS_Firewall_Resource::request(AIOS_Firewall_Resource::ALLOW_LIST);
		$aiowps_banned_ip_addresses = $aio_wp_security->configs->get_value('aiowps_banned_ip_addresses');
		$aiowps_banned_user_agents = $aio_wp_security->configs->get_value('aiowps_banned_user_agents');
		$allowlist = $aiowps_firewall_allow_list::get_ips();

		$aio_wp_security->include_template('wp-admin/firewall/block-and-allow-lists.php', false, array('result' => $result, 'aiowps_feature_mgr' => $aiowps_feature_mgr, 'aiowps_banned_user_agents' => $aiowps_banned_user_agents, 'aiowps_banned_ip_addresses' => $aiowps_banned_ip_addresses, 'allowlist' => $allowlist));
	}

	/**
	 * Validates posted user agent list and set, save as config.
	 *
	 * @global $aio_wp_security
	 * @global $aiowps_firewall_config
	 *
	 * @param string $banned_user_agents
	 *
	 * @return int
	 */
	private function validate_user_agent_list($banned_user_agents) {
		global $aio_wp_security;
		$aiowps_firewall_config = AIOS_Firewall_Resource::request(AIOS_Firewall_Resource::CONFIG);
		$submitted_agents = AIOWPSecurity_Utility::splitby_newline_trim_filter_empty($banned_user_agents);
		$agents = array_unique(array_filter(array_map('sanitize_text_field', $submitted_agents), 'strlen'));
		$aio_wp_security->configs->set_value('aiowps_banned_user_agents', implode("\n", $agents));
		$aiowps_firewall_config->set_value('aiowps_blacklist_user_agents', $agents);
		$_POST['aiowps_banned_user_agents'] = ''; // Clear the post variable for the banned address list
		return 1;
	}
}
