<?php 
    $heading = get_sub_field('heading');
    $body = get_sub_field('body');
    $card_link = get_sub_field('card_link');
    $highlight_colour = get_sub_field('highlight_colour');
?>
<div class="component cta-cards">
    <div class="cta-cards-inner">
        <div class="cta-cards-listing">
            <?php if(have_rows('cta_cards')) : ?>
                <?php while(have_rows('cta_cards')) : the_row(); ?>
                    <div class="cta-card" style="<?php echo $highlight_colour ? 'border-color: ' . $highlight_colour . ';' : '' ?>">
                        <?php if($heading) : ?>
                            <h3><?php echo $heading; ?></h3>
                        <?php endif; ?>
                        <?php if($body) {
                            echo $body;
                        } ?>
                        <?php if($card_link) : ?>
                            <a href="<?php echo $card_link['url'] ?>" class="button button--ghost button--ghost--border cta-card__link" style="<?php echo $highlight_colour ? 'border-color: ' . $highlight_colour . ';' : '' ?>"><?php echo $card_link['title']; ?></a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="cta-card" style="<?php echo $highlight_colour ? 'border-color: ' . $highlight_colour . ';' : '' ?>">
                    <?php if($heading) : ?>
                        <h3><?php echo $heading; ?></h3>
                    <?php endif; ?>
                    <?php if($body) {
                        echo $body;
                    } ?>
                    <?php if($card_link) : ?>
                        <a href="<?php echo $card_link['url'] ?>" class="button button--ghost button--ghost--border cta-card__link" style="<?php echo $highlight_colour ? 'border-color: ' . $highlight_colour . ';' : '' ?>"><?php echo $card_link['title']; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>