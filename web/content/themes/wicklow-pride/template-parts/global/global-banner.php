<?php 
    $banner = get_field('global_banner', 'options');
?>

<div class="global-banner">
    <?php if($banner['image']) : ?>
        <div class="global-banner__image">
            <img src="<?php echo $banner['image']['url']; ?>" alt="<?php echo $banner['image']['alt']; ?>">
        </div>
    <?php endif; ?>
    <div class="global-banner__body">
        <?php if($banner['kicker']) : ?>
            <h3 class="kicker"><?php echo $banner['kicker']; ?></h3>
        <?php endif; ?>
        <?php if($banner['heading']) : ?>
            <h2><?php echo $banner['heading']; ?></h2>
        <?php endif; ?>
        <?php
            if($banner['body']) {
                echo $banner['body'];
            }
        ?>
        <?php if($banner['button_link']) : ?>
            <a class="button button--ghost button--ghost--border" href="<?php echo $banner['button_link']['url'] ?>"><?php echo $banner['button_link']['title']; ?></a>
        <?php endif; ?>
    </div>
</div>