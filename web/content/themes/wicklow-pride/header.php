<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Wicklow_Pride
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wicklow-pride' ); ?></a>
	<header class="header">
		<div class="header-inner">
			<div class="header-logo">
				<a href="/">
					<img src="<?php echo get_field('organisation_logo', 'options')['url'] ?>" alt="Wicklow Pride">
				</a>
			</div>
			<nav class="main-navigation global-nav" data-component="nav-double" aria-label="Main Navigation" id="js-click-nav-vertical">
				<button class="button button--ghost" data-trigger="mobile-nav" style="display: none;" aria-expanded="false">
					<span class="visuallyhidden">Menu</span>
				</button>
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary-menu',
							'container' => false,
							'menu-id' => '',
							'menu_class' => 'desktop-menu clean-list'
						)
					);
				?>
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary-menu',
							'container' => false,
							'menu-id' => '',
							'menu_class' => 'mobile-menu clean-list'
						)
					);
				?>
			</nav>
			<!-- <div class="menu-toggle">
				<span class="line-1"></span>
				<span class="line-2">Menu Toggle</span>
				<span class="line-3"></span>
			</div> -->
		</div>
	</header>
	<?php 
		if(!is_archive() && !is_home()) {
			get_template_part( 'template-parts/component/hero' );
		} 
	?>