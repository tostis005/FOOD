<?php
$food_article_layout_css = get_template_directory() . '/assets/css/article-layout-v2.css';
if ( file_exists( $food_article_layout_css ) ) {
	wp_enqueue_style(
		'food-article-layout-v2',
		get_template_directory_uri() . '/assets/css/article-layout-v2.css',
		array( 'food-style' ),
		(string) filemtime( $food_article_layout_css )
	);
}
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$food_english  = function_exists( 'food_is_english' ) && food_is_english();
	$food_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
	$food_topics   = function_exists( 'food_get_article_topics' ) ? food_get_article_topics() : array();
	$food_visual   = function_exists( 'food_get_post_visual_context' ) ? food_get_post_visual_context() : null;
	$food_content  = apply_filters( 'the_content', get_the_content() );

	// Older imported articles may contain a prose Sources/Fuentes block inside
	// content_html as well as the structured source list appended by the importer.
	// When the structured list is present, suppress only the earlier prose block.
	if ( false !== strpos( $food_content, 'food-article-sources' ) ) {
		$food_content = preg_replace(
			'#<h2>\s*(?:Fuentes|Sources)\s*</h2>\s*<p>.*?</p>(?=.*?<ul[^>]*class=["\'][^"\']*food-article-sources[^"\']*["\'])#is',
			'',
			$food_content,
			1
		);
	}

	if ( function_exists( 'food_internal_links_inject' ) ) {
		$food_content = food_internal_links_inject( $food_content, get_the_ID() );
	}
	?>
	<div class="article-shell"><?php function_exists( 'food_language_breadcrumbs' ) ? food_language_breadcrumbs() : food_breadcrumbs(); ?></div>

	<header class="article-header article-shell">
		<div class="article-dimensions">
			<?php if ( $food_category ) : ?>
				<a href="<?php echo esc_url( get_category_link( $food_category ) ); ?>"><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $food_category->slug ) : $food_category->name ); ?></a>
			<?php endif; ?>
			<?php foreach ( $food_topics as $food_topic ) : ?>
				<a class="is-topic" href="<?php echo esc_url( get_term_link( $food_topic ) ); ?>"><?php echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $food_topic ) : $food_topic->name ); ?></a>
			<?php endforeach; ?>
		</div>
		<h1><?php the_title(); ?></h1>
		<div class="article-meta"><span><?php echo esc_html( function_exists( 'food_localized_reading_time' ) ? food_localized_reading_time() : food_reading_time() ); ?></span></div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="article-hero"><?php the_post_thumbnail( 'food-hero' ); ?></figure>
	<?php elseif ( $food_visual ) : ?>
		<div class="article-hero-fallback <?php echo esc_attr( $food_visual['class'] ); ?>" aria-hidden="true">
			<span class="article-hero-art"></span>
			<?php echo $food_visual['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>

	<article <?php post_class( 'article-shell' ); ?>>
		<?php if ( has_excerpt() ) : ?><div class="answer-box"><strong><?php echo esc_html( $food_english ? 'Quick answer' : 'Respuesta rápida' ); ?></strong><p><?php echo esc_html( get_the_excerpt() ); ?></p></div><?php endif; ?>
		<?php if ( is_active_sidebar( 'article-ad' ) ) : ?><div class="ad-slot"><?php dynamic_sidebar( 'article-ad' ); ?></div><?php endif; ?>
		<div class="entry-content"><?php echo $food_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="article-share">
			<button
				class="article-share-button"
				type="button"
				data-share-url="<?php echo esc_url( get_permalink() ); ?>"
				data-share-title="<?php echo esc_attr( get_the_title() ); ?>"
				data-share-label="<?php echo esc_attr( $food_english ? 'Share article' : 'Compartir artículo' ); ?>"
				data-copy-label="<?php echo esc_attr( $food_english ? 'Link copied' : 'Enlace copiado' ); ?>"
			>
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="2.5"></circle><circle cx="6" cy="12" r="2.5"></circle><circle cx="18" cy="19" r="2.5"></circle><path d="m8.2 10.8 7.6-4.5M8.2 13.2l7.6 4.5"></path></svg>
				<span class="article-share-label"><?php echo esc_html( $food_english ? 'Share article' : 'Compartir artículo' ); ?></span>
			</button>
			<span class="article-share-status screen-reader-text" aria-live="polite"></span>
		</div>
	</article>

	<?php
	$related_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array_values( array_unique( array_merge( array( get_the_ID() ), function_exists( 'food_internal_link_target_post_ids' ) ? food_internal_link_target_post_ids( get_the_ID() ) : array() ) ) ),
		'ignore_sticky_posts' => true,
	);
	$related_tax_query = array( 'relation' => 'OR' );
	if ( $food_category ) {
		$related_tax_query[] = array( 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => array( $food_category->term_id ) );
	}
	if ( ! empty( $food_topics ) ) {
		$related_tax_query[] = array( 'taxonomy' => 'food_topic', 'field' => 'term_id', 'terms' => array_map( function( $term ) { return (int) $term->term_id; }, $food_topics ) );
	}
	if ( count( $related_tax_query ) > 1 ) {
		$related_args['tax_query'] = $related_tax_query;
	}

	$related = new WP_Query( $related_args );
	if ( $related->have_posts() ) : ?>
		<section class="related">
			<div class="container">
				<div class="section-head"><div><div class="eyebrow"><?php echo esc_html( $food_english ? 'Keep exploring' : 'Sigue explorando' ); ?></div><h2><?php echo esc_html( $food_english ? 'Related articles' : 'Artículos relacionados' ); ?></h2></div></div>
				<div class="card-grid"><?php while ( $related->have_posts() ) : $related->the_post(); get_template_part( 'template-parts/card' ); endwhile; wp_reset_postdata(); ?></div>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>