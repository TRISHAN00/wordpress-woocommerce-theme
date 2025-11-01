<!-- Product Detail Start -->
<div class="product-detail">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="col-gallery">
          <div class="product-gallery">
            <div class="swiper mySwiper2 gallery__main">
              <div class="swiper-wrapper">
                <?php
                global $product;
                $attachment_ids = $product->get_gallery_image_ids();

                // Featured image
                $featured = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'large');
                if ($featured) : ?>
                  <div class="swiper-slide gallery__main-slide">
                    <a href="<?php echo esc_url($featured[0]); ?>" class="popup-link">
                      <img src="<?php echo esc_url($featured[0]); ?>" alt="<?php the_title(); ?>" class="gallery__main-image" />
                    </a>
                  </div>
                <?php endif; ?>

                <?php
                // Gallery images
                if ($attachment_ids && $product->get_image_id()) {
                  foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                ?>
                    <div class="swiper-slide gallery__main-slide">
                      <a href="<?php echo esc_url($image_url); ?>" class="popup-link">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>" class="gallery__main-image" />
                      </a>
                    </div>
                <?php }
                }
                ?>
              </div>
              <div class="swiper-button-next" aria-label="Next image"></div>
              <div class="swiper-button-prev" aria-label="Previous image"></div>
            </div>

            <!-- Thumbnails -->
            <div thumbsSlider="" class="swiper mySwiper gallery__thumbs">
              <div class="swiper-wrapper">
                <?php
                // Featured thumbnail
                if ($featured) : ?>
                  <div class="swiper-slide gallery__thumb-slide">
                    <img src="<?php echo esc_url($featured[0]); ?>" alt="<?php the_title(); ?>" />
                  </div>
                <?php endif; ?>

                <?php
                // Gallery thumbnails
                if ($attachment_ids) {
                  foreach ($attachment_ids as $attachment_id) {
                    $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                ?>
                    <div class="swiper-slide gallery__thumb-slide">
                      <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title(); ?>" />
                    </div>
                <?php }
                }
                ?>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="col-info">
          <div class="product-info">
            <div class="product-info__breadcrumb">
              <?php
              if (function_exists('woocommerce_breadcrumb')) {
                woocommerce_breadcrumb(array(
                  'wrap_before' => '<ul class="product-info__breadcrumb">',
                  'wrap_after'  => '</ul>',
                  'before'      => '<li>',
                  'after'       => '</li>',
                  'delimiter'   => '',
                ));
              }
              ?>
            </div>


            <div class="product-info__title">
              <h1><?php the_title(); ?></h1>
            </div>

            <div class="product-info__price">
              <strong><?php wc_get_template('single-product/price.php'); ?></strong>
            </div>

            <div class="product-info__features">
              <?php
              global $product;
              if ($product) {
                echo wpautop($product->get_short_description());
              }
              ?>
            </div>

            <div class="add-to-cart">
              <?php woocommerce_template_single_add_to_cart(); ?>
            </div>



            <div id="cart-help" class="sr-only">
              Please select a color before adding to cart
            </div>
          </div>


          <div class="product-meta">
            <?php
            global $product;

            if ($product) :

              // SKU
              if ($product->get_sku()) : ?>
                <div class="product-meta__item">
                  <span class="product-meta__label">SKU:</span>
                  <span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
                </div>
              <?php endif; ?>

              <!-- Categories -->
              <?php
              $categories = wc_get_product_category_list($product->get_id(), ', ', '<div class="product-meta__item"><span class="product-meta__label">Categories:</span> ', '</div>');
              if ($categories) {
                echo $categories;
              }
              ?>

              <!-- Tags -->
              <?php
              $tags = wc_get_product_tag_list($product->get_id(), ', ', '<div class="product-meta__item"><span class="product-meta__label">Tags:</span> ', '</div>');
              if ($tags) {
                echo $tags;
              }
              ?>

            <?php endif; ?>
          </div>


          <div class="social-share">
            <span class="social-share__title">Share:</span>
            <a
              href="#"
              class="social-share__icon social-share__icon--facebook"
              aria-label="Share on Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a
              href="#"
              class="social-share__icon social-share__icon--twitter"
              aria-label="Share on Twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a
              href="#"
              class="social-share__icon social-share__icon--pinterest"
              aria-label="Share on Pinterest">
              <i class="fab fa-pinterest-p"></i>
            </a>
            <a
              href="#"
              class="social-share__icon social-share__icon--linkedin"
              aria-label="Share on LinkedIn">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Product Detail End -->

<!-- Product Detail Tab Start -->
<div class="product-detail-tab py-5">
  <div class="container">
    <?php
    global $product;

    if (! $product) return;

    // Tabs
    $tabs = apply_filters('woocommerce_product_tabs', array());
    ?>

    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <?php
        $active = 'active';
        foreach ($tabs as $key => $tab) : ?>
          <button class="nav-link <?php echo esc_attr($active); ?>"
            id="nav-<?php echo esc_attr($key); ?>-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-<?php echo esc_attr($key); ?>"
            type="button"
            role="tab"
            aria-controls="nav-<?php echo esc_attr($key); ?>"
            aria-selected="<?php echo ($active == 'active') ? 'true' : 'false'; ?>">
            <?php echo wp_kses_post($tab['title']); ?>
          </button>
        <?php
          $active = ''; // Only first tab is active
        endforeach; ?>
      </div>
    </nav>

    <div class="tab-content p-4 border border-top-0 rounded-bottom bg-white shadow-sm" id="nav-tabContent">
      <?php
      $active = 'show active';
      foreach ($tabs as $key => $tab) : ?>
        <div class="tab-pane fade <?php echo esc_attr($active); ?>"
          id="nav-<?php echo esc_attr($key); ?>"
          role="tabpanel"
          aria-labelledby="nav-<?php echo esc_attr($key); ?>-tab">
          <?php
          if (isset($tab['callback'])) {
            call_user_func($tab['callback'], $key, $tab);
          }
          ?>
        </div>
      <?php
        $active = ''; // Only first tab is active
      endforeach; ?>
    </div>
  </div>
</div>

<!-- Product Detail Tab End -->

