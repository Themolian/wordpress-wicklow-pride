<div class="component banner">
    <div class="banner-inner">
        <div class="banner-wrap">
            <div class="banner-body">
                <?php if(get_sub_field('heading')) : ?>
                    <h2><?php echo get_sub_field('heading') ?></h2>
                <?php endif; ?>
                <?php if(get_sub_field('body')) : ?>
                    <div class="banner-content">
                        <?php echo get_sub_field('body') ?>
                    </div>
                <?php endif; ?>
                <?php if(get_sub_field('button_link')) : ?>
                    <a href="<?php echo get_sub_field('button_link')['url'] ?>" class="button button--ghost button--ghost--border"><?php echo get_sub_field('button_link')['title'] ?></a>
                <?php endif; ?>
            </div>
            <?php if(get_sub_field('image')) : ?>
                <div class="banner-image">
                    <img src="<?php echo get_sub_field('image')['url']; ?>" alt="<?php echo get_sub_field('image')['alt']; ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>