<?php 
    $heading = get_sub_field('heading');
    $members = get_sub_field('committee_members');
?>

<div class="component committee-listing">
    <div class="committee-listing-inner">
        <?php if($heading) : ?>
            <h2><?php echo $heading; ?></h2>
        <?php endif; ?>
        <?php if($members) : ?>
            <div class="committee-listing-wrap">
                <?php foreach($members as $post) : ?>
                    <?php 
                        setup_postdata( $post );

                        $name = get_field('full_name', $post->ID);
                        $pronouns = get_field('pronouns', $post->ID);
                        $role = get_field('role', $post->ID);
                        $image = get_the_post_thumbnail( $post->ID );
                    ?>
                    <div class="card committee-card">
                        <?php if($image) : ?>
                            <div class="member-profile-picture l-frame l-frame--3-2">
                                <?php echo $image; ?>
                            </div>
                        <?php else: ?>
                            <div class="member-profile-picture image--no-image l-frame l-frame--3-2">
                                <?php echo $image; ?>
                            </div>
                        <?php endif; ?>
                        <div class="committee-card__body">
                            <h2><?php echo $name ? $name : get_the_title($post->ID) ?></h2>
                            <?php if($pronouns) : ?>
                                <p class="pronouns"><?php echo $pronouns; ?></p>
                            <?php endif; ?>
                            <?php if($role) : ?>
                                <p class="role"><?php echo $role; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>