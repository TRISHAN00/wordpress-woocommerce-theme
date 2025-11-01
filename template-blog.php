<?php
/*
Template Name: Custom Blog
*/
get_header(); ?>

<section class="custom-blog-section py-5">
    <div class="container">
        <h1 class="page-title text-center mb-5"><?php the_title(); ?></h1>

        <div class="row">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 6,
            );
            $blog_query = new WP_Query($args);

            if ($blog_query->have_posts()) :
                while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <div class="col-lg-4 col-md-6 mb-5">
                        <article class="blog-card">
                            <div class="blog-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-thumb.jpg" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="blog-body">
                                <div class="blog-meta">
                                    <span class="meta-date"><?php echo get_the_date('F j, Y'); ?></span>
                                    <span class="meta-sep">•</span>
                                    <span class="meta-category"><?php the_category(', '); ?></span>
                                </div>

                                <h2 class="blog-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <p class="blog-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?>
                                </p>

                                <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                    Read More <span>→</span>
                                </a>
                            </div>
                        </article>
                    </div>
            <?php endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-center">No posts found.</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>