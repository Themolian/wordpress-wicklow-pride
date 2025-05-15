<?php

get_header();
   
$page_id = get_page_by_path( 'blog' )->ID; 
get_template_part('template-parts/component/hero', null, array(
	'hero' => get_field('hero', $page_id)
));

?>

	<main class="main">
			<nav class="breadcrumbs" aria-role="Breadcrumb">
				<div class="breadcrumbs-inner">
					<div class="l-cluster">
						<ul class="clean-list" role="list">
							<li><a href="/">Home</a></li>
							<li aria-current="page"><a href="<?php get_the_permalink($page_id); ?>">Blog</a></li>
						</ul>
					</div>
				</div>
			</nav>
			<section class="blog-posts">
				<div class="blog-posts-inner">
					<?php if(get_field('page_intro', $page_id)) : ?>
						<div class="blog-posts__intro">
							<?php echo get_field('page_intro', $page_id); ?>
						</div>
					<?php endif; ?>
					<?php if(have_posts()) : ?>
						<div class="blog-posts__listing">
							<?php while(have_posts()) : the_post(); ?>
								<article class="blog-post__card">
									<div class="blog-post__card__image">
										<?php echo the_post_thumbnail(); ?>
									</div>
									<div class="blog-post__card__body">
										<h3><?php the_title(); ?></h3>
										<?php echo get_field('teaser_description') ? get_field('teaser_description') : null; ?>
										<a href="<?php echo get_the_permalink(); ?>" class="button button--ghost button--ghost--border">Check it out</a>
									</div>
								</article>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
	</main>

<?php
get_footer();
