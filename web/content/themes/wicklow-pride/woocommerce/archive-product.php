<?php 
    get_header();
    $products_loop = new WP_Query(array(
        'post_type' => 'product',
        'post_status' => 'publish'
    ));
    $page_id = get_page_by_path( 'shop' )->ID;
    
    get_template_part('template-parts/component/hero', null, array(
        'hero' => get_field('hero', $page_id)
    ));
?>
    <main class="main">
    <?php get_template_part('template-parts/page-components'); ?>
        <?php if($products_loop->have_posts()) : ?>
            <div class="product-listing">
                <?php while($products_loop->have_posts()) : $products_loop->the_post(); ?>
                    <?php
                        $product = wc_get_product(get_the_id());

                        $sizes_strings = get_post_meta($product->id, '_product_attributes')[0]['size']['value'];
                        $sizes = explode("| ", $sizes_strings);
                        $size_labels = array();
                        foreach($sizes as $size) {
                            if(!str_contains($size, 'X')) {
                                array_push($size_labels, $size[0]);
                            } else {
                                array_push($size_labels, $size);
                            }
                        }
                    ?>
                    <div class="product-card">
                        <div class="product-card__image">
                            <?php the_post_thumbnail(); ?>
                            <p class="product-card__price"><?php echo $product->get_price_html() ?></p>
                            <div class="product-sizes">
                                <?php foreach($size_labels as $size) : ?>
                                    <a class="size-link" href="/"><?php echo $size; ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="product-card__body">
                            <h2><?php echo $product->name; ?></h2>
                            <p><?php echo $product->description ?></p>
                            <button data-product-id="<?php echo $product->get_id(); ?>" data-variation="419" class="button button--ghost button--ghost--border ajax-add-to-cart"><span>Add to cart</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>
<?php get_footer(); ?>