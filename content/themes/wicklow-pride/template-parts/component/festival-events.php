<?php
    $events = get_field('festival_events');
?>
<div class="component festival-events">
    <div class="festival-events-inner">
        
        <?php 
            foreach($events as $index=>$post) : setup_postdata( $post )
        ?>
            <div class="festival-events__details" data-event-id="<?php echo $post->ID; ?>" data-border-theme="<?php echo $index; ?>" data-current-event="<?php echo $index == 0 ? true : false ?>">
                <div class="festival-events__location-header">
                    <h3 class="location"><?php echo get_field('venue', $post->ID); ?></h3>
                </div>
                <div class="festival-events__details-wrap">
                    <div class="festival-events__location">
                        <div class="festival-events__location-map">
                        <div class="location-map">
                            <?php echo get_field('location', $post->ID); ?>
                        </div>
                        </div>
                    </div>
                    <div class="festival-events__event">
                        <div class="festival-event">
                            <div class="festival-event__header">
                                <p class="date"><?php echo get_field('start_date', $post->ID); ?></p>
                                <h3><?php the_title(); ?></h3>
                            </div>
                            <div class="festival-event__body">
                                <?php 
                                    if(get_field('event_description', $post->ID)) {
                                        the_field('event_description', $post->ID);
                                    }
                                ?>
                            </div>
                            <div class="festival-event__footer">
                                <a href="<?php the_permalink(); ?>" class="button button--ghost button--ghost--border">Check it out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="festival-events__switcher">
            <div class="l-cluster">
                <?php if($events) : ?>
                    <ul class="clean-list events">
                        <?php 
                            foreach($events as $index=>$post) : setup_postdata( $post )
                        ?>
                            <li class="festival-event__button theme-<?php echo $index ?>"><button class="button" data-event-id="<?php echo $post->ID ?>"><?php the_title(); ?></button></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php wp_reset_postdata(); ?>