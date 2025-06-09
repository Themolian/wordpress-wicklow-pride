<?php get_header(); ?>
<main class="main">
    <?php if(get_field('parade_mode', 'options')) : ?>
        <?php get_template_part('template-parts/component/festival-events'); ?>
    <?php endif; ?>
    <?php get_template_part('template-parts/page-components'); ?>
</main>
<?php get_footer(); ?>