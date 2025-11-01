<?php
/**
 * The Template for displaying all single products
 *
 * @package YourTheme
 */

get_header(); ?>

<main>
    <?php
    // WooCommerce content for single product
    woocommerce_content();
    ?>
</main>

<?php get_footer(); ?>
