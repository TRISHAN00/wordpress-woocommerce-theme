<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Get product attributes
$attributes = $product->get_attributes();
?>

<div <?php wc_product_class( 'product-card swiper-slide', $product ); ?>>
                                <a href="<?php the_permalink(); ?>" class="product-image">
                                    <?php echo $product->get_image(); ?>
                                </a>

                                <div class="product-content">
                                    <h3 class="product-name">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="product-price"><?php echo $product->get_price_html(); ?></div>

                                    <?php if ($product->is_type('variable')) : ?>
                                        <button class="select-options-btn">Select Options</button>
                                        <div class="product-overlay">
                                            <div class="close-overlay"></div>
                                            <div class="add-to-cart">
                                                <?php woocommerce_template_single_add_to_cart(); ?>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <form class="cart" method="post" enctype="multipart/form-data">
                                            <button type="submit"
                                                name="add-to-cart"
                                                value="<?php echo esc_attr($product->get_id()); ?>"
                                                class="single_add_to_cart_button button alt ajax_add_to_cart">
                                                Add to Cart
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
</div>
