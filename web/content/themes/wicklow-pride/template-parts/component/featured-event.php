<?php
    $post = get_sub_field('event');
?>
<section class="component featured-event">
    <div class="featured-event-inner">
        <?php 
            setup_postdata( $post );

            $title = $post[0]->post_title;
            $id = $post[0]->ID;
            $event_date = get_field('start_date', $id);
            $description = get_field('teaser_description', $id);
            $link = get_the_permalink( $id );
        ?>
        <h2>Events</h2>
        <article class="featured-event__card">
            <?php if(get_the_post_thumbnail( $id )) : ?>
                <div class="featured-event__image">
                    <?php echo get_the_post_thumbnail($id); ?>
                </div>
            <?php endif; ?>
            <div class="featured-event__body">
                <div class="featured-event__body-header">
                    <time datetime="<?php echo $event_date; ?>"><?php echo $event_date; ?></time>
                </div>
                <h3><?php echo $title; ?></h3>
                <?php 
                    if($description) {
                        echo $description;
                    }
                ?>
                <a href="<?php echo $link ?>" class="button button--ghost button--ghost--border">Check it out</a>
            </div>
        </article>
        <?php wp_reset_postdata(); ?>
        <a href="<?php echo get_site_url(); ?>/events" class="button button--ghost button--ghost--border">All Events</a>
    </div>
</section>