<?php
/**
 * Public taxonomy and directory slugs by language.
 *
 * WordPress keeps one canonical internal taxonomy vocabulary. Public routes are
 * localized so Spanish and English have independent, search-friendly URLs.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_english_family_slugs() {
	return array(
		'alimentos'                         => 'foods',
		'alimentacion-general'              => 'food-basics',
		'carnes'                            => 'meat',
		'pescados-mariscos'                 => 'fish-seafood',
		'huevos'                            => 'eggs',
		'lacteos-quesos'                    => 'dairy-cheese',
		'legumbres-soja'                    => 'legumes-soy',
		'frutos-secos-semillas'             => 'nuts-seeds',
		'cereales-pseudocereales-derivados' => 'grains-pseudocereals',
		'tuberculos'                        => 'tubers',
		'verduras-hortalizas-setas'         => 'vegetables-mushrooms',
		'frutas'                            => 'fruit',
		'aceites-grasas'                    => 'oils-fats',
		'bebidas'                           => 'drinks',
		'chocolate-cacao-dulces'            => 'chocolate-cocoa-sweets',
		'fermentados'                       => 'fermented-foods',
		'algas-especias-otros-alimentos'    => 'seaweed-spices-other-foods',
	);
}

function food_english_topic_slugs() {
	return array(
		'nutricion-composicion'                => 'nutrition-composition',
		'rankings-mejores-fuentes'             => 'rankings-best-sources',
		'comparativas'                         => 'comparisons',
		'seguridad-alimentaria'                => 'food-safety',
		'conservacion-almacenamiento'          => 'storage-shelf-life',
		'congelacion-descongelacion'           => 'freezing-thawing',
		'cocina-ciencia-alimentos'             => 'cooking-food-science',
		'preparacion-tecnicas-cocina'          => 'preparation-cooking-techniques',
		'salud-consumo-habitual'               => 'health-everyday-consumption',
		'conceptos-nutricion'                  => 'nutrition-concepts',
		'mitos-preguntas-frecuentes'           => 'myths-common-questions',
		'procesamiento-produccion-elaboracion' => 'processing-production',
		'compra-calidad-maduracion'            => 'buying-quality-ripeness',
	);
}

function food_directory_url( $directory, $language = '' ) {
	$language = $language ?: ( function_exists( 'food_current_language' ) ? food_current_language() : 'es' );
	$routes = array(
		'foods' => array(
			'es' => '/alimentos/',
			'en' => '/en/foods/',
		),
		'topics' => array(
			'es' => '/temas/',
			'en' => '/en/topics/',
		),
		'latest' => array(
			'es' => '/articulos/',
			'en' => '/en/articles/',
		),
	);
	$directory = isset( $routes[ $directory ] ) ? $directory : 'foods';
	$language  = 'en' === $language ? 'en' : 'es';
	return home_url( $routes[ $directory ][ $language ] );
}

function food_directory_current_url( $directory, $language = '' ) {
	$url = food_directory_url( $directory, $language );
	if ( 'latest' !== $directory ) {
		return $url;
	}
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	return $paged > 1 ? trailingslashit( $url ) . 'page/' . $paged . '/' : $url;
}

function food_is_editorial_food_category_slug( $slug ) {
	return 'alimentos' === $slug || ( function_exists( 'food_family_definitions' ) && isset( food_family_definitions()[ $slug ] ) );
}

function food_localize_legacy_english_home_url( $url, $path, $orig_scheme, $blog_id ) {
	$parsed_path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$family_map  = food_english_family_slugs();
	$topic_map   = food_english_topic_slugs();

	if ( preg_match( '#^/en/alimentos/?$#', $parsed_path ) ) {
		return home_url( '/en/foods/', $orig_scheme );
	}
	if ( preg_match( '#^/en/alimentos/([^/]+)(/.*)?$#', $parsed_path, $matches ) && isset( $family_map[ $matches[1] ] ) ) {
		$suffix = isset( $matches[2] ) ? $matches[2] : '/';
		return home_url( '/en/foods/' . $family_map[ $matches[1] ] . $suffix, $orig_scheme );
	}
	if ( preg_match( '#^/en/tema/([^/]+)(/.*)?$#', $parsed_path, $matches ) && isset( $topic_map[ $matches[1] ] ) ) {
		$suffix = isset( $matches[2] ) ? $matches[2] : '/';
		return home_url( '/en/topics/' . $topic_map[ $matches[1] ] . $suffix, $orig_scheme );
	}

	return $url;
}
add_filter( 'home_url', 'food_localize_legacy_english_home_url', 30, 4 );

function food_localized_spanish_category_link( $link, $term_id ) {
	if ( function_exists( 'food_is_english' ) && food_is_english() ) {
		return $link;
	}
	$term = get_term( $term_id, 'category' );
	if ( ! $term instanceof WP_Term || ! food_is_editorial_food_category_slug( $term->slug ) ) {
		return $link;
	}
	return 'alimentos' === $term->slug
		? food_directory_url( 'foods', 'es' )
		: home_url( '/alimentos/' . $term->slug . '/' );
}
add_filter( 'category_link', 'food_localized_spanish_category_link', 40, 2 );

function food_directory_query_vars( $vars ) {
	$vars[] = 'food_directory';
	return $vars;
}
add_filter( 'query_vars', 'food_directory_query_vars' );

function food_register_fully_localized_taxonomy_rewrites() {
	$families = food_english_family_slugs();
	$topics   = food_english_topic_slugs();

	add_rewrite_rule( '^temas/?$', 'index.php?food_directory=topics&food_lang=es', 'top' );
	add_rewrite_rule( '^en/topics/?$', 'index.php?food_directory=topics&food_lang=en', 'top' );

	add_rewrite_rule( '^alimentos/?$', 'index.php?food_directory=foods&food_lang=es', 'top' );
	add_rewrite_rule( '^en/foods/?$', 'index.php?food_directory=foods&food_lang=en', 'top' );

	add_rewrite_rule( '^articulos/?$', 'index.php?food_directory=latest&food_lang=es', 'top' );
	add_rewrite_rule( '^articulos/page/([0-9]+)/?$', 'index.php?food_directory=latest&food_lang=es&paged=$matches[1]', 'top' );
	add_rewrite_rule( '^en/articles/?$', 'index.php?food_directory=latest&food_lang=en', 'top' );
	add_rewrite_rule( '^en/articles/page/([0-9]+)/?$', 'index.php?food_directory=latest&food_lang=en&paged=$matches[1]', 'top' );

	foreach ( $families as $internal_slug => $english_slug ) {
		if ( 'alimentos' === $internal_slug ) {
			continue;
		}
		add_rewrite_rule( '^alimentos/' . preg_quote( $internal_slug, '#' ) . '/page/([0-9]+)/?$', 'index.php?category_name=' . $internal_slug . '&food_lang=es&paged=$matches[1]', 'top' );
		add_rewrite_rule( '^alimentos/' . preg_quote( $internal_slug, '#' ) . '/?$', 'index.php?category_name=' . $internal_slug . '&food_lang=es', 'top' );
		add_rewrite_rule( '^en/foods/' . preg_quote( $english_slug, '#' ) . '/page/([0-9]+)/?$', 'index.php?category_name=' . $internal_slug . '&food_lang=en&paged=$matches[1]', 'top' );
		add_rewrite_rule( '^en/foods/' . preg_quote( $english_slug, '#' ) . '/?$', 'index.php?category_name=' . $internal_slug . '&food_lang=en', 'top' );
	}

	foreach ( $topics as $internal_slug => $english_slug ) {
		add_rewrite_rule( '^en/topics/' . preg_quote( $english_slug, '#' ) . '/page/([0-9]+)/?$', 'index.php?food_topic=' . $internal_slug . '&food_lang=en&paged=$matches[1]', 'top' );
		add_rewrite_rule( '^en/topics/' . preg_quote( $english_slug, '#' ) . '/?$', 'index.php?food_topic=' . $internal_slug . '&food_lang=en', 'top' );
	}

	if ( '4' !== get_option( 'food_localized_taxonomy_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'food_localized_taxonomy_rewrite_version', '4' );
	}
}
add_action( 'init', 'food_register_fully_localized_taxonomy_rewrites', 91 );

function food_prepare_directory_query( $query ) {
	if ( ! $query instanceof WP_Query || ! $query->is_main_query() || ! $query->get( 'food_directory' ) ) {
		return;
	}
	$query->is_404 = false;
	$query->is_home = false;
}
add_action( 'parse_query', 'food_prepare_directory_query', 1 );

function food_directory_prevent_404( $preempt, $query ) {
	if ( $query instanceof WP_Query && $query->get( 'food_directory' ) ) {
		$query->is_404 = false;
		return false;
	}
	return $preempt;
}
add_filter( 'pre_handle_404', 'food_directory_prevent_404', 10, 2 );

function food_directory_template( $template ) {
	$directory = get_query_var( 'food_directory' );
	if ( in_array( $directory, array( 'foods', 'topics' ), true ) ) {
		$directory_template = get_template_directory() . '/taxonomy-directory.php';
		return file_exists( $directory_template ) ? $directory_template : $template;
	}
	if ( 'latest' === $directory ) {
		$latest_template = get_template_directory() . '/latest-articles.php';
		return file_exists( $latest_template ) ? $latest_template : $template;
	}
	return $template;
}
add_filter( 'template_include', 'food_directory_template', 97 );

function food_directory_document_title( $title ) {
	$directory = get_query_var( 'food_directory' );
	if ( ! $directory ) {
		return $title;
	}
	$english = function_exists( 'food_is_english' ) && food_is_english();
	$labels  = array(
		'foods'  => $english ? 'Foods' : 'Alimentos',
		'topics' => $english ? 'Topics' : 'Temas',
		'latest' => $english ? 'Latest articles' : 'Últimos artículos',
	);
	return isset( $labels[ $directory ] ) ? $labels[ $directory ] . ' | Quinnoa' : $title;
}
add_filter( 'pre_get_document_title', 'food_directory_document_title', 20 );

function food_directory_canonical() {
	$directory = get_query_var( 'food_directory' );
	if ( ! in_array( $directory, array( 'foods', 'topics', 'latest' ), true ) ) {
		return;
	}
	$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	echo '<link rel="canonical" href="' . esc_url( food_directory_current_url( $directory, $language ) ) . '">' . "\n";
}
add_action( 'wp_head', 'food_directory_canonical', 3 );

/** SEO coverage for directory routes that are not handled by language-seo.php. */
function food_directory_extra_head() {
	$directory = get_query_var( 'food_directory' );
	if ( ! in_array( $directory, array( 'foods', 'latest' ), true ) ) {
		return;
	}
	$english   = function_exists( 'food_is_english' ) && food_is_english();
	$language  = $english ? 'en' : 'es';
	$canonical = food_directory_current_url( $directory, $language );
	$title     = wp_get_document_title();
	$descriptions = array(
		'foods' => $english
			? 'Explore Quinnoa by food group and find every article related to each family of foods.'
			: 'Explora Quinnoa por grupos de alimentos y encuentra todos los artículos relacionados con cada familia.',
		'latest' => $english
			? 'Browse all Quinnoa articles from newest to oldest.'
			: 'Consulta todos los artículos de Quinnoa ordenados del más reciente al más antiguo.',
	);
	$description = $descriptions[ $directory ];

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:site_name" content="Quinnoa">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
}
add_action( 'wp_head', 'food_directory_extra_head', 2 );

