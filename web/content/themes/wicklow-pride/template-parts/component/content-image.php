<?php 
    $image = get_sub_field('image');
    $imagePos = get_sub_field('image_orientation');
    $heading = get_sub_field('heading');
    $body = get_sub_field('body');
?>
<div class="component content-image">
    <div class="content-image-inner">
        <?php if($image && $imagePos !== 'Left') : ?>
            <div class="content-image__image">
                <img src="<?php echo $image['url'] ?>" alt="<?php $image['alt']; ?>">
            </div>
        <?php endif; ?>
        <div class="content-image__body">
            <?php if($heading) : ?>
                <h2><?php echo $heading; ?></h2>
            <?php endif; ?>
            <?php 
                if($body) {
                    echo $body;
                }
            ?>
        </div>
        <?php if($image && $imagePos === 'Left' ) : ?>
            <div class="content-image__image">
                <img src="<?php echo $image['url'] ?>" alt="<?php $image['alt']; ?>">
            </div>
        <?php endif; ?>
    </div>
</div>