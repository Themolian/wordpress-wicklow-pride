<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Wicklow_Pride
 */

?>
	<?php get_template_part('template-parts/global/newsletter'); ?>
	<?php get_template_part('template-parts/global/global-banner'); ?>
	<footer class="footer">
		<div class="footer-inner">
			<div class="footer-col footer-logo">
				<img src="<?php echo get_field('organisation_logo', 'options')['url'] ?>" alt="Wicklow Pride">
			</div>
			<div class="footer-col footer-contact">
				<?php if(have_rows('social_media', 'options')) : ?>
					<div class="footer-socials">
						<?php while(have_rows('social_media', 'options')) : the_row(); ?>
							<a href="<?php get_sub_field('social_media_url') ?>" data-social-type="<?php echo get_sub_field('social_media_title'); ?>">
								<img src="<?php echo get_theme_file_uri(); ?>/dist/svg/<?php echo strtolower(get_sub_field('social_media_title')); ?>.svg" alt="Follow us on <?php echo get_sub_field('social_media_title'); ?>">
							</a>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
				<?php if(get_field('email_address', 'options')) : ?>
					<a class="mail" href="mailto:<?php echo get_field('email_address', 'options'); ?>">
						<img src="<?php echo get_theme_file_uri(); ?>/dist/svg/email.svg" alt="">
						<?php echo get_field('email_address', 'options'); ?>
					</a>
				<?php endif; ?>
				<?php if(get_field('phone_number', 'options')) : ?>
					<a class="phone" href="mailto:<?php echo get_field('phone_number', 'options'); ?>">
						<img src="<?php echo get_theme_file_uri(); ?>/dist/svg/phone.svg" alt="">
						<?php echo get_field('phone_number', 'options'); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="footer-col footer-charity">
				<?php echo get_field('charity_section', 'options'); ?>
			</div>
			<div class="footer-col footer-attr">
				<p>&copy; Wicklow Pride <?php echo date('Y'); ?></p>
				<p>Designed and developed by <a href="https://www.jamiecurran.tech/" target="_blank">Jamie Curran</a></p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
