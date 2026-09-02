<?php
get_header();

$feature_post = food_get_home_feature_post();
$feature_id   = $feature_post instanceof WP_Post ? (int) $feature_post->ID : 0;
$feature_food = $feature_id ? food_get_primary_food_category( $feature_id ) : null;
$feature_topic = $feature_id ? food_get_primary_topic( $feature_id ) : null;
$discover_ids = food_get_rotating_post_ids( 5, $feature_id ? array( $feature_id ) : array() );
?>

<section class="home-hero home-hero-v5">
	<div class="container home-hero-grid home-hero-grid-v5">
		<div class="hero-main hero-main-v5">
			<span class="hero-kicker">Una guía para entender lo que comes</span>
			<h1>Comida, explicada con criterio.</h1>
			<p>Busca por alimento o por la duda que quieres resolver. FOOD organiza cada guía en dos dimensiones: de qué alimento habla y qué tipo de información necesitas.</p>

			<form class="hero-search hero-search-v5" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="food-search">Buscar</label>
				<input id="food-search" type="search" name="s" placeholder="Busca un alimento, una duda o una técnica…" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar</button>
			</form>

			<nav class="hero-topic-links" aria-label="Explorar por tema">
				<span>También por tema</span>
				<a href="<?php echo esc_url( food_topic_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ); ?>">Seguridad</a>
				<a href="<?php echo esc_url( food_topic_url( 'nutricion', 'Nutrición' ) ); ?>">Nutrición</a>
				<a href="<?php echo esc_url( food_topic_url( 'cocina-tecnica', 'Cocina y técnica' ) ); ?>">Cocina</a>
				<a href="<?php echo esc_url( food_topic_url( 'origen-calidad', 'Origen y calidad' ) ); ?>">Calidad</a>
			</nav>
		</div>

		<?php if ( $feature_id ) : ?>
			<a class="home-feature-card" href="<?php echo esc_url( get_permalink( $feature_id ) ); ?>">
				<div class="home-feature-media <?php echo has_post_thumbnail( $feature_id ) ? 'has-image' : 'has-illustration'; ?>">
					<?php if ( has_post_thumbnail( $feature_id ) ) : ?>
						<?php echo get_the_post_thumbnail( $feature_id, 'food-card', array( 'loading' => 'eager' ) ); ?>
					<?php else : ?>
						<div class="home-feature-illustration family-<?php echo esc_attr( $feature_food ? $feature_food->slug : 'general' ); ?>">
							<?php echo food_category_icon_svg( $feature_food ? $feature_food->slug : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
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
				<div class="home-feature-media has-illustration"><div class="home-feature-illustration family-general"><?php echo food_category_icon_svg( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
				<div class="home-feature-body"><div class="content-dimensions"><span>FOOD</span></div><strong>Las guías destacadas aparecerán aquí automáticamente.</strong><p>Cuando publiques contenido, la portada escogerá una guía destacada y la irá renovando sin tocar el diseño.</p></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section food-families-section">
	<div class="container">
		<header class="section-intro section-intro-v5">
			<div>
				<span class="section-label">Primera dimensión</span>
				<h2>Explora por alimento</h2>
			</div>
			<p>Una clasificación estable para crecer con cientos de artículos sin mezclar productos con tipos de consulta.</p>
		</header>

		<div class="food-family-grid">
			<?php
			$families = array(
				array( 'Carnes', 'carnes', 'Cortes, calidad, conservación y cocción.' ),
				array( 'Pescados y mariscos', 'pescados-mariscos', 'Frescura, especies, seguridad y cocina.' ),
				array( 'Jamón y embutidos', 'jamon-embutidos', 'Curados, procedencia, categorías y calidad.' ),
				array( 'Quesos y lácteos', 'quesos-lacteos', 'Variedades, conservación, usos y elaboración.' ),
				array( 'Aceites', 'aceites', 'Sabor, conservación, usos y aceite de oliva.' ),
				array( 'Legumbres', 'legumbres', 'Tipos, remojo, cocción y composición.' ),
				array( 'Frutas', 'frutas', 'Maduración, temporada, conservación y calidad.' ),
				array( 'Verduras y hortalizas', 'verduras-hortalizas', 'Estado, temporada, cocina y conservación.' ),
				array( 'Cereales, pan y pasta', 'cereales-pan-pasta', 'Arroz, panes, cereales, pasta y harinas.' ),
				array( 'Huevos', 'huevos', 'Etiquetado, frescura, seguridad y cocina.' ),
			);

			foreach ( $families as $family ) : ?>
				<a class="food-family-card family-<?php echo esc_attr( $family[1] ); ?>" href="<?php echo esc_url( food_category_url( $family[1], $family[0] ) ); ?>">
					<span class="food-family-art" aria-hidden="true"><?php echo food_category_icon_svg( $family[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="food-family-content">
						<strong><?php echo esc_html( $family[0] ); ?></strong>
						<small><?php echo esc_html( $family[2] ); ?></small>
					</span>
					<span class="food-family-arrow" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section topic-directory-section">
	<div class="container topic-directory-layout">
		<header class="topic-directory-intro">
			<span class="section-label">Segunda dimensión</span>
			<h2>Explora por lo que quieres saber</h2>
			<p>Un artículo puede ser de <em>Carnes</em> y a la vez de <em>Nutrición</em>, <em>Seguridad</em> o <em>Cocina y técnica</em>. Las dos clasificaciones son independientes.</p>
		</header>

		<div class="topic-directory-list">
			<?php $topic_index = 0; foreach ( food_topic_definitions() as $topic_slug => $topic_definition ) : $topic_index++; $topic_term = get_term_by( 'slug', $topic_slug, 'food_topic' ); ?>
				<a class="topic-directory-row" href="<?php echo esc_url( food_topic_url( $topic_slug, $topic_definition['name'] ) ); ?>">
					<span class="topic-directory-number"><?php echo esc_html( str_pad( (string) $topic_index, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<strong><?php echo esc_html( $topic_definition['name'] ); ?></strong>
					<span class="topic-directory-description"><?php echo esc_html( $topic_definition['description'] ); ?></span>
					<span class="topic-directory-count"><?php echo $topic_term ? esc_html( (string) $topic_term->count ) : '0'; ?></span>
					<span class="topic-directory-arrow" aria-hidden="true">→</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( ! empty( $discover_ids ) ) : ?>
	<section class="section discover-section">
		<div class="container">
			<header class="section-intro section-intro-v5">
				<div>
					<span class="section-label">Descubre algo nuevo</span>
					<h2>Cinco lecturas para empezar</h2>
				</div>
				<p>Esta selección sale del contenido publicado y cambia periódicamente. No depende de ejemplos escritos a mano en la portada.</p>
			</header>

			<div class="discover-grid">
				<?php
				$discover_query = new WP_Query(
					array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => count( $discover_ids ),
						'post__in'            => $discover_ids,
						'orderby'             => 'post__in',
						'ignore_sticky_posts' => true,
					)
				);
				$discover_position = 0;
				while ( $discover_query->have_posts() ) : $discover_query->the_post();
					$discover_position++;
					$food_term  = food_get_primary_food_category();
					$topic_term = food_get_primary_topic();
					?>
					<a class="discover-card <?php echo 1 === $discover_position ? 'discover-card-lead' : ''; ?>" href="<?php the_permalink(); ?>">
						<?php if ( 1 === $discover_position ) : ?>
							<div class="discover-lead-media <?php echo has_post_thumbnail() ? 'has-image' : ''; ?>">
								<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'food-card', array( 'loading' => 'lazy' ) ); else : ?>
									<div class="discover-illustration family-<?php echo esc_attr( $food_term ? $food_term->slug : 'general' ); ?>"><?php echo food_category_icon_svg( $food_term ? $food_term->slug : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="discover-card-body">
							<div class="content-dimensions">
								<?php if ( $food_term ) : ?><span><?php echo esc_html( $food_term->name ); ?></span><?php endif; ?>
								<?php if ( $topic_term ) : ?><span><?php echo esc_html( $topic_term->name ); ?></span><?php endif; ?>
							</div>
							<strong><?php the_title(); ?></strong>
							<?php if ( 1 === $discover_position ) : ?><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p><?php endif; ?>
							<span class="discover-card-arrow" aria-hidden="true">↗</span>
						</div>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( is_active_sidebar( 'home-ad' ) ) : ?>
	<div class="container ad-slot"><?php dynamic_sidebar( 'home-ad' ); ?></div>
<?php endif; ?>

<section class="section latest-guides latest-guides-v5">
	<div class="container">
		<div class="section-head section-head-v5">
			<div><div class="eyebrow">Recién publicado</div><h2>Últimas guías</h2></div>
			<a class="section-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">Ver todos los artículos →</a>
		</div>
		<div class="card-grid">
			<?php
			$food_latest = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 6, 'ignore_sticky_posts' => false ) );
			if ( $food_latest->have_posts() ) :
				while ( $food_latest->have_posts() ) : $food_latest->the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				wp_reset_postdata();
			else : ?>
				<div class="home-empty-state"><strong>La portada ya está preparada para crecer.</strong><p>Las últimas guías aparecerán aquí automáticamente en cuanto empieces a publicar.</p></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
