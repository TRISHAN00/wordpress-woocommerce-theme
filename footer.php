<?php
/**
 * The template for displaying the footer
 *
 * @package RazuTheme
 */
?>

<footer class="site-footer">
  <div class="footer-decoration"></div>
  <div class="footer-decoration"></div>

  <!-- Main Footer -->
  <div class="footer-main">
    <div class="container">
      <div class="footer-grid">

        <!-- Company Info -->
        <?php
        $company_name = get_theme_mod('footer_company_name', 'VapeDream Hub');
        $company_info = get_theme_mod('footer_company_info', 'Your trusted partner for premium vaping experiences.');
        $address      = get_theme_mod('footer_address');
        $phone        = get_theme_mod('footer_phone');
        $email        = get_theme_mod('footer_email');
        $hours        = get_theme_mod('footer_hours');
        ?>

        <div class="footer-column company-info">
          <?php if ($company_name) : ?>
            <h4><?php echo esc_html($company_name); ?></h4>
          <?php endif; ?>

          <?php if ($company_info) : ?>
            <p><?php echo esc_html($company_info); ?></p>
          <?php endif; ?>

          <?php if ($address || $phone || $email || $hours) : ?>
            <div class="contact-info">
              <?php if ($address) : ?>
                <div class="contact-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <span><?php echo esc_html($address); ?></span>
                </div>
              <?php endif; ?>

              <?php if ($phone) : ?>
                <div class="contact-item">
                  <i class="fas fa-phone"></i>
                  <span><?php echo esc_html($phone); ?></span>
                </div>
              <?php endif; ?>

              <?php if ($email) : ?>
                <div class="contact-item">
                  <i class="fas fa-envelope"></i>
                  <span><?php echo esc_html($email); ?></span>
                </div>
              <?php endif; ?>

              <?php if ($hours) : ?>
                <div class="contact-item">
                  <i class="fas fa-clock"></i>
                  <span><?php echo esc_html($hours); ?></span>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Top Categories -->
        <div class="footer-column">
          <h4><?php echo esc_html(get_theme_mod('footer_top_categories_title', 'Top Categories')); ?></h4>
          <?php
          if (has_nav_menu('footer_top_categories')) {
            wp_nav_menu(array(
              'theme_location' => 'footer_top_categories',
              'container'      => false,
              'menu_class'     => 'footer-links',
              'fallback_cb'    => false,
              'depth'          => 1,
            ));
          } else {
            echo '<ul class="footer-links"><li><a href="#">Assign a Top Categories menu in Appearance → Menus</a></li></ul>';
          }
          ?>
        </div>

        <!-- More Categories (Two Columns) -->
        <div class="footer-column">
          <h4><?php echo esc_html(get_theme_mod('footer_more_categories_title', 'More Categories')); ?></h4>
          <div class="more-categories-grid">
            <?php
            if (has_nav_menu('footer_more_categories')) {
              $menu_locations = get_nav_menu_locations();
              $menu_id = $menu_locations['footer_more_categories'] ?? null;

              if ($menu_id) {
                $menu_items = wp_get_nav_menu_items($menu_id);

                if (!empty($menu_items)) {
                  $half       = ceil(count($menu_items) / 2);
                  $first_col  = array_slice($menu_items, 0, $half);
                  $second_col = array_slice($menu_items, $half);

                  echo '<ul class="footer-links">';
                  foreach ($first_col as $item) {
                    echo '<li class="category-link"><a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
                  }
                  echo '</ul><ul class="footer-links">';
                  foreach ($second_col as $item) {
                    echo '<li class="category-link"><a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
                  }
                  echo '</ul>';
                }
              }
            } else {
              echo '<ul class="footer-links"><li><a href="#">Assign a More Categories menu in Appearance → Menus</a></li></ul>';
            }
            ?>
          </div>
        </div>

        <!-- Social Links -->
        <?php
        $social_links = [
          'facebook' => get_theme_mod('footer_facebook_link'),
          'instagram' => get_theme_mod('footer_instagram_link'),
          'twitter' => get_theme_mod('footer_twitter_link'),
          'youtube' => get_theme_mod('footer_youtube_link'),
          'tiktok' => get_theme_mod('footer_tiktok_link'),
          'telegram' => get_theme_mod('footer_telegram_link'),
        ];
        $has_social = array_filter($social_links);
        ?>

        <?php if (!empty($has_social)) : ?>
          <div class="footer-column">
            <h4>Follow Us</h4>
            <div class="social-links">
              <?php foreach ($social_links as $key => $link) :
                if ($link) :
                  $icon_class = 'fab fa-' . esc_attr($key);
              ?>
                  <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener">
                    <i class="<?php echo esc_attr($icon_class); ?>"></i>
                  </a>
              <?php endif;
              endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- Legal Section -->
  <div class="legal-section">
    <div class="container">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/payment-gateway.png'); ?>" alt="Payment Gateways" />
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container">
      <div class="footer-bottom-content">
        <div class="copyright d-flex justify-content-between w-100">
          <p>
            &copy; <?php echo date('Y'); ?> 
            <strong>VapeDream Hub</strong>. All Rights Reserved. 
            | Designed with ❤️ for Vapers 
            
          </p>
          <span class="dev-credit">
              Developed by 
              <a href="https://www.linkedin.com/in/devtrishansaha/" target="_blank" rel="noopener noreferrer">
                <strong>Trishan</strong>
              </a>
            </span>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
