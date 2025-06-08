<?php
    if(!isset($args['hero'])) { 
        $hero = get_field('hero');
    } else {
        $hero = $args['hero'];
    }
?>

<section class="hero<?php echo is_front_page() ? ' hero--homepage' : '' ?>">
    <div class="hero-inner">
        <?php 
            $heading = get_the_title();
            if(!empty($hero['heading'])) {
                $heading = $hero['heading'];
            }
        ?>
        <h1><?php echo $heading; ?></h1>
        <?php if(!empty($hero['subheading'])) : ?>
            <p class="hero-subheading"><?php echo $hero['subheading']; ?></p>
        <?php endif; ?>
        <?php if(!empty($hero['cta'])) : ?>
            <div class="hero-footer">
                <a href="<?php echo $hero['cta']['url'] ?>" class="button button--ghost button--ghost--border"><?php echo $hero['cta']['title']; ?></a>
            </div>
        <?php endif; ?>
        <?php if(!empty($hero['image']) && empty($hero['images'])) : ?>
            <div class="hero-image">
                <img src="<?php echo $hero['image']['url'] ?>" alt="<?php echo $hero['image']['alt']; ?>">
            </div>
        <?php elseif(!empty(!empty($hero['images']))) : ?>
            <?php get_template_part( 'template-parts/component/hero/carousel.php' ); ?>
        <?php else: ?>
            <div class="hero-image">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php endif; ?>
    </div>
	<?php
		if ( function_exists( 'wicklow_pride_woocommerce_header_cart' ) && is_shop() ) {
			wicklow_pride_woocommerce_header_cart();
		}
	?>
</section>