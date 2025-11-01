<?php

while ( $loop->have_posts() ) : $loop->the_post();
    global $product; ?>
    
    <div class="col-md-3 product-item">
        <a href="<?php the_permalink(); ?>">
            <?php echo woocommerce_get_product_thumbnail(); ?>
            <h3><?php the_title(); ?></h3>
        </a>
        <span class="price"><?php echo $product->get_price_html(); ?></span>
        <?php woocommerce_template_loop_add_to_cart(); ?>
    </div>
<?php endwhile; ?>
