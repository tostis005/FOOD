<?php get_header(); ?>

<?php
$food_english  = function_exists( 'food_is_english' ) && food_is_english();
$archive_visual = ( is_category() || is_tax( 'food_topic' ) ) && function_exists( 'food_get_term_visual_context' )
	? food_get_term_visual_context()
	: null;
$archive_term = ( is_category() || is_tax( 'food_topic' ) ) ? get_queried_object() : null;
$food_loop    = $GLOBALS['wp_query'];

// Build taxonomy archives explicitly so clicking a food/category always means
// “show the articles assigned to this category”, independently of search text.
if ( $archive_term instanceof WP_Term && ( is_category() || is_tax( 'food_topic' ) ) ) {
	$archive_args = array(
		'post_type'            => 'post',
		'post_status'          => 'publish',
		'posts_per_page'       => (int) get_option( 'posts_per_page', 10 ),
		'paged'                => max( 1, (int) get_query_var( 'paged' ) ),
		'ignore_sticky_posts'  => true,
		'food_language_bypass' => 1,
	);

	if ( is_category() && 'alimentacion-general' === $archive_term->slug ) {
		// General imported articles deliberately used the parent Alimentos term in
		// earlier imports. Their canonical family meta is empty, so include them here.
		$archive_args['meta_query'] = array(
			'relation' => 'AND',
			function_exists( 'food_language_query_clause' ) ? food_language_query_clause() : array(),
			array(
				'relation' => 'OR',
				array( 'key' => '_food_food_family', 'value' => '', 'compare' => '=' ),
				array( 'key' => '_food_food_family', 'compare' => 'NOT EXISTS' ),
			),
		);
	} else {
		$archive_args['tax_query'] = array(
			array(
				'taxonomy'         => $archive_term->taxonomy,
				'field'            => 'term_id',
				'terms'            => array( (int) $archive_term->term_id ),
				'include_children' => is_category(),
			),
		);
		if ( function_exists( 'food_language_query_clause' ) ) {
			$archive_args['meta_query'] = array( food_language_query_clause() );
		}
	}

	$food_loop = new WP_Query( $archive_args );
}
?>

<div class="container archive-wrap">
	<?php function_exists( 'food_language_breadcrumbs' ) ? food_language_breadcrumbs() : food_breadcrumbs(); ?>
	<header class="archive-header <?php echo $archive_visual ? 'has-taxonomy-visual' : ''; ?>">
		<div class="archive-header-copy">
			<div class="eyebrow">
				<?php
				if ( is_search() ) {
					echo esc_html( $food_english ? 'Results' : 'Resultados' );
				} elseif ( is_tax( 'food_topic' ) ) {
					echo esc_html( $food_english ? 'Articles by topic' : 'Artículos por tema' );
				} elseif ( is_category() ) {
					echo esc_html( $food_english ? 'Articles by food' : 'Artículos por alimento' );
				} else {
					echo esc_html( $food_english ? 'Quinnoa archive' : 'Archivo Quinnoa' );
				}
				?>
			</div>
			<h1>
				<?php
				if ( is_search() ) {
					printf( esc_html( $food_english ? 'Results for “%s”' : 'Resultados para “%s”' ), esc_html( get_search_query() ) );
				} elseif ( is_category() && $archive_term instanceof WP_Term ) {
					echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $archive_term->slug ) : $archive_term->name );
				} elseif ( is_tax( 'food_topic' ) && $archive_term instanceof WP_Term ) {
					echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $archive_term ) : $archive_term->name );
				} elseif ( is_tag() ) {
					single_tag_title();
				} else {
					the_archive_title();
				}
				?>
			</h1>
			<?php
			$archive_description = term_description();
			if ( ( is_category() || is_tax( 'food_topic' ) ) && $archive_description && ! $food_english ) : ?>
				<div class="taxonomy-description"><?php echo wp_kses_post( $archive_description ); ?></div>
			<?php elseif ( $food_english && $archive_term instanceof WP_Term ) : ?>
				<div class="taxonomy-description"><p><?php echo esc_html( is_category() ? food_family_display( $archive_term->slug, 'short' ) : 'Practical Quinnoa articles in this topic, selected for the English edition.' ); ?></p></div>
			<?php endif; ?>
		</div>

		<?php if ( $archive_visual ) : ?>
			<div class="taxonomy-hero-visual <?php echo esc_attr( $archive_visual['class'] ); ?>" aria-hidden="true">
				<?php echo $archive_visual['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</header>

	<?php if ( is_search() ) : ?>
		<div class="search-panel"><?php get_search_form(); ?></div>
	<?php endif; ?>

	<?php if ( $food_loop->have_posts() ) : ?>
		<div class="card-grid">
			<?php while ( $food_loop->have_posts() ) : $food_loop->the_post(); get_template_part( 'template-parts/card' ); endwhile; ?>
		</div>
		<?php
		$current_page = max( 1, (int) get_query_var( 'paged' ) );
		$pagination   = paginate_links(
			array(
				'current'   => $current_page,
				'total'     => max( 1, (int) $food_loop->max_num_pages ),
				'mid_size'  => 1,
				'prev_text' => $food_english ? '← Previous' : '← Anterior',
				'next_text' => $food_english ? 'Next →' : 'Siguiente →',
			)
		);
		if ( $pagination ) : ?><div class="pagination nav-links"><?php echo wp_kses_post( $pagination ); ?></div><?php endif; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<div class="answer-box"><strong><?php echo esc_html( $food_english ? 'No articles here yet' : 'Todavía no hay artículos aquí' ); ?></strong><p><?php echo esc_html( $food_english ? 'There are no published articles assigned to this section yet. You can browse other Quinnoa articles or use search.' : 'Todavía no hay artículos publicados asignados a esta sección. Puedes explorar otros artículos de Quinnoa o utilizar el buscador.' ); ?></p></div>
		<div class="search-panel"><?php get_search_form(); ?></div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
