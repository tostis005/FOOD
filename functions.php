<?php
/**
 * Pommelo theme functions.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_theme_setup() {
	load_theme_textdomain( 'food', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );
	add_image_size( 'food-card', 760, 500, true );
	add_image_size( 'food-hero', 1400, 860, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'food' ),
			'footer'  => __( 'Menú del pie', 'food' ),
		)
	);
}
add_action( 'after_setup_theme', 'food_theme_setup' );

function food_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'food-style', get_stylesheet_uri(), array(), $version );

	$editorial_css = get_template_directory() . '/assets/css/editorial.css';
	if ( file_exists( $editorial_css ) ) {
		wp_enqueue_style(
			'food-editorial',
			get_template_directory_uri() . '/assets/css/editorial.css',
			array( 'food-style' ),
			(string) filemtime( $editorial_css )
		);
	}

	wp_enqueue_script( 'food-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'food_enqueue_assets' );

function food_widgets_init() {
	$areas = array(
		'home-ad'    => 'Publicidad · portada',
		'article-ad' => 'Publicidad · artículo',
		'footer-1'   => 'Pie · columna 1',
		'footer-2'   => 'Pie · columna 2',
	);

	foreach ( $areas as $id => $name ) {
		register_sidebar(
			array(
				'name'          => __( $name, 'food' ),
				'id'            => $id,
				'before_widget' => '<div class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'food_widgets_init' );

function food_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'food_excerpt_length', 999 );

function food_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 210 ) );
	return sprintf( _n( '%s min de lectura', '%s min de lectura', $minutes, 'food' ), $minutes );
}

/**
 * Definitive first-version classification by food.
 *
 * A post may have one of these terms, or none when the subject is purely
 * transversal. The independent article-type taxonomy is defined separately.
 */
function food_family_definitions() {
	return array(
		'alimentacion-general' => array(
			'name'        => 'Alimentación general',
			'description' => 'Guías generales sobre alimentos, hábitos cotidianos y cuestiones que afectan a varias familias de productos.',
			'short'       => 'Conceptos y dudas que abarcan distintos alimentos.',
		),
		'carnes' => array(
			'name'        => 'Carnes',
			'description' => 'Tipos de carne, cortes, calidad, conservación, preparación, cocción y composición nutricional.',
			'short'       => 'Cortes, calidad, conservación y cocina.',
		),
		'pescados-mariscos' => array(
			'name'        => 'Pescados y mariscos',
			'description' => 'Pescados y mariscos: especies, frescura, seguridad, conservación, nutrición y técnicas de cocina.',
			'short'       => 'Especies, frescura, seguridad y cocina.',
		),
		'huevos' => array(
			'name'        => 'Huevos',
			'description' => 'Huevos: etiquetado, frescura, conservación, seguridad alimentaria, nutrición y formas de cocinarlos.',
			'short'       => 'Frescura, etiquetado, nutrición y cocina.',
		),
		'lacteos-quesos' => array(
			'name'        => 'Lácteos y quesos',
			'description' => 'Leche, yogur, quesos y otros lácteos: composición, variedades, conservación, calidad y elaboración.',
			'short'       => 'Leche, yogur, quesos, calidad y conservación.',
		),
		'legumbres-soja' => array(
			'name'        => 'Legumbres y soja',
			'description' => 'Lentejas, garbanzos, judías, soja y derivados: nutrición, remojo, cocción, conservación y usos.',
			'short'       => 'Legumbres, soja, remojo, cocción y nutrición.',
		),
		'frutos-secos-semillas' => array(
			'name'        => 'Frutos secos y semillas',
			'description' => 'Frutos secos y semillas: composición nutricional, conservación, tostado, consumo y diferencias entre variedades.',
			'short'       => 'Nueces, almendras, semillas y sus propiedades.',
		),
		'cereales-pseudocereales-derivados' => array(
			'name'        => 'Cereales, pseudocereales y derivados',
			'description' => 'Arroz, avena, trigo, quinoa, pan, pasta, harinas y otros derivados: nutrición, conservación y cocina.',
			'short'       => 'Arroz, avena, quinoa, pan, pasta y harinas.',
		),
		'tuberculos' => array(
			'name'        => 'Tubérculos',
			'description' => 'Patata, boniato y otros tubérculos: conservación, seguridad, composición, preparación y cocina.',
			'short'       => 'Patata, boniato, conservación y cocina.',
		),
		'verduras-hortalizas-setas' => array(
			'name'        => 'Verduras, hortalizas y setas',
			'description' => 'Verduras, hortalizas y setas: temporada, frescura, conservación, seguridad, nutrición y técnicas de cocina.',
			'short'       => 'Frescura, temporada, setas, verduras y cocina.',
		),
		'frutas' => array(
			'name'        => 'Frutas',
			'description' => 'Frutas: maduración, temporada, conservación, seguridad, composición nutricional y señales de calidad.',
			'short'       => 'Maduración, temporada, conservación y calidad.',
		),
		'aceites-grasas' => array(
			'name'        => 'Aceites y grasas',
			'description' => 'Aceite de oliva, otros aceites y grasas culinarias: composición, calidad, conservación y usos en cocina.',
			'short'       => 'Aceite de oliva, grasas, calidad y usos.',
		),
		'bebidas' => array(
			'name'        => 'Bebidas',
			'description' => 'Agua, café, té, infusiones y otras bebidas: composición, preparación, conservación y consumo.',
			'short'       => 'Agua, café, té, infusiones y otras bebidas.',
		),
		'chocolate-cacao-dulces' => array(
			'name'        => 'Chocolate, cacao y alimentos dulces',
			'description' => 'Chocolate, cacao y alimentos dulces: ingredientes, composición, calidad, conservación y elaboración.',
			'short'       => 'Chocolate, cacao, dulces, ingredientes y calidad.',
		),
		'fermentados' => array(
			'name'        => 'Fermentados',
			'description' => 'Alimentos fermentados: procesos, microorganismos, conservación, seguridad, elaboración y consumo.',
			'short'       => 'Fermentación, elaboración, conservación y consumo.',
		),
		'algas-especias-otros-alimentos' => array(
			'name'        => 'Algas, especias y otros alimentos',
			'description' => 'Algas, especias, condimentos y otros alimentos: usos, calidad, composición y conservación.',
			'short'       => 'Algas, especias, condimentos y otros alimentos.',
		),
	);
}

