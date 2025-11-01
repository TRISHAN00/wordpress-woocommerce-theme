<?php
/**
 * WooCommerce Template Wrapper with AJAX Load More, Nice Select & Category Description
 */

get_header();

// Determine current category
$current_category_slug = 'all';
$current_category_name = 'Shop';
if (is_product_category()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
        $current_category_slug = $term->slug;
        $current_category_name = $term->name;
    }
}

// Get product categories excluding "uncategorized"
$uncat = get_term_by('slug', 'uncategorized', 'product_cat');
$uncat_id = $uncat ? $uncat->term_id : 0;

$product_categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'exclude' => [$uncat_id],
]);
?>

<main>
    <!-- Banner -->
    <section class="inner-banner">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-01.jpg" alt="cart banner" />
        <div class="inner-banner__overlay">
            <h1 id="category-title" class="page-title"><?php echo esc_html($current_category_name); ?></h1>
        </div>
    </section>

    <!-- Breadcrumb & Ordering -->
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-content">
                <div class="breadcrumb-nav">
                    <a href="<?php echo home_url(); ?>">Home</a> /
                    <span id="category-title"><?php echo esc_html($current_category_name); ?></span>
                </div>
                <div class="shop-controls">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Listing -->
    <div class="product-listing-area">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-4 p-0">
                    <aside class="product-sidebar">
                        <div class="sidebar-categories">
                            <h3>PRODUCT CATEGORIES</h3>
                            <ul class="category-list">
                                <li>
                                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
                                       class="category-filter <?php echo ($current_category_slug === 'all') ? 'active' : ''; ?>" 
                                       data-category="all">
                                       All Products
                                    </a>
                                </li>
                                <?php foreach ($product_categories as $category):
                                    $link = get_term_link($category);
                                    $active = ($current_category_slug === $category->slug) ? 'active' : '';
                                ?>
                                <li>
                                    <a href="<?php echo esc_url($link); ?>" 
                                       class="category-filter <?php echo esc_attr($active); ?>" 
                                       data-category="<?php echo esc_attr($category->slug); ?>">
                                       <?php echo esc_html($category->name); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Mobile Dropdown -->
                        <div class="mobile-category-dropdown">
                            <select id="productCategory" class="category-select">
                                <option value="all" <?php selected($current_category_slug, 'all'); ?>>All Products</option>
                                <?php foreach ($product_categories as $category):
                                    $selected = ($current_category_slug === $category->slug) ? 'selected' : '';
                                ?>
                                <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Top Rated Products -->
                        <div class="top-rated">
                            <h3>TOP RATED PRODUCTS</h3>
                            <?php
                            $top_rated = wc_get_products([
                                'status' => 'publish',
                                'limit' => 3,
                                'orderby' => 'rating',
                                'order' => 'DESC'
                            ]);
                            foreach ($top_rated as $product):
                            ?>
                            <div class="product-item">
                                <div class="product-thumb"><?php echo $product->get_image(); ?></div>
                                <div class="product-info">
                                    <h4><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h4>
                                    <div class="rating"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div>
                                    <div class="price-range-text"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </aside>
                </div>

                <!-- Products Grid -->
                <div class="col-md-8 position-relative">
                    <div id="initial-loading" class="loading-overlay" style="display:none;">
                        <div class="spinner-container">
                            <div class="spinner"></div>
                            <p>Loading products...</p>
                        </div>
                    </div>

                    <div class="products-list-wrap">
                        <div class="row" id="post-container">
                            <!-- AJAX-loaded products will appear here -->
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button id="load-more" class="btn btn-load-more" style="display:none;">
                                    <span class="load-more-text">Load More Products</span>
                                    <span class="load-more-spinner" style="display:none;">
                                        <span class="mini-spinner"></span>
                                    </span>
                                </button>
                                <div id="no-more-products" class="no-more-message" style="display:none;">
                                    <p>You've viewed all products in this category</p>
                                </div>
                            </div>
                        </div>

                        <!-- Category Description -->
                        <div class="category-description-wrap mt-4">
                            <?php
                            if (is_product_category()) {
                                $term = get_queried_object();
                                if ($term && !empty($term->description)) {
                                    echo '<div id="category-description" class="category-description">';
                                    echo wp_kses_post(wpautop($term->description));
                                    echo '</div>';
                                } else {
                                    echo '<div id="category-description" class="category-description" style="display:none;"></div>';
                                }
                            } else {
                                echo '<div id="category-description" class="category-description" style="display:none;"></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
jQuery(document).ready(function($){
    let currentPage = 1;
    let currentCategory = '<?php echo $current_category_slug; ?>';
    let maxPages = 1;
    let isLoading = false;

    // Initialize Nice Select
    function initNiceSelect(){
        const $select = $('#productCategory');
        if(typeof $.fn.niceSelect !== 'undefined'){
            if(!$select.hasClass('nice-select-initialized')){
                $select.niceSelect();
                $select.addClass('nice-select-initialized');
            } else {
                $select.niceSelect('update');
            }
        }
    }

    // Load products via AJAX
    function loadProducts(category, page, replace=false){
        if(isLoading) return;
        isLoading = true;

        const $loadMore = $('#load-more'),
              $loadText = $('.load-more-text'),
              $loadSpinner = $('.load-more-spinner'),
              $noMore = $('#no-more-products');

        if(replace){
            $('#initial-loading').fadeIn(200);
            $('#post-container').css('opacity',0.3);
            $noMore.hide();
        } else {
            $loadMore.prop('disabled',true);
            $loadText.hide();
            $loadSpinner.show();
        }

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data:{
                action: 'load_products',
                category: category,
                page: page,
                nonce: '<?php echo wp_create_nonce("load_products_nonce"); ?>'
            },
            success:function(response){
                if(response.success){
                    const data = response.data;
                    if(replace){
                        $('#post-container').html(data.html).css('opacity',1);
                        $('#initial-loading').fadeOut(200);
                        if($(window).width()<768){
                            $('html,body').animate({scrollTop:$('#post-container').offset().top-100},400);
                        }
                    } else {
                        const $new = $(data.html);
                        $('#post-container').append($new);
                        if($new.length>0){
                            $('html,body').animate({scrollTop:$new.first().offset().top-120},400);
                        }
                    }
                    maxPages = data.max_pages;
                    if(currentPage<maxPages){
                        $loadMore.show();
                        $noMore.hide();
                    } else {
                        $loadMore.hide();
                        if(data.total>0 && !replace) $noMore.fadeIn(300);
                    }
                } else {
                    console.error('Server error:',response);
                }
            },
            error:function(xhr,status,error){
                console.error('AJAX error:',error);
            },
            complete:function(){
                isLoading = false;
                $loadMore.prop('disabled',false);
                $loadText.show();
                $loadSpinner.hide();
                $('#initial-loading').fadeOut(200);
                $('#post-container').css('opacity',1);
            }
        });
    }

    // Handle category change
    function handleCategoryChange(category, categoryName){
        if(isLoading || category===currentCategory) return;
        $('.category-filter').removeClass('active');
        $('.category-filter[data-category="'+category+'"]').addClass('active');
        $('#category-title').text(categoryName);

        $('#productCategory').val(category);
        initNiceSelect();

        currentCategory = category;
        currentPage = 1;
        updateURL(category);
        loadProducts(category,currentPage,true);

        // 🟡 Load category description dynamically
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: { action: 'get_category_description', category: category },
            success: function(response){
                if(response.success && response.data.description){
                    $('#category-description').html(response.data.description).fadeIn(300);
                } else {
                    $('#category-description').fadeOut(200).empty();
                }
            }
        });
    }

    // Desktop click
    $(document).on('click','.category-filter',function(e){
        e.preventDefault();
        const category = $(this).data('category');
        const name = $(this).text().trim();
        handleCategoryChange(category,name);
    });

    // Mobile change
    $(document).on('change','#productCategory',function(){
        const category = $(this).val();
        const name = $(this).find('option:selected').text();
        handleCategoryChange(category,name);
    });

    // Load more click
    $('#load-more').on('click',function(){
        if(isLoading) return;
        currentPage++;
        loadProducts(currentCategory,currentPage,false);
    });

    // Update URL dynamically
    function updateURL(category){
        let url = '<?php echo esc_url(get_permalink(wc_get_page_id("shop"))); ?>';
        if(category!=='all') url = '<?php echo home_url("/product-category/"); ?>'+category+'/';
        window.history.pushState({category:category},'',url);
        const titlePrefix = 'Shop';
        document.title = category==='all'?titlePrefix:category.charAt(0).toUpperCase()+category.slice(1)+' - '+titlePrefix;
    }

    // Back/forward buttons
    window.onpopstate = function(event){
        if(event.state && event.state.category){
            const category = event.state.category;
            const name = category==='all'?'All Products':category.charAt(0).toUpperCase()+category.slice(1);
            currentCategory = category;
            currentPage = 1;

            $('.category-filter').removeClass('active');
            $('.category-filter[data-category="'+category+'"]').addClass('active');
            $('#category-title').text(name);

            $('#productCategory').val(category);
            initNiceSelect();
            loadProducts(category,1,true);
        }
    };

    // Initialize
    initNiceSelect();
    loadProducts(currentCategory,currentPage,true);
});
</script>

<?php get_footer(); ?>
