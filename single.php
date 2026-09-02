<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$food_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
	$food_topic    = function_exists( 'food_get_primary_topic' ) ? food_get_primary_topic() : null;
	?>
	<div class="article-shell"><?php food_breadcrumbs(); ?></div>

	<header class="article-header article-shell">
		<div class="article-dimensions">
			<?php if ( $food_category ) : ?>
				<a href="<?php echo esc_url( get_category_link( $food_category ) ); ?>"><?php echo esc_html( $food_category->name ); ?></a>
			<?php endif; ?>
			<?php if ( $food_topic ) : ?>
				<a class="is-topic" href="<?php echo esc_url( get_term_link( $food_topic ) ); ?>"><?php echo esc_html( $food_topic->name ); ?></a>
			<?php endif; ?>
		</div>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?><p class="article-deck"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<div class="article-meta">
			<span>Guía FOOD</span>
			<span>·</span>
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
	</article>

	<?php
	$related_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	);

	$related_tax_query = array( 'relation' => 'OR' );
	if ( $food_category ) {
		$related_tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( $food_category->term_id ),
		);
	}
	if ( $food_topic ) {
		$related_tax_query[] = array(
			'taxonomy' => 'food_topic',
			'field'    => 'term_id',
			'terms'    => array( $food_topic->term_id ),
		);
	}
	if ( count( $related_tax_query ) > 1 ) {
		$related_args['tax_query'] = $related_tax_query;
	}

	$related = new WP_Query( $related_args );
	if ( $related->have_posts() ) : ?>
		<section class="related">
			<div class="container">
				<div class="section-head"><div><div class="eyebrow">Sigue explorando</div><h2>Más guías relacionadas</h2></div></div>
				<div class="card-grid">
					<?php while ( $related->have_posts() ) : $related->the_post(); get_template_part( 'template-parts/card' ); endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
