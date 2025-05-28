<section class="component partner-listing">
    <div class="partner-listing-inner">
        <?php if(get_sub_field('heading')) : ?>
            <h2><?php echo get_sub_field('heading'); ?></h2>
        <?php endif; ?>
        <div class="partner-listing__partners">
            <?php if(have_rows('partners')) : ?>
                <?php while(have_rows('partners')) : the_row(); ?>
                    <div class="partner-listing__item">
                        <?php if(get_sub_field('partner_link')) : ?>
                            <a href="<?php echo get_sub_field('partner_link')['url'] ?>" target="_blank">
                        <?php endif; ?>
                        <?php if(get_sub_field('partner_name')) : ?>
                            <h3><?php echo get_sub_field('partner_name') ?></h3>
                        <?php endif; ?>
                        <?php if(get_sub_field('partner_link')) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>