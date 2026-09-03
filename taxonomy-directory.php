<?php
get_header();

$english = function_exists( 'food_is_english' ) && food_is_english();
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

<div class="container archive-wrap directory-page-wrap">
	<nav class="breadcrumbs" aria-label="<?php echo esc_attr( $english ? 'Breadcrumbs' : 'Migas de pan' ); ?>">
		<a href="<?php echo esc_url( function_exists( 'food_language_home_url' ) ? food_language_home_url() : home_url( '/' ) ); ?>"><?php echo esc_html( $english ? 'Home' : 'Inicio' ); ?></a>
		<span>›</span>
		<span aria-current="page"><?php echo esc_html( $english ? 'Topics' : 'Temas' ); ?></span>
	</nav>

	<header class="archive-header directory-page-header">
		<div class="archive-header-copy">
			<div class="eyebrow"><?php echo esc_html( $english ? 'Browse Quinnoa' : 'Explora Quinnoa' ); ?></div>
			<h1><?php echo esc_html( $english ? 'Explore by topic' : 'Explora por tema' ); ?></h1>
			<div class="taxonomy-description">
				<p><?php echo esc_html( $english ? 'Choose the kind of question you want to explore: nutrition, comparisons, storage, cooking, food safety, buying and more.' : 'Elige el tipo de pregunta que quieres explorar: nutrición, comparativas, conservación, cocina, seguridad alimentaria, compra y mucho más.' ); ?></p>
			</div>
		</div>
	</header>

	<section class="section topic-directory-section directory-page-section">
		<div class="topic-card-grid">
			<?php foreach ( food_topic_definitions() as $slug => $topic ) : ?>
				<a class="topic-card topic-<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( food_topic_url( $slug, $topic['name'] ) ); ?>">
					<span class="topic-card-art" aria-hidden="true"><?php echo food_topic_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="topic-card-copy">
						<strong><?php echo esc_html( function_exists( 'food_topic_display' ) ? food_topic_display( $slug ) : $topic['name'] ); ?></strong>
						<small><?php echo esc_html( $english && isset( $topic_descriptions_en[ $slug ] ) ? $topic_descriptions_en[ $slug ] : $topic['description'] ); ?></small>
						<span class="topic-card-arrow" aria-hidden="true">↗</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
</div>

<?php get_footer(); ?>