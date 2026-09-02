<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div class="article-shell"><?php food_breadcrumbs(); ?></div>

	<header class="article-header article-shell">
		<div class="post-category">
			<?php $categories = get_the_category(); echo ! empty( $categories ) ? esc_html( $categories[0]->name ) : esc_html__( 'FOOD', 'food' ); ?>
		</div>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?><p class="article-deck"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<div class="article-meta">
			<span>Por <?php the_author(); ?></span>
			<span>Actualizado <?php echo esc_html( get_the_modified_date() ); ?></span>
			<span><?php echo esc_html( food_reading_time() ); ?></span>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="article-hero"><?php the_post_thumbnail( 'food-hero' ); ?></figure>
	<?php endif; ?>

	<article <?php post_class( 'article-shell' ); ?>>
		<?php if ( has_excerpt() ) : ?>
			<div class="answer-box">
				<strong>Respuesta rápida</strong>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'article-ad' ) ) : ?>
			<div class="ad-slot"><?php dynamic_sidebar( 'article-ad' ); ?></div>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( has_tag() ) : ?><div class="article-tags"><?php the_tags( '', ' ', '' ); ?></div><?php endif; ?>
	</article>

	<?php
	$related_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( $categories ) ) {
		$related_args['category__in'] = array( $categories[0]->term_id );
	}
	$related = new WP_Query( $related_args );
	if ( $related->have_posts() ) : ?>
		<section class="related">
			<div class="container">
				<div class="section-head"><div><div class="eyebrow">Sigue aprendiendo</div><h2>También te puede interesar</h2></div></div>
				<div class="card-grid">
					<?php while ( $related->have_posts() ) : $related->the_post(); get_template_part( 'template-parts/card' ); endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