function food_editorial_categories() {
	$children = array();
	foreach ( food_family_definitions() as $slug => $definition ) {
		$children[ $slug ] = array( $definition['name'], $definition['description'] );
	}

	return array(
		'alimentos' => array(
			'name'        => 'Alimentos',
			'description' => 'Información práctica sobre alimentos: nutrición, seguridad alimentaria, conservación, calidad, preparación y cocina.',
			'children'    => $children,
		),
	);
}

function food_ensure_editorial_structure() {
	$structure_version = '4';
	if ( get_option( 'food_editorial_structure_version' ) === $structure_version ) {
		return;
	}

	foreach ( food_editorial_categories() as $slug => $definition ) {
		$parent = get_term_by( 'slug', $slug, 'category' );
		if ( ! $parent ) {
			$created = wp_insert_term(
				$definition['name'],
				'category',
				array(
					'slug'        => $slug,
					'description' => $definition['description'],
				)
			);
			if ( ! is_wp_error( $created ) ) {
				$parent = get_term( (int) $created['term_id'], 'category' );
			}
		} else {
			wp_update_term(
				$parent->term_id,
				'category',
				array(
					'name'        => $definition['name'],
					'description' => $definition['description'],
				)
			);
		}

		if ( empty( $definition['children'] ) || ! $parent || is_wp_error( $parent ) ) {
			continue;
		}

		foreach ( $definition['children'] as $child_slug => $child ) {
			$child_term = get_term_by( 'slug', $child_slug, 'category' );
			if ( ! $child_term ) {
				wp_insert_term(
					$child[0],
					'category',
					array(
						'slug'        => $child_slug,
						'description' => $child[1],
						'parent'      => (int) $parent->term_id,
					)
				);
			} else {
				wp_update_term(
					$child_term->term_id,
					'category',
					array(
						'name'        => $child[0],
						'description' => $child[1],
						'parent'      => (int) $parent->term_id,
					)
				);
			}
		}
	}

	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules( false );
	}

	update_option( 'food_editorial_structure_version', $structure_version );
}
add_action( 'init', 'food_ensure_editorial_structure', 30 );

function food_category_url( $slug, $fallback_label = '' ) {
	$term = get_category_by_slug( $slug );
	if ( $term ) {
		return get_category_link( $term );
	}

	$search = $fallback_label ? $fallback_label : str_replace( '-', ' ', $slug );
	return home_url( '/?s=' . rawurlencode( $search ) );
}

function food_post_url_by_slug( $slug, $fallback_search = '' ) {
	$post = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $post instanceof WP_Post ) {
		return get_permalink( $post );
	}

	return home_url( '/?s=' . rawurlencode( $fallback_search ? $fallback_search : $slug ) );
}

/** Legacy fallback kept for compatibility with older templates. */
function food_category_fallback() {
	echo '<ul class="menu food-fallback-menu">';
	printf( '<li><a href="%s">%s</a></li>', esc_url( food_category_url( 'alimentos', 'Alimentos' ) ), esc_html( 'Alimentos' ) );
	echo '</ul>';
}

function food_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="Migas de pan"><a href="' . esc_url( home_url( '/' ) ) . '">Inicio</a><span>›</span>';
	if ( is_single() ) {
		$food_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
		if ( $food_category ) {
			echo '<a href="' . esc_url( get_category_link( $food_category ) ) . '">' . esc_html( $food_category->name ) . '</a><span>›</span>';
		}
		echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() || is_tax( 'food_topic' ) ) {
		echo '<span aria-current="page">' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		echo '<span aria-current="page">Buscar</span>';
	}
	echo '</nav>';
}

function food_article_schema() {
	if ( ! is_single() || 'post' !== get_post_type() ) {
		return;
	}

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'datePublished'    => get_the_date( DATE_W3C ),
		'dateModified'     => get_the_modified_date( DATE_W3C ),
		'mainEntityOfPage' => get_permalink(),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( has_post_thumbnail() ) {
		$schema['image'] = array( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'food_article_schema', 20 );

function food_tag_archive_robots( $robots ) {
	if ( is_tag() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'food_tag_archive_robots' );

function food_body_classes( $classes ) {
	$classes[] = 'food-theme';
	return $classes;
}
add_filter( 'body_class', 'food_body_classes' );

$food_editorial_taxonomies = get_template_directory() . '/inc/editorial-taxonomies.php';
if ( file_exists( $food_editorial_taxonomies ) ) {
	require_once $food_editorial_taxonomies;
}

$food_visual_taxonomy = get_template_directory() . '/inc/visual-taxonomy.php';
if ( file_exists( $food_visual_taxonomy ) ) {
	require_once $food_visual_taxonomy;
}
