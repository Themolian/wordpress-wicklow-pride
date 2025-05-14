<?php 
    $heading = get_sub_field('heading');
    $body = get_sub_field('body');
?>
<section class="basic-content body-content component">
    <div class="basic-content-inner">
        <?php if($heading) : ?>
            <h2><?php echo $heading; ?></h2>
        <?php endif; ?>
        <?php 
            if($body) {
                echo $body;
            }
        ?>
    </div>
</section>