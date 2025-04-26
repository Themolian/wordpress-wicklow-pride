<div class="sponsors-listing component">
    <div class="sponsors-listing-inner">
        <?php if(get_sub_field('heading')) : ?>
            <h2><?php the_sub_field('heading'); ?></h2>
        <?php endif; ?>
        <?php if(get_sub_field('body')) : ?>
            <?php the_sub_field('body'); ?>
        <?php endif; ?>
    </div>
</div>