<?php get_header(); ?>
    <nav class="breadcrumbs" aria-role="Breadcrumb">
        <div class="breadcrumbs-inner">
            <div class="l-cluster">
                <ul class="clean-list" role="list">
                    <li><a href="/">Home</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li aria-current="page"><a href="<?php echo get_the_permalink(); ?>"><? echo the_title(); ?></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <article class="blog-post">
        <?php if(get_the_post_thumbnail()) : ?>
            <div class="post__featured-image">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php endif; ?>
    <div class="blog-post__header">
        <h2><?php the_title(); ?></h2>
        <div class="blog-post__date">
            <?php echo get_the_date(); ?>
        </div>
    </div>
    <?php the_content(); ?>
    <div class="blog-post__footer">
        <a class="button button--ghost button--ghost--border" href="/blog">Back to all blog posts</a>
    </div>
    </article>
<?php get_footer(); ?>