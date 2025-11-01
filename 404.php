<?php
get_header(); // Include header
?>

<section class="error-404 not-found" style="padding: 100px 0; text-align:center;">
    <div class="container">
        <h1 style="font-size: 80px; margin-bottom: 20px;">404</h1>
        <h2 style="margin-bottom: 20px;">Oops! Page Not Found</h2>
        <p style="margin-bottom: 30px;">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Go Back Home</a>

        <!-- Optional: Search Form -->
        <div style="margin-top: 30px;">
            <?php get_search_form(); ?>
        </div>

        <!-- Optional: Popular Posts / Categories -->
        <div style="margin-top: 50px;">
            <h3>Popular Posts</h3>
            <ul>
                <?php
                $popular_posts = new WP_Query(array(
                    'posts_per_page' => 5,
                    'orderby' => 'comment_count',
                    'order' => 'DESC'
                ));
                if($popular_posts->have_posts()):
                    while($popular_posts->have_posts()): $popular_posts->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
        </div>
    </div>
</section>

<?php
get_footer(); // Include footer
?>
