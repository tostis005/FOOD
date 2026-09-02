<?php
get_header();

$feature_post   = food_get_home_feature_post();
$feature_id     = $feature_post instanceof WP_Post ? (int) $feature_post->ID : 0;
$feature_food   = $feature_id ? food_get_primary_food_category( $feature_id ) : null;
$feature_topic  = $feature_id ? food_get_primary_topic( $feature_id ) : null;
$feature_visual = $feature_id ? food_get_post_visual_context( $feature_id ) : null;
$discover_ids   = food_get_rotating_post_ids( 5, $feature_id ? array( $feature_id ) : array() );
?>

<section class="home-hero home-hero-v5">
	<div class="container home-hero-grid home-hero-grid-v5">
		<div class="hero-main hero-main-v5">
			<span class="hero-kicker">Guías claras sobre alimentos, nutrición y cocina</span>
			<h1>Entiende mejor lo que comes.</h1>
			<p>Pommelo reúne información práctica sobre alimentos, nutrición, seguridad alimentaria, conservación, calidad y cocina para ayudarte a elegir, guardar y preparar mejor la comida de cada día.</p>
			<form class="hero-search hero-search-v5" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="food-search">Buscar</label>
				<input id="food-search" type="search" name="s" placeholder="Busca un alimento, una duda o una técnica…" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar</button>
			</form>
			<nav class="hero-topic-links" aria-label="Explorar Pommelo">
				<span>Empieza por</span>
				<a href="<?php echo esc_url( food_topic_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ); ?>">Seguridad alimentaria</a>
				<a href="<?php echo esc_url( food_topic_url( 'nutricion-composicion', 'Nutrición y composición' ) ); ?>">Nutrición</a>
				<a href="<?php echo esc_url( food_topic_url( 'cocina-ciencia-alimentos', 'Cocina y ciencia de los alimentos' ) ); ?>">Cocina</a>
				<a href="<?php echo esc_url( food_topic_url( 'conservacion-almacenamiento', 'Conservación y almacenamiento' ) ); ?>">Conservación</a>
			</nav>
		</div>

		<?php if ( $feature_id ) : ?>
			<a class="home-feature-card" href="<?php echo esc_url( get_permalink( $feature_id ) ); ?>">
				<div class="home-feature-media <?php echo has_post_thumbnail( $feature_id ) ? 'has-image' : 'has-illustration'; ?>">
					<?php if ( has_post_thumbnail( $feature_id ) ) : ?>
						<?php echo get_the_post_thumbnail( $feature_id, 'food-card', array( 'loading' => 'eager' ) ); ?>
					<?php else : ?>
						<div class="home-feature-illustration <?php echo esc_attr( $feature_visual['class'] ); ?>"><?php echo $feature_visual['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</div>
				<div class="home-feature-body">
					<div class="content-dimensions">
						<?php if ( $feature_food ) : ?><span><?php echo esc_html( $feature_food->name ); ?></span><?php endif; ?>
						<?php if ( $feature_topic ) : ?><span><?php echo esc_html( $feature_topic->name ); ?></span><?php endif; ?>
					</div>
					<strong><?php echo esc_html( get_the_title( $feature_id ) ); ?></strong>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $feature_id ), 20 ) ); ?></p>
					<span class="feature-read">Guía destacada <span aria-hidden="true">↗</span></span>
				</div>
			</a>
		<?php else : ?>
			<div class="home-feature-card home-feature-empty">
				<div class="home-feature-media has-illustration"><div class="home-feature-illustration family-alimentacion-general"><?php echo food_category_icon_svg( 'alimentacion-general' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
				<div class="home-feature-body"><div class="content-dimensions"><span>Pommelo</span></div><strong>Guías prácticas para resolver dudas reales sobre comida.</strong><p>Explora alimentos, nutrición, seguridad, conservación y cocina con explicaciones claras y útiles.</p></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section food-families-section">
	<div class="container">
		<header class="section-intro section-intro-v5">
			<div><span class="section-label">Alimentos</span><h2>Encuentra información por tipo de alimento</h2></div>
			<p>Desde carnes, pescados y lácteos hasta tubérculos, frutos secos, bebidas o fermentados. Encuentra guías sobre nutrición, seguridad, conservación, calidad y cocina.</p>
		</header>
		<div class="food-family-grid">
			<?php foreach ( food_family_definitions() as $slug => $family ) : ?>
				<a class="food-family-card family-<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( food_category_url( $slug, $family['name'] ) ); ?>">
					<span class="food-family-art" aria-hidden="true"><?php echo food_category_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="food-family-content"><strong><?php echo esc_html( $family['name'] ); ?></strong><small><?php echo esc_html( $family['short'] ); ?></small></span>
					<span class="food-family-arrow" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section topic-directory-section">
	<div class="container topic-directory-layout">
		<header class="topic-directory-intro">
			<span class="section-label">Guías por tema</span>
			<h2>Resuelve tus dudas sobre alimentación</h2>
			<p>Consulta información sobre composición nutricional, seguridad alimentaria, conservación, congelación, cocina, salud, elaboración, compra y calidad de los alimentos.</p>
		</header>
		<div class="topic-card-grid">
			<?php foreach ( food_topic_definitions() as $slug => $topic ) : ?>
				<a class="topic-card topic-<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( food_topic_url( $slug, $topic['name'] ) ); ?>">
					<span class="topic-card-art" aria-hidden="true"><?php echo food_topic_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="topic-card-copy"><strong><?php echo esc_html( $topic['name'] ); ?></strong><small><?php echo esc_html( $topic['description'] ); ?></small><span class="topic-card-arrow" aria-hidden="true">↗</span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( ! empty( $discover_ids ) ) : ?>
	<section class="section discover-section">
		<div class="container">
			<header class="section-intro section-intro-v5"><div><span class="section-label">Descubre algo nuevo</span><h2>Cinco lecturas para empezar</h2></div><p>Una selección de guías útiles para descubrir respuestas sobre alimentos, cocina, nutrición, seguridad y calidad.</p></header>
			<div class="discover-grid">
				<?php
				$query = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => count( $discover_ids ), 'post__in' => $discover_ids, 'orderby' => 'post__in', 'ignore_sticky_posts' => true ) );
				$position = 0;
				while ( $query->have_posts() ) : $query->the_post();
					$position++;
					$food_term = food_get_primary_food_category();
					$topic_term = food_get_primary_topic();
					$visual = food_get_post_visual_context();
				?>
					<a class="discover-card <?php echo 1 === $position ? 'discover-card-lead' : ''; ?>" href="<?php the_permalink(); ?>">
						<?php if ( 1 === $position ) : ?><div class="discover-lead-media <?php echo has_post_thumbnail() ? 'has-image' : ''; ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'food-card', array( 'loading' => 'lazy' ) ); else : ?><div class="discover-illustration <?php echo esc_attr( $visual['class'] ); ?>"><?php echo $visual['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?></div><?php endif; ?>
						<div class="discover-card-body"><div class="content-dimensions"><?php if ( $food_term ) : ?><span><?php echo esc_html( $food_term->name ); ?></span><?php endif; ?><?php if ( $topic_term ) : ?><span><?php echo esc_html( $topic_term->name ); ?></span><?php endif; ?></div><strong><?php the_title(); ?></strong><?php if ( 1 === $position ) : ?><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p><?php endif; ?><span class="discover-card-arrow" aria-hidden="true">↗</span></div>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( is_active_sidebar( 'home-ad' ) ) : ?><div class="container ad-slot"><?php dynamic_sidebar( 'home-ad' ); ?></div><?php endif; ?>

<section class="section latest-guides latest-guides-v5">
	<div class="container">
		<div class="section-head section-head-v5"><div><div class="eyebrow">Recién publicado</div><h2>Últimas guías</h2></div><a class="section-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">Ver todos los artículos →</a></div>
		<div class="card-grid">
			<?php
			$latest = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 6, 'ignore_sticky_posts' => false, 'post__not_in' => food_home_ignored_post_ids() ) );
			if ( $latest->have_posts() ) : while ( $latest->have_posts() ) : $latest->the_post(); get_template_part( 'template-parts/card' ); endwhile; wp_reset_postdata();
			else : ?><div class="home-empty-state"><strong>Estamos preparando las primeras guías.</strong><p>Muy pronto encontrarás aquí nuevos artículos sobre alimentos, nutrición, seguridad y cocina.</p></div><?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
