<?php
// Query all published posts from custom post type "banner"
$args = array(
    'post_type'      => 'banner',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
);

$banner_query = new WP_Query( $args );

if ( $banner_query->have_posts() ) :
?>
<section class="banner-area">
    <div class="swiper banner-area-slide-active">
        <div class="swiper-wrapper">
            <?php while ( $banner_query->have_posts() ) : $banner_query->the_post(); ?>
                <div class="swiper-slide">
                    <div class="banner-area-slide-item">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php 
                                // Get featured image URL
                                $desktop_image = get_the_post_thumbnail_url( get_the_ID(), 'full' ); 
                            ?>
                            <img class="desktop-img" src="<?php echo esc_url( $desktop_image ); ?>" alt="<?php the_title_attribute(); ?>" />
                        <?php endif; ?>

                        <?php 
                        // Optional: if you have a separate mobile image field in ACF
                        $mobile_image = get_field( 'mobile_image', get_the_ID() ); 
                        if ( $mobile_image ) : ?>
                            <img class="mobile-img" src="<?php echo esc_url( $mobile_image ); ?>" alt="<?php the_title_attribute(); ?>" />
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="swiper-pagination"></div>
    </div>
</section>
<?php 
endif;
wp_reset_postdata();
?>
