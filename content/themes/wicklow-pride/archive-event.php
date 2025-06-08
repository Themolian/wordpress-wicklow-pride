<?php
    get_header();
    $events = new WP_Query(array(
        'post_type' => 'event',
        'post_status' => 'publish'
    ));
    $page_id = get_page_by_path( 'events' )->ID;
    
    get_template_part('template-parts/component/hero', null, array(
        'hero' => get_field('hero', $page_id)
    ));
?>
<nav class="breadcrumbs" aria-role="Breadcrumb">
    <div class="breadcrumbs-inner">
        <div class="l-cluster">
            <ul class="clean-list" role="list">
                <li><a href="/">Home</a></li>
                <li aria-current="page"><a href="<?php echo get_site_url() ?>/events">Events</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="events-inner">
    <div class="section-header">
        <?php if(get_field('events_heading', $page_id)) : ?>
            <h2><?php echo get_field('events_heading', $page_id); ?></h2>
        <?php endif; ?>
        <?php if(get_field('events_page_intro', $page_id)) : ?>
            <div class="page-intro">
                <?php echo get_field('events_page_intro', $page_id); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="section-content">
        <?php if(get_field('highlighted_event', $page_id)) : ?>
            <?php 
                $post = get_field('highlighted_event', $page_id);
                setup_postdata( $post );

                $title = $post[0]->post_title;
                $id = $post[0]->ID;
                $event_date = get_field('start_date', $id);
                $description = get_field('teaser_description', $id);
                $link = get_the_permalink( $id );
                $thumbnail = get_the_post_thumbnail( $id );
                $currentDate = date("Y/m/d");
            ?>
            <div class="events__featured-event">
                <div class="events__featured-event__content">
                        <div class="featured-event__image">
                            <?php echo $thumbnail; ?>
                            <!-- <img src="{{ featuredEventImage.getUrl('featuredEventImage') }}" alt="{{ featuredEventImage.alt }}"> -->
                            <?php if($event_date < $currentDate) : ?>
                                <p class="notice">This event has passed</p>
                            <?php endif; ?>
                        </div>
                    <div class="events__featured-event__body">
                        <div class="events__featured-event__body-header">
                            <time datetime="<?php echo $event_date; ?>"><?php echo $event_date; ?></time>
                        </div>
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $description; ?></p>
                        <a href="<?php echo $link; ?>" class="button button--ghost button--ghost--border">Check it out</a>
                    </div>
                </div>
                <div class="featured-event__countdown">
                    <?php get_template_part('template-parts/component/countdown.php'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($events->have_posts()) : ?>
            <div class="events-listing">
                <?php while($events->have_posts()) : $events->the_post(); ?>
                    <article class="event-card">
                        <?php if(has_post_thumbnail()) : ?>
                            <div class="event-card__image">
                                <?php the_post_thumbnail(); ?>
                                <?php if(get_field('start_date') < date('c')) : ?>
                                    <p class="notice">This event has passed</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="event-card__body">
                            <?php 
                                $event_title = get_the_title();
                                if(get_field('teaser_title')) {
                                    $event_title = get_field('teaser_title');
                                }
                            ?>
                            <h3><?php echo $event_title; ?></h3>
                            <?php if(get_field('teaser_description')) : ?>
                                <p><?php echo get_field('teaser_description'); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </div>
</div>
<?php get_footer(); ?>