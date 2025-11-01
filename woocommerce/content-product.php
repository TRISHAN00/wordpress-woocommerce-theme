<?php
defined('ABSPATH') || exit;

global $product;

if (empty($product) || ! $product->is_visible()) {
  return;
}
?>

<div <?php wc_product_class($product); ?>
  <div class="product-card card h-100 shadow-sm">
    <a href="<?php the_permalink(); ?>" class="product-image">
      <?php echo woocommerce_get_product_thumbnail('medium'); ?>
    </a>

    <div class="card-body product-content text-center">
      <h3 class="product-title h6">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h3>

      <!-- ✅ Show Attributes -->
      <?php if ($product->is_type('variable')) : ?>
        <div class="product-attributes mb-2">
          <?php
          $attributes = $product->get_attributes();

          if (! empty($attributes)) {
            foreach ($attributes as $attribute) {
              // Get attribute name
              $attribute_name = wc_attribute_label($attribute->get_name());

              // Get terms
              $terms = $attribute->get_terms();
              if (! empty($terms)) {
                echo '<p class="attr"><strong>' . esc_html($attribute_name) . ':</strong> ';
                $values = array();
                foreach ($terms as $term) {
                  $values[] = esc_html($term->name);
                }
                echo implode(', ', $values);
                echo '</p>';
              }
            }
          }
          ?>
        </div>
      <?php endif; ?>

      <div class="product-price mb-2">
        <?php echo $product->get_price_html(); ?>
      </div>

      <div class="product-actions">
        <?php woocommerce_template_loop_add_to_cart(); ?>
      </div>
    </div>
  </div>
</div>