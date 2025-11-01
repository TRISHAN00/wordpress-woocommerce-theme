<?php

/**
 * The header for RazuTheme
 *
 * Displays all of the <head> section and everything up until <div id="content">
 *
 * @package RazuTheme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <title><?php wp_title('|', true, 'right'); ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <!-- Scroll to top button -->
  <button id="back-to-top"><i class="far fa-arrow-up"></i></button>


  <!-- Toast -->
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1100">
    <div id="loginToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">Logged in successfully!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Floating WhatsApp & Telegram Buttons -->
  <div class="floating-socials">
    <a href="https://wa.me/+971569863209" target="_blank" class="icon whatsapp" aria-label="Chat on WhatsApp">
      <i class="fab fa-whatsapp"></i>
    </a>
    <a href="https://t.me/yourusername" target="_blank" class="icon telegram" aria-label="Join Telegram">
      <i class="fab fa-telegram-plane"></i>
    </a>
  </div>


  <!-- Login/Register Modal -->
  <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content auth-modal">
        <div class="modal-header">
          <h5 class="modal-title" id="authModalLabel">Welcome</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="fal fa-window-close"></i>
          </button>
        </div>
        <div class="modal-body">
          <!-- Tabs -->
          <ul class="nav nav-tabs justify-content-center mb-3" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Register</button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="authTabsContent">
            <!-- Login Form -->
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
              <form id="ajax-login-form">
                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                <div class="mb-3">
                  <label for="loginEmail" class="form-label">Username or email address *</label>
                  <input type="text" class="form-control" id="loginEmail" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                  <label for="loginPassword" class="form-label">Password *</label>
                  <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <a href="<?php echo wp_lostpassword_url(); ?>" class="small">Forgot Password?</a>
                </div>
                <div id="login-message" class="mb-2 text-danger"></div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
              </form>
            </div>

            <!-- Register Form -->
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
              <form id="ajax-register-form">
                <?php wp_nonce_field('ajax-register-nonce', 'security'); ?>
                <div class="mb-3">
                  <label for="registerName" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="registerName" name="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                  <label for="registerEmail" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                  <label for="registerPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create a password" required>
                </div>
                <div id="register-message" class="mb-2 text-danger"></div>
                <button type="submit" class="btn btn-success w-100">Register</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Overlay -->
  <div class="overlay"></div>

  <!-- Mini Cart Sidebar -->
  <aside class="sidebar" id="mini-cart-sidebar">
    <div class="sidebar-header">
      <h4>Your Cart</h4>
      <button class="close-icon"><i class="fal fa-times"></i></button>
    </div>
    <div class="sidebar-body">
      <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
      </div>
    </div>
    <div class="sidebar-footer">
      <div class="subtotal">
        <span>Subtotal:</span>
        <strong id="cart-subtotal-amount"><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
      </div>
      <a href="<?php echo wc_get_checkout_url(); ?>" class="checkout-btn">
        Proceed to Checkout
      </a>
    </div>
  </aside>



  <!-- Sidebar -->
  <nav class="mobile-menu-sidebar" id="mobile-menu-sidebar">
    <?php
    wp_nav_menu(array(
      'theme_location' => 'primary',
      'container' => false,
      'menu_class' => 'mobile-menu-list',
      'walker' => new Mobile_Menu_Walker()
    ));
    ?>
  </nav>
  <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>


  <!-- Top Header with Auto-Scrolling Text -->
  <section class="top-header">
    <div class="container">
      <!-- Auto-scrolling text wrapper -->
      <div class="scroll-text-wrapper">
        <div class="scroll-text">
          <p><?php echo wp_kses_post(get_theme_mod('top_header_text', '🚚 Free Delivery Above 350 Dhs | 💳 Card Payment Available (Dubai/Sharjah/Ajman)')); ?></p>
          <!-- Duplicate for seamless loop -->
          <p style="margin-left: 50px;"><?php echo wp_kses_post(get_theme_mod('top_header_text', '🚚 Free Delivery Above 350 Dhs | 💳 Card Payment Available (Dubai/Sharjah/Ajman)')); ?></p>
        </div>
      </div>

      <!-- Right section (social icons) -->
      <div class="right-section">
        <div class="social-icons">
          <?php
          $socials = ['facebook', 'instagram', 'twitter', 'whatsapp'];
          foreach ($socials as $social) :
            $url = get_theme_mod("top_header_{$social}_url", '#');
            if ($url) :
          ?>
              <a href="<?php echo esc_url($url); ?>" target="_blank" aria-label="<?php echo esc_attr(ucfirst($social)); ?>">
                <i class="fab fa-<?php echo esc_attr($social); ?>"></i>
              </a>
          <?php
            endif;
          endforeach;
          ?>
        </div>
      </div>
    </div>
  </section>
  <header class="mobile-menu-header d-flex gap-2">
    <button id="mobile-menu-toggle" class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
    <input type="search" id="search-input" class="search-field" placeholder="<?php esc_attr_e('Search products...', 'mytheme'); ?>">

  </header>
  <!-- Main Header -->
  <header class="site-header" id="tp-header-sticky">
    <div class="container">
      <div class="header-content">
        <!-- Logo -->
        <div class="header-logo">
          <?php if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
          } else { ?>
            <a href="<?php echo esc_url(home_url('/')); ?>">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt="<?php bloginfo('name'); ?>">
            </a>
          <?php } ?>
        </div>

        <!-- Search -->
        <div class="header-search desktop-search">
          <input type="search" id="search-input" class="search-field hide-from-tablet-device" placeholder="<?php esc_attr_e('Search products...', 'mytheme'); ?>">
          <div id="search-results" class="ajax-search-results"></div>
        </div>

        <!-- Header Icons -->
        <div class="header-icons">
          <div class="auth-buttons">
            <?php if (is_user_logged_in()):
              $current_user = wp_get_current_user(); ?>
              <a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="icon-link account-btn">
                <i class="fas fa-user-circle"></i>
                <span><?php _e('My Account', 'mytheme'); ?></span>
              </a>
              <a href="<?php echo wp_logout_url(home_url()); ?>" class="icon-link logout-btn">
                <i class="far fa-sign-out"></i>
                <span><?php _e('Logout', 'mytheme'); ?></span>
              </a>
            <?php else: ?>
              <a href="#" class="icon-link login-btn" data-bs-toggle="modal" data-bs-target="#authModal">
                <i class="far fa-user"></i>
                <span><?php _e('Login / Register', 'mytheme'); ?></span>
              </a>
            <?php endif; ?>
            <!-- Cart Button -->
            <a href="javascript:void(0)" class="icon-link cart-btn" id="cart-toggle">
              <i class="fas fa-shopping-cart"></i>
              <span id="header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </a>
          </div>
        </div>



      </div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav" id="mainNav" role="navigation">
      <div class="container">
        <?php wp_nav_menu(array(
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'nav-list',
          'depth' => 3,
          'fallback_cb' => false
        )); ?>
      </div>
    </nav>
  </header>



  <script>
    const mobileSidebar = document.getElementById("mobile-menu-sidebar");
    const mobileOverlay = document.getElementById("mobile-menu-overlay");
    const mobileToggleBtn = document.getElementById("mobile-menu-toggle");

    mobileToggleBtn.addEventListener("click", () => {
      mobileSidebar.classList.toggle("active");
      mobileOverlay.classList.toggle("active");
    });

    mobileOverlay.addEventListener("click", () => {
      mobileSidebar.classList.remove("active");
      mobileOverlay.classList.remove("active");
    });

    document.querySelectorAll(".mobile-menu-submenu-toggle").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const submenu = btn.nextElementSibling;
        const parent = btn.parentElement;

        // Smooth toggle
        if (submenu.classList.contains("open")) {
          submenu.style.maxHeight = null;
          submenu.classList.remove("open");
          btn.classList.remove("active");
        } else {
          submenu.classList.add("open");
          btn.classList.add("active");
          submenu.style.maxHeight = submenu.scrollHeight + "px";
        }
      });
    });
  </script>