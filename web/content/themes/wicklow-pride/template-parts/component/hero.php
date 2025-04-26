<?php $hero = get_field('hero'); ?>

<section class="hero<?php echo is_front_page() ? ' hero--homepage' : '' ?>">
    <div class="hero-inner">
        <?php if($hero['heading']) : ?>
            <h1><?php echo $hero['heading']; ?></h1>
        <?php endif; ?>
        <?php if($hero['subheading']) : ?>
            <p class="hero-subheading"><?php echo $hero['subheading']; ?></p>
        <?php endif; ?>
        <?php if($hero['cta']) : ?>
            <div class="hero-footer">
                <a href="<?php echo $hero['cta']['url'] ?>" class="button button--ghost button--ghost--border"><?php echo $hero['cta']['title']; ?></a>
            </div>
        <?php endif; ?>
        <?php if($hero['image'] && empty($hero['images'])) : ?>
            <div class="hero-image">
                <img src="<?php echo $hero['image']['url'] ?>" alt="<?php echo $hero['image']['alt']; ?>">
            </div>
        <?php elseif(!empty($hero['images'])) : ?>
            <?php get_template_part( 'template-parts/component/hero/carousel.php' ); ?>
        <?php endif; ?>
    </div>
</section>