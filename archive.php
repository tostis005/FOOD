<?php get_header(); ?>

<div class="container archive-wrap">
	<?php food_breadcrumbs(); ?>
	<header class="archive-header">
		<div class="eyebrow"><?php echo is_search() ? 'Resultados' : 'Archivo FOOD'; ?></div>
		<h1>
			<?php
			if ( is_search() ) {
				printf( esc_html__( 'Resultados para “%s”', 'food' ), esc_html( get_search_query() ) );
			} elseif ( is_category() ) {
				single_cat_title();
			} elseif ( is_tag() ) {
				single_tag_title();
			} else {
				the_archive_title();
			}
			?>
		</h1>
		<?php if ( is_category() && category_description() ) : ?><p><?php echo wp_kses_post( category_description() ); ?></p><?php endif; ?>
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
		<div class="answer-box"><strong>No hemos encontrado resultados</strong><p>Prueba con una palabra más general, como “patata”, “jamón”, “aceite” o “carne”.</p></div>
		<div class="search-panel"><?php get_search_form(); ?></div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
