<?php get_header(); ?>

<section class="custom-single-post py-5">
	<div class="container">
		<div class="row justify-content-center">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article class="single-post-card">

						<!-- Post Thumbnail -->
						<?php if (has_post_thumbnail()) : ?>
							<div class="single-post-thumb">
								<?php the_post_thumbnail('large'); ?>
							</div>
						<?php endif; ?>

						<!-- Post Content -->
						<div class="single-post-body">
							<h1 class="single-post-title"><?php the_title(); ?></h1>

							<div class="single-post-meta">
								<span class="meta-date"><?php echo get_the_date('F j, Y'); ?></span>
								<span class="meta-sep">•</span>
								<span class="meta-author"><?php the_author(); ?></span>
								<span class="meta-sep">•</span>
								<span class="meta-category"><?php the_category(', '); ?></span>
							</div>

							<div class="single-post-content">
								<?php the_content(); ?>
							</div>

							<div class="single-post-tags">
								<?php the_tags('<strong>Tags:</strong> ', ', ', ''); ?>
							</div>



							<!-- Navigation -->
							<div class="single-post-navigation d-flex justify-content-between mt-5">
								<div class="prev-post"><?php previous_post_link('%link', '← Previous Post'); ?></div>
								<div class="next-post"><?php next_post_link('%link', 'Next Post →'); ?></div>
							</div>

						</div>
					</article>
			<?php endwhile;
			endif; ?>

		</div>
	</div>
</section>

<?php get_footer(); ?>