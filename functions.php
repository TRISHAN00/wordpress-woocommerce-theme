<?php

/**
 * razuTheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package razuTheme
 */

if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

// =====================================================================================
// INCLUDES & DEPENDENCIES
// =====================================================================================

include_once("inc/customizer/kirki-installer.php");
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/cmb2/init.php';
require get_template_directory() . '/inc/banner-fields.php';

if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}

// =====================================================================================
// THEME SETUP & SUPPORT
// =====================================================================================

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function razu_theme_setup()
{
    load_theme_textdomain('razu-theme', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'razu-theme'),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    add_theme_support(
        'custom-background',
        apply_filters(
            'razu_theme_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    add_theme_support('customize-selective-refresh-widgets');

    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );
}
add_action('after_setup_theme', 'razu_theme_setup');

class Mobile_Menu_Walker extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='mobile-menu-submenu'>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $output .= $indent . '<li class="mobile-menu-item' . ($has_children ? ' mobile-menu-has-submenu' : '') . '">';

        if ($has_children) {
            $output .= '<button class="mobile-menu-submenu-toggle">' . esc_html($item->title) . '</button>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}



/**
 * Set the content width in pixels
 */
function razu_theme_content_width()
{
    $GLOBALS['content_width'] = apply_filters('razu_theme_content_width', 640);
}
add_action('after_setup_theme', 'razu_theme_content_width', 0);

// =====================================================================================
// WOOCOMMERCE SETUP
// =====================================================================================

function mytheme_woocommerce_support()
{
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
    ));
}
add_action('after_setup_theme', 'mytheme_woocommerce_support');

// Remove default WooCommerce elements
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

// Remove Downloads tab from My Account
add_filter('woocommerce_account_menu_items', function ($items) {
    unset($items['downloads']);
    return $items;
}, 999);

// =====================================================================================
// MENUS REGISTRATION
// =====================================================================================

function mytheme_register_menus()
{
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mytheme'),
    ));
}
add_action('after_setup_theme', 'mytheme_register_menus');

function razutheme_register_footer_menus()
{
    register_nav_menus(array(
        'footer_top_categories'  => __('Footer Top Categories Menu', 'razutheme'),
        'footer_more_categories' => __('Footer More Categories Menu', 'razutheme'),
    ));
}
add_action('after_setup_theme', 'razutheme_register_footer_menus');

// =====================================================================================
// WIDGET AREAS
// =====================================================================================

function razu_theme_widgets_init()
{
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'razu-theme'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'razu-theme'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'razu_theme_widgets_init');

