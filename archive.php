<?php get_header(); ?>

<?php
$food_english = function_exists( 'food_is_english' ) && food_is_english();
$archive_visual = ( is_category() || is_tax( 'food_topic' ) ) && function_exists( 'food_get_term_visual_context' )
	? food_get_term_visual_context()
	: null;
$archive_term = ( is_category() || is_tax( 'food_topic' ) ) ? get_queried_object() : null;
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
					echo esc_html( $food_english ? 'Guides by topic' : 'Guías por tema' );
				} elseif ( is_category() ) {
					echo esc_html( $food_english ? 'Guides by food' : 'Guías por alimento' );
				} else {
					echo esc_html( $food_english ? 'Pometum archive' : 'Archivo Pometum' );
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
				<div class="taxonomy-description"><p><?php echo esc_html( is_category() ? food_family_display( $archive_term->slug, 'short' ) : 'Practical Pometum guides in this topic, selected for the English edition.' ); ?></p></div>
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

	<?php if ( have_posts() ) : ?>
		<div class="card-grid">
			<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card' ); endwhile; ?>
		</div>
		<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => $food_english ? '← Previous' : '← Anterior', 'next_text' => $food_english ? 'Next →' : 'Siguiente →' ) ); ?></div>
	<?php else : ?>
		<div class="answer-box"><strong><?php echo esc_html( $food_english ? 'No guides here yet' : 'Todavía no hay guías aquí' ); ?></strong><p><?php echo esc_html( $food_english ? 'We are preparing content for this section. You can explore other Pometum guides in the meantime.' : 'Estamos preparando contenido para esta sección. Mientras tanto puedes explorar otras guías de Pometum.' ); ?></p></div>
		<div class="search-panel"><?php get_search_form(); ?></div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
