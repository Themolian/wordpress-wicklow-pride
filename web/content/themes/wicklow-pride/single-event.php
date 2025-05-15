<?php 
    get_header();
?>
<nav class="breadcrumbs" aria-role="Breadcrumb">
    <div class="breadcrumbs-inner">
        <div class="l-cluster">
            <ul class="clean-list" role="list">
                <li><a href="/">Home</a></li>
                <li><a href="/events">Events</a></li>
                <li aria-current="page"><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a></li>
            </ul>
        </div>
    </div>
</nav>
<article class="event event-body">
    <div class="component">
        <div class="event-details">
            <?php get_the_post_thumbnail() ? the_post_thumbnail() : null ?>
            <?php if(get_field('venue')) : ?>
                <p class="location"><?php echo get_field('venue'); ?></p>
            <?php endif; ?>
            <?php if(get_field('start_date')) : ?>
                <p class="time"><?php echo get_field('start_date'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php echo get_field('event_description') ? get_field('event_description') : null ?>
    <?php
    if(get_field('page_components')) {
        get_template_part('template-parts/page-components.php');
    }
    ?>
    <a href="/events" class="button button--ghost button--ghost--border">Back to all events</a>
</article>

<?php get_footer(); ?>