function razuTheme_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'razuTheme'),
        'id'            => 'shop-sidebar',
        'description'   => __('Widgets for WooCommerce shop page.', 'razuTheme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'razuTheme_widgets_init');

// =====================================================================================
// ENQUEUE SCRIPTS & STYLES
// =====================================================================================


function razu_theme_scripts()
{
    // CSS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '5.3.0', 'all');
    wp_enqueue_style('fontawesome-pro', get_template_directory_uri() . '/assets/css/fontawesome-pro.min.css', array(), null, 'all');
    wp_enqueue_style('nice-select', get_template_directory_uri() . '/assets/css/nice-select.css', array(), null, 'all');
    wp_enqueue_style('flaticon-exdos', get_template_directory_uri() . '/assets/css/flaticon-exdos.css', array(), null, 'all');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), null, 'all');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), null, 'all');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css', array(), null, 'all');

    // JS
    wp_enqueue_script('jquery-custom', get_template_directory_uri() . '/assets/js/jquery-3.7.1.min.js', array(), '3.7.1', true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery-custom'), '5.3.0', true);
    wp_enqueue_script('isotope', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array('jquery-custom'), null, true);
    wp_enqueue_script('nice-select', get_template_directory_uri() . '/assets/js/jquery.nice-select.js', array('jquery-custom'), null, true);
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery-custom'), null, true);
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('wow', get_template_directory_uri() . '/assets/js/wow.js', array(), null, true);
    wp_enqueue_script('header-js', get_template_directory_uri() . '/assets/js/header.js', array('jquery-custom'), null, true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery-custom'), null, true);
    wp_enqueue_script('products-js', get_template_directory_uri() . '/assets/js/products.js', array('jquery-custom'), null, true);
}
add_action('wp_enqueue_scripts', 'razu_theme_scripts');

function enqueue_ajax_search_script()
{
    wp_enqueue_script('ajax-product-search', get_template_directory_uri() . '/js/ajax-search.js', array('jquery'), '1.0', true);
    wp_localize_script('ajax-product-search', 'ajax_search_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-search-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_search_script');

function mytheme_enqueue_auth_scripts()
{
    wp_enqueue_script('auth-ajax', get_template_directory_uri() . '/js/auth.js', array('jquery'), '1.0.0', true);

    $myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '';

    wp_localize_script('auth-ajax', 'ajax_auth_params', array(
        'ajax_url'       => admin_url('admin-ajax.php'),
        'login_nonce'    => wp_create_nonce('ajax-login-nonce'),
        'register_nonce' => wp_create_nonce('ajax-register-nonce'),
        'logout_url'     => wp_logout_url(home_url()),
        'myaccount_url'  => $myaccount_url,
    ));
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_auth_scripts');

function enqueue_homepage_scripts()
{
    wp_enqueue_script('load-more', get_template_directory_uri() . '/js/load-more.js', ['jquery'], null, true);
    wp_localize_script('load-more', 'ajax_loadmore', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_homepage_scripts');

function custom_enqueue_ajax_add_to_cart()
{
    wp_enqueue_script('custom-ajax-add-to-cart', get_stylesheet_directory_uri() . '/js/ajax-add-to-cart.js', array('jquery'), '1.0.3', true);
    wp_localize_script('custom-ajax-add-to-cart', 'ajax_add_to_cart_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'wc_ajax_url' => WC_AJAX::get_endpoint("%%endpoint%%"),
        'nonce'    => wp_create_nonce('ajax-add-to-cart-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'custom_enqueue_ajax_add_to_cart');

// =====================================================================================
// CUSTOMIZER SETTINGS
// =====================================================================================

/**
 * Top Header Customizer Settings
 */
function mytheme_customize_register($wp_customize)
{
    $wp_customize->add_section('top_header_section', array(
        'title'       => __('Top Header', 'mytheme'),
        'priority'    => 30,
        'description' => __('Manage the top header bar content and social links', 'mytheme'),
    ));

    $wp_customize->add_setting('top_header_text', array(
        'default'           => '🚚 Free Delivery Above 350 Dhs | 💳 Card Payment Available (Dubai/Sharjah/Ajman)',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('top_header_text', array(
        'label'   => __('Header Text', 'mytheme'),
        'section' => 'top_header_section',
        'type'    => 'textarea',
    ));

    $socials = array('facebook', 'instagram', 'twitter', 'whatsapp');
    foreach ($socials as $social) {
        $wp_customize->add_setting("top_header_{$social}_url", array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("top_header_{$social}_url", array(
            'label'   => ucfirst($social) . ' URL',
            'section' => 'top_header_section',
            'type'    => 'url',
        ));
    }
}
add_action('customize_register', 'mytheme_customize_register');

/**
 * Footer Customizer Settings
 */
function vapedreamhub_footer_customizer($wp_customize)
{
    $wp_customize->add_section('footer_settings', array(
        'title'    => __('Footer Settings', 'vapedreamhub'),
        'priority' => 160,
    ));

    $wp_customize->add_setting('footer_company_info', array(
        'default' => 'Your trusted partner for premium vaping experiences...',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('footer_company_info', array(
        'label' => __('Company Description', 'vapedreamhub'),
        'type' => 'textarea',
        'section' => 'footer_settings',
    ));

    $wp_customize->add_setting('footer_address', array(
        'default' => 'Dubai, Sharjah, Ajman - UAE',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('footer_address', array(
        'label' => __('Address', 'vapedreamhub'),
        'type' => 'text',
        'section' => 'footer_settings',
    ));

    $wp_customize->add_setting('footer_email', array(
        'default' => 'info@vapedreamhub.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('footer_email', array(
        'label' => __('Email', 'vapedreamhub'),
        'type' => 'text',
        'section' => 'footer_settings',
    ));

    $wp_customize->add_setting('footer_hours', array(
        'default' => '24/7 Customer Support',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('footer_hours', array(
        'label' => __('Support Hours', 'vapedreamhub'),
        'type' => 'text',
        'section' => 'footer_settings',
    ));

    $socials = ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'telegram'];
    foreach ($socials as $social) {
        $wp_customize->add_setting("footer_{$social}_link", array(
            'default' => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("footer_{$social}_link", array(
            'label' => ucfirst($social) . ' URL',
            'type' => 'url',
            'section' => 'footer_settings',
        ));
    }

    $wp_customize->add_setting('footer_copyright', array(
        'default' => '&copy; ' . date('Y') . ' VapeDream Hub. All Rights Reserved. | Designed with ❤️ for Vapers',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('footer_copyright', array(
        'label' => __('Copyright Text', 'vapedreamhub'),
        'type' => 'textarea',
        'section' => 'footer_settings',
    ));
}
add_action('customize_register', 'vapedreamhub_footer_customizer');

// =====================================================================================
// WOOCOMMERCE MODIFICATIONS
// =====================================================================================

/**
 * Modify WooCommerce product query
 */
function modify_product_query($query)
{
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category()) {
            $query->set('posts_per_page', 9);
            $query->set('post_status', 'publish');

            $meta_query = $query->get('meta_query', array());
            $meta_query[] = array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            );
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'modify_product_query');

/**
 * Custom cart fragments for AJAX updates
 */
function custom_cart_fragments($fragments)
{
    ob_start();
?>
    <span id="header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['#header-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'custom_cart_fragments');

// =====================================================================================
// AJAX HANDLERS - PRODUCT SEARCH
// =====================================================================================

function ajax_product_search()
{
    check_ajax_referer('ajax-search-nonce', 'nonce');

    if (isset($_POST['query'])) {
        $query = sanitize_text_field($_POST['query']);

        $args = array(
            'post_type' => 'product',
            's' => $query,
            'posts_per_page' => -1
        );

        $search = new WP_Query($args);

        if ($search->have_posts()) {
            echo '<ul class="search-results-list">';
            while ($search->have_posts()) {
                $search->the_post();
                global $product;
    ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php echo woocommerce_get_product_thumbnail('thumbnail'); ?>
                        <div class="d-flex flex-column">
                            <span class="search-product-title"><?php the_title(); ?></span>
                            <span class="search-product-price"><?php echo $product->get_price_html(); ?></span>
                        </div>
                    </a>
                </li>
            <?php
            }
            echo '</ul>';
        } else {
            echo '<p class="no-results">' . __('No products found.', 'mytheme') . '</p>';
        }
        wp_reset_postdata();
    }

    wp_die();
}
add_action('wp_ajax_ajax_product_search', 'ajax_product_search');
add_action('wp_ajax_nopriv_ajax_product_search', 'ajax_product_search');

// =====================================================================================
// AJAX HANDLERS - AUTHENTICATION
// =====================================================================================

/**
 * AJAX Login Handler
 */
function ajax_login_handler()
{
    check_ajax_referer('ajax-login-nonce', 'security');

    $creds = array(
        'user_login'    => sanitize_text_field($_POST['email']),
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['remember']) && $_POST['remember'] === '1',
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => $user->get_error_message()));
    } else {
        wp_send_json_success(array('display_name' => $user->display_name));
    }
}
add_action('wp_ajax_nopriv_ajaxlogin', 'ajax_login_handler');

/**
 * AJAX Register Handler
 */
function ajax_register_handler()
{
    check_ajax_referer('ajax-register-nonce', 'security');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        wp_send_json_error(array('message' => 'All fields are required.'));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email already registered.'));
    }

    $username = sanitize_user(explode('@', $email)[0], true);
    if (username_exists($username)) $username .= wp_rand(1000, 9999);

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }

    wp_update_user(array(
        'ID'           => $user_id,
        'display_name' => $name,
    ));

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success(array('display_name' => $name));
}
add_action('wp_ajax_nopriv_ajax_register', 'ajax_register_handler');




// =====================================================================================
// AJAX HANDLERS - CART OPERATIONS
// =====================================================================================

/**
 * AJAX Add to Cart Handler
 */
function custom_add_to_cart()
{
    check_ajax_referer('ajax-add-to-cart-nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? $_POST['variation'] : array();

    if (!$product_id) {
        wp_send_json_error(array('message' => 'Invalid product ID'));
        return;
    }

    if ($variation_id) {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    } else {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
    }

    if ($cart_item_key) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_subtotal = WC()->cart->get_cart_subtotal();

        ob_start();
        woocommerce_mini_cart();
        $mini_cart_html = ob_get_clean();

        wp_send_json_success(array(
            'message' => 'Product added to cart',
            'cart_count' => $cart_count,
            'cart_subtotal' => $cart_subtotal,
            'mini_cart_html' => $mini_cart_html,
            'fragments' => array(
                '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart_html . '</div>',
            )
        ));
    } else {
        wp_send_json_error(array('message' => 'Unable to add product to cart'));
    }
}
add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart');

/**
 * AJAX Update Cart Item Quantity - FIXED VERSION
 * Place this in functions.php, replacing the existing ajax_update_cart_item function
 */
function ajax_update_cart_item()
{
    check_ajax_referer('ajax-add-to-cart-nonce', 'nonce');

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        WC()->cart->set_quantity($cart_item_key, $quantity, true);
    } else {
        WC()->cart->remove_cart_item($cart_item_key);
    }

    // ✅ Force cart calculation to ensure accurate totals
    WC()->cart->calculate_totals();

    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_subtotal = WC()->cart->get_cart_subtotal();

    ob_start();
    woocommerce_mini_cart();
    $mini_cart_html = ob_get_clean();

    wp_send_json_success(array(
        'cart_count' => $cart_count,
        'cart_subtotal' => $cart_subtotal,
        'mini_cart_html' => $mini_cart_html,
        'fragments' => array(
            '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart_html . '</div>',
            '#header-cart-count' => '<span id="header-cart-count">' . $cart_count . '</span>',
        )
    ));
}
add_action('wp_ajax_update_cart_item', 'ajax_update_cart_item');
add_action('wp_ajax_nopriv_update_cart_item', 'ajax_update_cart_item');

// 🟡 AJAX: Get category description dynamically
add_action('wp_ajax_get_category_description', 'get_category_description_ajax');
add_action('wp_ajax_nopriv_get_category_description', 'get_category_description_ajax');
function get_category_description_ajax()
{
    $slug = sanitize_text_field($_POST['category'] ?? '');
    if ($slug === 'all') {
        wp_send_json_success(['description' => '']);
    }
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term && !is_wp_error($term)) {
        $description = wpautop($term->description);
        wp_send_json_success(['description' => $description]);
    }
    wp_send_json_error();
}


/**
 * AJAX Remove Cart Item - FINAL FIX
 */
function ajax_remove_cart_item()
{
    // Enable error logging
    error_log('=== REMOVE CART ITEM CALLED ===');

    check_ajax_referer('ajax-add-to-cart-nonce', 'nonce');

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    error_log('Cart Item Key: ' . $cart_item_key);

    if (WC()->cart->remove_cart_item($cart_item_key)) {
        // Force recalculation
        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();

        // Get updated values
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_subtotal = WC()->cart->get_cart_subtotal();

        // Debug logging
        error_log('Cart Count After Remove: ' . $cart_count);
        error_log('Cart Subtotal After Remove: ' . $cart_subtotal);
        error_log('Subtotal Type: ' . gettype($cart_subtotal));

        ob_start();
        woocommerce_mini_cart();
        $mini_cart_html = ob_get_clean();

        $response_data = array(
            'message'       => 'Item removed from cart',
            'cart_count'    => $cart_count,
            'cart_subtotal' => $cart_subtotal,
            'mini_cart_html' => $mini_cart_html,
            'debug_info'    => array(
                'subtotal_length' => strlen($cart_subtotal),
                'count_type' => gettype($cart_count),
                'subtotal_type' => gettype($cart_subtotal)
            )
        );

        error_log('Response Data: ' . print_r($response_data, true));
        error_log('=== END REMOVE CART ITEM ===');

        wp_send_json_success($response_data);
    } else {
        error_log('Failed to remove cart item');
        wp_send_json_error(array('message' => 'Unable to remove item'));
    }
}
add_action('wp_ajax_remove_cart_item', 'ajax_remove_cart_item');
add_action('wp_ajax_nopriv_remove_cart_item', 'ajax_remove_cart_item');

// =====================================================================================
// AJAX HANDLERS - PRODUCT LOADING
// =====================================================================================

/**
 * AJAX Load More Products (Homepage)
 */
function ajax_load_more_products()
{
    $category = sanitize_text_field($_POST['category']);
    $paged    = intval($_POST['page']);

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ]
        ]
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $product = wc_get_product(get_the_ID());
            ?>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 col-6 product-item">
                <div class="product-card">
                    <a href="<?php the_permalink(); ?>" class="product-image">
                        <?php echo $product->get_image(); ?>
                        <div class="product-labels labels-rounded">
                            <?php
                            global $product;
                            if ($product->is_on_sale()) {
                                $regular_price = (float) $product->get_regular_price();
                                $sale_price    = (float) $product->get_sale_price();

                                // For variable products, use variation prices
                                if ($product->is_type('variable')) {
                                    $regular_price = (float) $product->get_variation_regular_price('max');
                                    $sale_price    = (float) $product->get_variation_sale_price('min');
                                }

                                if ($regular_price > 0 && $sale_price > 0) {
                                    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                                    echo '<span class="onsale product-label">-' . $discount . '%</span>';
                                }
                            }
                            ?>
                        </div>

                    </a>
                    <div class="product-content">
                        <h3 class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="product-price"><?php echo $product->get_price_html(); ?></div>

                        <?php if ($product->is_type('variable')) : ?>
                            <button class="select-options-btn"><i class="fas fa-shopping-cart"></i></button>
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
            </div>
        <?php
        endwhile;
    else :
        echo '<div class="col-12"><p>No more products.</p></div>';
    endif;

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_load_more_products', 'ajax_load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'ajax_load_more_products');

/**
 * AJAX Load Products (Shop Page) - FIXED VERSION
 * Replace the existing ajax_load_products function in functions.php
 */
function ajax_load_products()
{
    check_ajax_referer('load_products_nonce', 'nonce');

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 9;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    ];

    if ($category !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $product = wc_get_product(get_the_ID());
        ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-6 product-item">
                <div class="product-card">
                    <a href="<?php the_permalink(); ?>" class="product-image">
                        <?php echo $product->get_image(); ?>
                        <div class="product-labels labels-rounded">
                            <?php
                            global $product;
                            if ($product->is_on_sale()) {
                                $regular_price = (float) $product->get_regular_price();
                                $sale_price    = (float) $product->get_sale_price();

                                // For variable products, use variation prices
                                if ($product->is_type('variable')) {
                                    $regular_price = (float) $product->get_variation_regular_price('max');
                                    $sale_price    = (float) $product->get_variation_sale_price('min');
                                }

                                if ($regular_price > 0 && $sale_price > 0) {
                                    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                                    echo '<span class="onsale product-label">-' . $discount . '%</span>';
                                }
                            }
                            ?>
                        </div>

                    </a>

                    <div class="product-content text-center">
                        <h3 class="product-name">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <div class="product-price mb-2">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <?php if ($product->is_type('variable')) : ?>
                            <button class="select-options-btn"><i class="fas fa-shopping-cart"></i></button>
                            
                            <div class="product-overlay">
                                <div class="close-overlay"></div>
                                <div class="add-to-cart">
                                    <?php
                                    // Properly render WooCommerce variation form
                                    wc_get_template(
                                        'single-product/add-to-cart/variable.php',
                                        [
                                            'available_variations' => $product->get_available_variations(),
                                            'attributes'           => $product->get_variation_attributes(),
                                            'selected_attributes'  => $product->get_default_attributes(),
                                        ]
                                    );
                                    ?>
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
            </div>
    <?php
        endwhile;
    else :
        echo '<div class="col-12"><p>No products found.</p></div>';
    endif;

    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success([
        'html'       => $html,
        'max_pages'  => $query->max_num_pages,
        'total'      => $query->found_posts,
        'needs_variation_init' => true,
    ]);
}
add_action('wp_ajax_load_products', 'ajax_load_products');
add_action('wp_ajax_nopriv_load_products', 'ajax_load_products');


function enqueue_shop_variation_scripts()
{
    if (is_shop() || is_product_category() || is_product_taxonomy()) {
        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('jquery-ui-core');

        $inline_script = "
        jQuery(document).ready(function($) {
            if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                $('.variations_form').each(function() {
                    if (!$(this).hasClass('initialized')) {
                        $(this).addClass('initialized');
                        $(this).wc_variation_form();
                    }
                });
            }
            
            $(document).ajaxComplete(function(event, xhr, settings) {
                if (settings.data && settings.data.indexOf('action=load_products') > -1) {
                    setTimeout(function() {
                        $('.variations_form:not(.initialized)').each(function() {
                            $(this).addClass('initialized');
                            $(this).wc_variation_form();
                        });
                    }, 200);
                }
            });
        });
        ";

        wp_add_inline_script('wc-add-to-cart-variation', $inline_script);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_shop_variation_scripts', 99);

// =====================================================================================
// HELPER FUNCTIONS - PRODUCT SECTIONS
// =====================================================================================

/**
 * Render Product Section with Load More
 */
function render_product_section($title, $category_slug, $posts_per_page = 8)
{
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $posts_per_page,
        'paged'          => 1,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category_slug,
            ]
        ],
    ];

    $query = new WP_Query($args);
    ?>
    <section class="product-list">
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>

        <div class="container">
            <div class="subtitle">
                <h2><?php echo esc_html($title); ?></h2>
            </div>

            <div class="load-more-container" data-category="<?php echo esc_attr($category_slug); ?>">
                <div class="row" id="post-container">
                    <?php
                    if ($query->have_posts()) :
                        while ($query->have_posts()) : $query->the_post();
                            $product = wc_get_product(get_the_ID());
                    ?>
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 col-6 product-item">
                                <div class="product-card">
                                    <a href="<?php the_permalink(); ?>" class="product-image">
                                        <?php echo $product->get_image(); ?>

                                        <div class="product-labels labels-rounded">
                                            <?php
                                            global $product;
                                            if ($product->is_on_sale()) {
                                                $regular_price = (float) $product->get_regular_price();
                                                $sale_price    = (float) $product->get_sale_price();

                                                // For variable products, use variation prices
                                                if ($product->is_type('variable')) {
                                                    $regular_price = (float) $product->get_variation_regular_price('max');
                                                    $sale_price    = (float) $product->get_variation_sale_price('min');
                                                }

                                                if ($regular_price > 0 && $sale_price > 0) {
                                                    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                                                    echo '<span class="onsale product-label">-' . $discount . '%</span>';
                                                }
                                            }
                                            ?>
                                        </div>

                                    </a>

                                    <div class="product-content">
                                        <h3 class="product-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="product-price"><?php echo $product->get_price_html(); ?></div>

                                        <?php if ($product->is_type('variable')) : ?>
                                            <button class="select-options-btn"><i class="fas fa-shopping-cart"></i></button>
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
                            </div>
                    <?php
                        endwhile;
                    else :
                        echo '<div class="col-12"><p>No products found in this category.</p></div>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>

                <?php if ($query->max_num_pages > 1) : ?>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button class="load-more-btn"
                                data-category="<?php echo esc_attr($category_slug); ?>"
                                data-page="1"
                                data-max="<?php echo $query->max_num_pages; ?>">
                                <span class="btn-text">Load More</span>
                                <span class="btn-loader" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                </span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php
}

// =====================================================================================
// UTILITY FUNCTIONS
// =====================================================================================


/**
 * Enable WebP uploads
 */
function webp_upload_mimes($existing_mimes)
{
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');
