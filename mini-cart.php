<?php

/**
 * Mini Cart Template
 * 
 * This template can be overridden by copying it to:
 * yourtheme/woocommerce/cart/mini-cart.php
 * 
 * Works with AJAX - updates without page reload
 * 
 * @package razuTheme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if (!WC()->cart->is_empty()) : ?>

    <!-- Cart Items List -->
    <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
        <?php
        do_action('woocommerce_before_mini_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        ?>
                <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

                    <!-- Remove Button -->
                    <?php
                    echo apply_filters(
                        'woocommerce_cart_item_remove_link',
                        sprintf(
                            '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                            esc_attr__('Remove this item', 'woocommerce'),
                            esc_attr($product_id),
                            esc_attr($cart_item_key),
                            esc_attr($_product->get_sku())
                        ),
                        $cart_item_key
                    );
                    ?>

                    <!-- Product Image -->
                    <?php if (empty($product_permalink)) : ?>
                        <div class="mini-cart-product-image">
                            <?php echo $thumbnail; ?>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($product_permalink); ?>" class="mini-cart-product-image">
                            <?php echo $thumbnail; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Product Details -->
                    <div class="mini-cart-product-details">
                        <!-- Product Name -->
                        <div class="mini-cart-product-name">
                            <?php if (empty($product_permalink)) : ?>
                                <?php echo wp_kses_post($product_name); ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url($product_permalink); ?>">
                                    <?php echo wp_kses_post($product_name); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Product Variation Attributes -->
                        <?php if ($cart_item['variation']) : ?>
                            <div class="mini-cart-variation">
                                <?php
                                $variation_data = array();
                                foreach ($cart_item['variation'] as $attr_key => $attr_value) {
                                    $taxonomy = str_replace('attribute_', '', $attr_key);

                                    if (taxonomy_exists($taxonomy)) {
                                        $term = get_term_by('slug', $attr_value, $taxonomy);
                                        $variation_data[] = $term ? $term->name : $attr_value;
                                    } else {
                                        $variation_data[] = $attr_value;
                                    }
                                }
                                echo '<small>' . implode(', ', $variation_data) . '</small>';
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Quantity and Price -->
                        <div class="mini-cart-quantity-price">
                            <!-- Quantity Input with +/- buttons -->
                            <div class="quantity-controls">
                                <button type="button" class="quantity-minus">-</button>
                                <?php
                                if ($_product->is_sold_individually()) {
                                    $min_quantity = 1;
                                    $max_quantity = 1;
                                } else {
                                    $min_quantity = 0;
                                    $max_quantity = $_product->get_max_purchase_quantity();
                                }

                                $product_quantity = woocommerce_quantity_input(
                                    array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $max_quantity,
                                        'min_value'    => $min_quantity,
                                        'product_name' => $product_name,
                                    ),
                                    $_product,
                                    false
                                );

                                echo apply_filters('woocommerce_widget_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                ?>
                                <button type="button" class="quantity-plus">+</button>
                            </div>

                            <!-- Price -->
                            <span class="mini-cart-item-price">
                                <?php echo apply_filters('woocommerce_widget_cart_item_quantity', $product_price, $cart_item, $cart_item_key); ?>
                            </span>
                        </div>

                        <!-- Item Total -->
                        <div class="mini-cart-item-total">
                            <span class="label"><?php esc_html_e('Total:', 'woocommerce'); ?></span>
                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                        </div>
                    </div>

                </li>
        <?php
            }
        }

        do_action('woocommerce_mini_cart_contents');
        ?>
    </ul>

<?php else : ?>

    <!-- Empty Cart Message -->
    <div class="empty-cart-message">
        <i class="fas fa-shopping-cart"></i>
        <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button wc-forward">
            <?php esc_html_e('Return to shop', 'woocommerce'); ?>
        </a>
    </div>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>