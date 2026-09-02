<?php get_header(); ?>

<?php
$archive_visual = ( is_category() || is_tax( 'food_topic' ) ) && function_exists( 'food_get_term_visual_context' )
	? food_get_term_visual_context()
	: null;
?>

<div class="container archive-wrap">
	<?php food_breadcrumbs(); ?>
	<header class="archive-header <?php echo $archive_visual ? 'has-taxonomy-visual' : ''; ?>">
		<div class="archive-header-copy">
			<div class="eyebrow">
				<?php
				if ( is_search() ) {
					echo 'Resultados';
				} elseif ( is_tax( 'food_topic' ) ) {
					echo 'Guías por tema';
				} elseif ( is_category() ) {
					echo 'Guías por alimento';
				} else {
					echo 'Archivo Pometum';
				}
				?>
			</div>
			<h1>
				<?php
				if ( is_search() ) {
					printf( esc_html__( 'Resultados para “%s”', 'food' ), esc_html( get_search_query() ) );
				} elseif ( is_category() ) {
					single_cat_title();
				} elseif ( is_tax( 'food_topic' ) ) {
					single_term_title();
				} elseif ( is_tag() ) {
					single_tag_title();
				} else {
					the_archive_title();
				}
				?>
			</h1>
			<?php
			$archive_description = term_description();
			if ( ( is_category() || is_tax( 'food_topic' ) ) && $archive_description ) : ?>
				<div class="taxonomy-description"><?php echo wp_kses_post( $archive_description ); ?></div>
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
		<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Anterior', 'next_text' => 'Siguiente →' ) ); ?></div>
	<?php else : ?>
		<div class="answer-box"><strong>Todavía no hay guías aquí</strong><p>Estamos preparando contenido para esta sección. Mientras tanto puedes explorar otras guías de Pometum.</p></div>
		<div class="search-panel"><?php get_search_form(); ?></div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
