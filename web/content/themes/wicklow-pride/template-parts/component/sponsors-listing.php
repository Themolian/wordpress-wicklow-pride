<div class="sponsors-listing component">
    <div class="sponsors-listing-inner">
        <?php if(get_sub_field('heading')) : ?>
            <h2><?php the_sub_field('heading'); ?></h2>
        <?php endif; ?>
        <?php if(get_sub_field('body')) : ?>
            <?php the_sub_field('body'); ?>
        <?php endif; ?>
        <?php if(have_rows('sponsors')) : ?>
            <div class="sponsors">
                <?php while(have_rows('sponsors')) : the_row() ?>
                    <?php if(get_sub_field('sponsor_link')) : ?><a href="<?php echo get_sub_field('sponsor_link')['url'] ?>"><?php endif; ?>

                    <img src="<?php echo get_sub_field('sponsor_logo')['url']; ?>" alt="<?php echo get_sub_field('sponsor_logo')['alt']; ?>">

                    <?php if(get_sub_field('sponsor_link')) : ?></a><?php endif; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>