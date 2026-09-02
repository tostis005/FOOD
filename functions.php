<?php
/**
 * FOOD theme functions.
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
 * Editorial architecture.
 *
 * FOOD uses categories as the only visible editorial taxonomy. Product families
 * live below "Alimentos"; transversal search intents remain top-level areas.
 */
function food_editorial_categories() {
	return array(
		'alimentos' => array(
			'name'        => 'Alimentos',
			'description' => 'Guías organizadas por familias de alimentos: cómo elegirlos, conservarlos, entenderlos y cocinarlos.',
			'children'    => array(
				'carnes'                 => array( 'Carnes', 'Tipos de carne, cortes, calidad, conservación, cocina y nutrición práctica.' ),
				'pescados-mariscos'      => array( 'Pescados y mariscos', 'Pescados, mariscos, frescura, conservación, cocina y características del producto.' ),
				'jamon-embutidos'        => array( 'Jamón y embutidos', 'Jamón, paleta, embutidos, curados, calidades, origen, conservación y consumo.' ),
				'quesos-lacteos'         => array( 'Quesos y lácteos', 'Quesos, leche y otros lácteos: variedades, calidad, conservación y usos.' ),
				'aceites'                 => array( 'Aceites', 'Aceite de oliva y otros aceites: sabor, calidad, conservación, usos y dudas frecuentes.' ),
				'legumbres'               => array( 'Legumbres', 'Lentejas, garbanzos, judías y otras legumbres: propiedades, conservación y cocina.' ),
				'frutas'                  => array( 'Frutas', 'Frutas: maduración, conservación, calidad, temporada y dudas habituales.' ),
				'verduras-hortalizas'     => array( 'Verduras y hortalizas', 'Verduras y hortalizas: estado, conservación, cocina, temporada y calidad.' ),
				'cereales-pan-pasta'      => array( 'Cereales, pan y pasta', 'Arroz, cereales, panes y pastas: variedades, conservación, cocina y nutrición.' ),
				'huevos'                  => array( 'Huevos', 'Huevos: conservación, etiquetado, cocina, seguridad y calidad.' ),
			),
		),
		'seguridad-alimentaria' => array(
			'name'        => 'Seguridad alimentaria',
			'description' => 'Respuestas claras para saber cuándo un alimento es seguro, cuándo conviene descartarlo y cómo conservarlo correctamente.',
		),
		'nutricion' => array(
			'name'        => 'Nutrición',
			'description' => 'Comparativas y explicaciones prácticas sobre proteínas, grasas, fibra, energía y composición de los alimentos.',
		),
		'cocina' => array(
			'name'        => 'Cocina',
			'description' => 'Técnicas, errores habituales y explicaciones de lo que ocurre cuando cocinamos.',
		),
		'platos-menus' => array(
			'name'        => 'Platos y menús',
			'description' => 'Ideas y guías para organizar platos completos, menús, comida cotidiana y opciones equilibradas.',
		),
		'origen-calidad' => array(
			'name'        => 'Origen y calidad',
			'description' => 'Denominaciones de origen, sellos, etiquetado, procedencia y criterios para entender la calidad de un alimento.',
		),
	);
}

function food_ensure_editorial_structure() {
	$structure_version = '2';
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
			wp_update_term( $parent->term_id, 'category', array( 'description' => $definition['description'] ) );
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
						'description' => $child[1],
						'parent'      => (int) $parent->term_id,
					)
				);
			}
		}
	}

	// Evergreen content should not expose dates in its URL structure.
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

function food_category_fallback() {
	$items = array(
		'Alimentos'              => 'alimentos',
		'Seguridad alimentaria' => 'seguridad-alimentaria',
		'Nutrición'              => 'nutricion',
		'Cocina'                 => 'cocina',
		'Platos y menús'         => 'platos-menus',
		'Origen y calidad'       => 'origen-calidad',
	);

	echo '<ul class="menu food-fallback-menu">';
	foreach ( $items as $label => $slug ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( food_category_url( $slug, $label ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

function food_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="Migas de pan"><a href="' . esc_url( home_url( '/' ) ) . '">Inicio</a><span>›</span>';
	if ( is_single() ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			echo '<a href="' . esc_url( get_category_link( $categories[0] ) ) . '">' . esc_html( $categories[0]->name ) . '</a><span>›</span>';
		}
		echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() ) {
		echo '<span aria-current="page">' . esc_html( single_cat_title( '', false ) ) . '</span>';
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
