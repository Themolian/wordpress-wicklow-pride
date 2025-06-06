<?php if (!defined('ABSPATH')) die('Access denied.'); ?>
<h2><?php esc_html_e('Custom .htaccess rules settings', 'all-in-one-wp-security-and-firewall'); ?></h2>
		<form action="" method="POST" id="aiowpsec-save-custom-rules-settings-form">
			<div class="aio_blue_box">
				<?php
				$info_msg = '';

				$info_msg .= '<p>'. esc_html__('This feature can be used to apply your own custom .htaccess rules and directives.', 'all-in-one-wp-security-and-firewall').'</p>';
				$info_msg .= '<p>'. esc_html__('It is useful for when you want to tweak our existing firewall rules or when you want to add your own.', 'all-in-one-wp-security-and-firewall').'</p>';
				$info_msg .= '<p>'. esc_html__('NOTE: This feature can only be used if your site is hosted using the Apache webserver, or another that uses .htaccess files.', 'all-in-one-wp-security-and-firewall').'</p>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped earlier.
				echo $info_msg;
				?>
			</div>
			<div class="aio_yellow_box">
				<?php
				/* translators: %s: Warning */
				$info_msg_2 = '<p>'. sprintf(esc_html__('%s: Only use this feature if you know what you are doing.', 'all-in-one-wp-security-and-firewall'), '<strong>' . esc_html__('Warning', 'all-in-one-wp-security-and-firewall') . '</strong>').'</p>';
				$info_msg_2 .= '<p>'.esc_html__('Incorrect .htaccess rules or directives can break or prevent access to your site.', 'all-in-one-wp-security-and-firewall').'</p>';
				$info_msg_2 .= '<p>'.esc_html__('It is your responsibility to ensure that you are entering the correct code!', 'all-in-one-wp-security-and-firewall').'</p>';
				$info_msg_2 .= '<p>'.esc_html__('If you break your site you will need to access your server via FTP or something similar and then edit your .htaccess file and delete the changes you made.', 'all-in-one-wp-security-and-firewall').'</p>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped earlier.
				echo $info_msg_2;
				?>
			</div>

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php esc_html_e('Custom .htaccess rules', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
				<div class="inside">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Enable custom .htaccess rules', 'all-in-one-wp-security-and-firewall'); ?>:</th>
							<td>
								<div class="aiowps_switch_container">
									<?php AIOWPSecurity_Utility_UI::setting_checkbox(esc_html__('Enable this to activate the custom rules entered in the text box below', 'all-in-one-wp-security-and-firewall'), 'aiowps_enable_custom_rules', '1' == $aio_wp_security->configs->get_value('aiowps_enable_custom_rules')); ?>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Place custom rules at the top', 'all-in-one-wp-security-and-firewall');?>:</th>
							<td>
								<div class="aiowps_switch_container">
									<?php AIOWPSecurity_Utility_UI::setting_checkbox(esc_html__('Enable this if you want to place your custom rules at the beginning of all the rules applied by this plugin', 'all-in-one-wp-security-and-firewall'), 'aiowps_place_custom_rules_at_top', '1' == $aio_wp_security->configs->get_value('aiowps_place_custom_rules_at_top')); ?>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="aiowps_custom_rules"><?php esc_html_e('Enter custom .htaccess rules:', 'all-in-one-wp-security-and-firewall'); ?></label></th>
							<td>
								<textarea id="aiowps_custom_rules" name="aiowps_custom_rules" rows="35" cols="50"><?php echo esc_html($aio_wp_security->configs->get_value('aiowps_custom_rules')); ?></textarea>
								<br />
								<span class="description"><?php esc_html_e('Enter your custom .htaccess rules/directives.', 'all-in-one-wp-security-and-firewall');?></span>
							</td>
						</tr>
					</table>
				</div></div>
			<input type="submit" name="aiowps_save_custom_rules_settings" value="<?php esc_html_e('Save custom rules', 'all-in-one-wp-security-and-firewall'); ?>" class="button-primary">
		</form>
