<?php get_header(); 
    $checkout = new WC_Checkout();
?>
<main class="main">
    <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
        <?php if( $checkout->get_checkout_fields() ) : ?>
            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="col2-set" id="customer_details">
                <div class="col-1">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                </div>

                <div class="col-2">
                    <?php do_action( 'woocommerce_checkout_shipping' ) ?>
                </div>
            </div>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>
        <?php endif; ?>
        <div class="order-review">
            <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
            <h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce') ?></h3>

            <?php do_action('woocommerce_checkout_before_order_review'); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>
    </form>
</main>
<?php get_footer(); ?>