function food_directory_extra_hreflang() {
	$directory = get_query_var( 'food_directory' );
	if ( ! in_array( $directory, array( 'foods', 'latest' ), true ) ) {
		return;
	}
	$es = food_directory_current_url( $directory, 'es' );
	$en = food_directory_current_url( $directory, 'en' );
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( $es ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $en ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $es ) . '">' . "\n";
}
add_action( 'wp_head', 'food_directory_extra_hreflang', 4 );

function food_directory_extra_schema() {
	$directory = get_query_var( 'food_directory' );
	if ( ! in_array( $directory, array( 'foods', 'latest' ), true ) ) {
		return;
	}
	$english   = function_exists( 'food_is_english' ) && food_is_english();
	$language  = $english ? 'en-US' : 'es-ES';
	$canonical = food_directory_current_url( $directory, $english ? 'en' : 'es' );
	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'CollectionPage',
		'url'        => $canonical,
		'name'       => wp_get_document_title(),
		'inLanguage' => $language,
		'isPartOf'   => array(
			'@type' => 'WebSite',
			'name'  => 'Quinnoa',
			'url'   => home_url( '/' ),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'food_directory_extra_schema', 20 );

/** Replace the legacy home anchor emitted by the forced primary navigation. */
function food_rewrite_latest_navigation_link( $nav_menu, $args ) {
	if ( ! is_object( $args ) || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $nav_menu;
	}
	$latest_url = esc_url( food_directory_url( 'latest' ) );
	return preg_replace( '~href="[^"]*#ultimos-articulos"~', 'href="' . $latest_url . '"', $nav_menu );
}
add_filter( 'wp_nav_menu', 'food_rewrite_latest_navigation_link', 60, 2 );

function food_expected_taxonomy_url_with_page( $base_url ) {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	if ( $paged <= 1 ) {
		return trailingslashit( $base_url );
	}
	return trailingslashit( $base_url ) . 'page/' . $paged . '/';
}

function food_redirect_localized_taxonomy_canonical() {
	if ( is_admin() || wp_doing_ajax() || get_query_var( 'food_directory' ) ) {
		return;
	}

	$expected = '';
	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && food_is_editorial_food_category_slug( $term->slug ) ) {
			$expected = food_expected_taxonomy_url_with_page( get_category_link( $term ) );
		}
	} elseif ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$expected = food_expected_taxonomy_url_with_page( get_term_link( $term ) );
		}
	}

	if ( ! $expected || is_wp_error( $expected ) ) {
		return;
	}

	$current_path  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	$expected_path = (string) wp_parse_url( $expected, PHP_URL_PATH );
	if ( untrailingslashit( $current_path ) === untrailingslashit( $expected_path ) ) {
		return;
	}

	wp_safe_redirect( $expected, 301 );
	exit;
}
add_action( 'template_redirect', 'food_redirect_localized_taxonomy_canonical', 3 );
