<?php
get_header();

$english      = function_exists( 'food_is_english' ) && food_is_english();
$language_url = function_exists( 'food_language_home_url' ) ? food_language_home_url() : home_url( '/' );
$feature_post = food_get_home_feature_post();
$feature_id   = $feature_post instanceof WP_Post ? (int) $feature_post->ID : 0;
$feature_food = $feature_id ? food_get_primary_food_category( $feature_id ) : null;
$feature_topic = $feature_id ? food_get_primary_topic( $feature_id ) : null;
$feature_visual = $feature_id ? food_get_post_visual_context( $feature_id ) : null;

$discover_candidates = get_posts(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 24,
		'fields'         => 'ids',
		'post__not_in'   => array_values( array_unique( array_merge( $feature_id ? array( $feature_id ) : array(), food_home_ignored_post_ids() ) ) ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
if ( count( $discover_candidates ) > 5 ) {
	shuffle( $discover_candidates );
}
$discover_ids = array_slice( $discover_candidates, 0, 5 );

$topic_descriptions_en = array(
	'nutricion-composicion' => 'Protein, fats, carbohydrates, fiber, calories, vitamins and minerals with useful context.',
	'rankings-mejores-fuentes' => 'Practical rankings that compare foods using a clear nutritional or quality criterion.',
	'comparativas' => 'Side-by-side differences between foods, varieties, formats and cooking methods.',
	'seguridad-alimentaria' => 'How to judge food safety, reduce risk and know when food should be discarded.',
	'conservacion-almacenamiento' => 'How long food keeps and the best way to store it in the fridge, pantry or containers.',
	'congelacion-descongelacion' => 'What can be frozen, how long it keeps and how to thaw it safely.',
	'cocina-ciencia-alimentos' => 'What happens inside food when it is heated, mixed or transformed during cooking.',
	'preparacion-tecnicas-cocina' => 'Methods, temperatures and techniques for more reliable cooking results.',
	'salud-consumo-habitual' => 'How different foods fit into everyday eating patterns and regular consumption.',
	'conceptos-nutricion' => 'Clear explanations of protein, fiber, energy density and other nutrition fundamentals.',
	'mitos-preguntas-frecuentes' => 'Direct answers to common questions and popular claims about food and nutrition.',
	'procesamiento-produccion-elaboracion' => 'How food is produced, processed, fermented, cured and manufactured.',
	'compra-calidad-maduracion' => 'How to choose food, recognize quality, understand ripeness and read useful signals.',
);
?>

<section class="home-hero home-hero-v5">
	<div class="container home-hero-grid home-hero-grid-v5">
		<div class="hero-main hero-main-v5">
			<span class="hero-kicker"><?php echo esc_html( $english ? 'Food, quality and cooking' : 'Alimentos, calidad y cocina' ); ?></span>
			<h1><?php echo esc_html( $english ? 'Better food choices start with better understanding.' : 'Comer mejor empieza por entender mejor.' ); ?></h1>
			<p><?php echo esc_html( $english ? 'Quinnoa explains what is behind the food we eat: how to choose it, store it, cook it and compare its quality and composition, with clear practical guidance.' : 'Quinnoa explica qué hay detrás de los alimentos: cómo elegirlos, conservarlos, cocinarlos y comparar su calidad y composición, con información clara y práctica para el día a día.' ); ?></p>
			<form class="hero-search hero-search-v5" role="search" method="get" action="<?php echo esc_url( $language_url ); ?>">
				<label class="screen-reader-text" for="food-search"><?php echo esc_html( $english ? 'Search' : 'Buscar' ); ?></label>
				<input id="food-search" type="search" name="s" placeholder="<?php echo esc_attr( $english ? 'Search a food, question or technique…' : 'Busca un alimento, una duda o una técnica…' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit"><?php echo esc_html( $english ? 'Search' : 'Buscar' ); ?></button>
			</form>
			<nav class="hero-topic-links" aria-label="<?php echo esc_attr( $english ? 'Explore Quinnoa' : 'Explorar Quinnoa' ); ?>">
				<span><?php echo esc_html( $english ? 'Explore' : 'Explora' ); ?></span>
				<a href="<?php echo esc_url( food_topic_url( 'seguridad-alimentaria', 'Food safety' ) ); ?>"><?php echo esc_html( $english ? 'Food safety' : 'Seguridad alimentaria' ); ?></a>
				<a href="<?php echo esc_url( food_topic_url( 'nutricion-composicion', 'Nutrition' ) ); ?>"><?php echo esc_html( $english ? 'Nutrition' : 'Nutrición' ); ?></a>
				<a href="<?php echo esc_url( food_topic_url( 'cocina-ciencia-alimentos', 'Cooking' ) ); ?>"><?php echo esc_html( $english ? 'Cooking' : 'Cocina' ); ?></a>
				<a href="<?php echo esc_url( food_topic_url( 'conservacion-almacenamiento', 'Storage' ) ); ?>"><?php echo esc_html( $english ? 'Storage' : 'Conservación' ); ?></a>
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
						<?php if ( $feature_food ) : ?><span><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $feature_food->slug ) : $feature_food->name ); ?></span><?php endif; ?>
						<?php if ( $feature_topic ) : ?><span><?php echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $feature_topic ) : $feature_topic->name ); ?></span><?php endif; ?>
					</div>
					<strong><?php echo esc_html( get_the_title( $feature_id ) ); ?></strong>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $feature_id ), 20 ) ); ?></p>
					<span class="feature-read"><?php echo esc_html( $english ? 'Featured guide' : 'Lectura destacada' ); ?> <span aria-hidden="true">↗</span></span>
				</div>
			</a>
		<?php else : ?>
			<div class="home-feature-card home-feature-empty">
				<div class="home-feature-media has-illustration"><div class="home-feature-illustration family-alimentacion-general"><?php echo food_category_icon_svg( 'alimentacion-general' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
				<div class="home-feature-body"><div class="content-dimensions"><span>Quinnoa</span></div><strong><?php echo esc_html( $english ? 'Useful knowledge for choosing and enjoying food better.' : 'Conocimiento útil para elegir y disfrutar mejor los alimentos.' ); ?></strong><p><?php echo esc_html( $english ? 'Clear guides on quality, nutrition, safety, storage and cooking.' : 'Guías sobre calidad, nutrición, seguridad, conservación y cocina explicadas con claridad.' ); ?></p></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section food-families-section">
	<div class="container">
		<header class="section-intro section-intro-v5">
			<div><span class="section-label"><?php echo esc_html( $english ? 'Foods' : 'Alimentos' ); ?></span><h2><?php echo esc_html( $english ? 'Know each food better' : 'Conoce mejor cada alimento' ); ?></h2></div>
			<p><?php echo esc_html( $english ? 'From meat and fish to fruit, cheese, legumes and oils. Learn how to recognize quality, store food safely, cook it well and understand what it contains.' : 'De la carne y el pescado a las frutas, los quesos, las legumbres o el aceite. Descubre cómo reconocer calidad, conservar bien, cocinar mejor y entender lo que aporta cada alimento.' ); ?></p>
		</header>
		<div class="food-family-grid">
			<?php foreach ( food_family_definitions() as $slug => $family ) : ?>
				<a class="food-family-card family-<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( food_category_url( $slug, $family['name'] ) ); ?>">
					<span class="food-family-art" aria-hidden="true"><?php echo food_category_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="food-family-content"><strong><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $slug ) : $family['name'] ); ?></strong><small><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $slug, 'short' ) : $family['short'] ); ?></small></span>
					<span class="food-family-arrow" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section topic-directory-section">
	<div class="container topic-directory-layout">
		<header class="topic-directory-intro">
			<span class="section-label"><?php echo esc_html( $english ? 'Guides by topic' : 'Guías por tema' ); ?></span>
			<h2><?php echo esc_html( $english ? 'Answers for choosing, storing and cooking with confidence' : 'Respuestas para comprar, conservar y cocinar con criterio' ); ?></h2>
			<p><?php echo esc_html( $english ? 'Nutrition, food safety, storage, freezing, cooking, production, buying and quality: practical information for better food decisions.' : 'Nutrición, seguridad alimentaria, conservación, congelación, cocina, elaboración, compra y calidad: información práctica para tomar mejores decisiones alrededor de la comida.' ); ?></p>
		</header>
		<div class="topic-card-grid">
			<?php foreach ( food_topic_definitions() as $slug => $topic ) : ?>
				<a class="topic-card topic-<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( food_topic_url( $slug, $topic['name'] ) ); ?>">
					<span class="topic-card-art" aria-hidden="true"><?php echo food_topic_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="topic-card-copy"><strong><?php echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $slug ) : $topic['name'] ); ?></strong><small><?php echo esc_html( $english && isset( $topic_descriptions_en[ $slug ] ) ? $topic_descriptions_en[ $slug ] : $topic['description'] ); ?></small><span class="topic-card-arrow" aria-hidden="true">↗</span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( ! empty( $discover_ids ) ) : ?>
	<section class="section discover-section">
		<div class="container">
			<header class="section-intro section-intro-v5"><div><span class="section-label"><?php echo esc_html( $english ? 'Keep learning' : 'Para seguir aprendiendo' ); ?></span><h2><?php echo esc_html( $english ? 'Five guides to start with' : 'Cinco lecturas para empezar' ); ?></h2></div><p><?php echo esc_html( $english ? 'A selection of guides for discovering food, answering common questions and understanding what happens when we buy, store and cook.' : 'Una selección de guías para descubrir alimentos, resolver dudas y entender mejor lo que ocurre al comprar, conservar y cocinar.' ); ?></p></header>
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
						<div class="discover-card-body"><div class="content-dimensions"><?php if ( $food_term ) : ?><span><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $food_term->slug ) : $food_term->name ); ?></span><?php endif; ?><?php if ( $topic_term ) : ?><span><?php echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $topic_term ) : $topic_term->name ); ?></span><?php endif; ?></div><strong><?php the_title(); ?></strong><?php if ( 1 === $position ) : ?><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p><?php endif; ?><span class="discover-card-arrow" aria-hidden="true">↗</span></div>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( is_active_sidebar( 'home-ad' ) ) : ?><div class="container ad-slot"><?php dynamic_sidebar( 'home-ad' ); ?></div><?php endif; ?>

<section class="section latest-guides latest-guides-v5">
	<div class="container">
		<div class="section-head section-head-v5"><div><div class="eyebrow"><?php echo esc_html( $english ? 'New reads' : 'Nuevas lecturas' ); ?></div><h2><?php echo esc_html( $english ? 'Latest guides' : 'Últimas guías' ); ?></h2></div></div>
		<div class="card-grid">
			<?php
			$latest = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 6, 'ignore_sticky_posts' => false, 'post__not_in' => food_home_ignored_post_ids() ) );
			if ( $latest->have_posts() ) : while ( $latest->have_posts() ) : $latest->the_post(); get_template_part( 'template-parts/card' ); endwhile; wp_reset_postdata();
			else : ?><div class="home-empty-state"><strong><?php echo esc_html( $english ? 'The first guides are on their way.' : 'Estamos preparando las primeras guías.' ); ?></strong><p><?php echo esc_html( $english ? 'New guides on food, quality, nutrition, safety and cooking will appear here.' : 'Muy pronto encontrarás aquí nuevos artículos sobre alimentos, calidad, nutrición, seguridad y cocina.' ); ?></p></div><?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
