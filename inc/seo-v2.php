<?php
/**
 * Centralized SEO overrides for Quinnoa.
 *
 * @package FOOD
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$food_seo_v2_schema = __DIR__ . '/seo-schema-v2.php';
if ( file_exists( $food_seo_v2_schema ) ) {
	require_once $food_seo_v2_schema;
}

function food_seo_v2_is_english() {
	return function_exists( 'food_is_english' ) && food_is_english();
}

function food_seo_v2_language() {
	return food_seo_v2_is_english() ? 'en' : 'es';
}

function food_seo_v2_locale() {
	return food_seo_v2_is_english() ? 'en_US' : 'es_ES';
}

function food_seo_v2_is_home() {
	return is_front_page() || ( function_exists( 'food_is_english_home_request' ) && food_is_english_home_request() );
}

function food_seo_v2_directory() {
	$directory = get_query_var( 'food_directory' );
	return is_string( $directory ) ? $directory : '';
}

function food_seo_v2_page_number() {
	return max( 1, (int) get_query_var( 'paged' ) );
}

function food_seo_v2_trim( $text, $length = 158 ) {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
	if ( '' === $text ) {
		return '';
	}
	$size = function_exists( 'mb_strlen' ) ? mb_strlen( $text ) : strlen( $text );
	return $size <= $length ? $text : wp_html_excerpt( $text, $length - 1, '…' );
}

function food_seo_v2_paginated_text( $text ) {
	$page = food_seo_v2_page_number();
	if ( $page > 1 ) {
		$text = rtrim( $text, '. ' ) . ( food_seo_v2_is_english() ? sprintf( '. Page %d.', $page ) : sprintf( '. Página %d.', $page ) );
	}
	return food_seo_v2_trim( $text );
}

function food_seo_v2_title( $title ) {
	if ( is_404() ) {
		return $title;
	}

	$english   = food_seo_v2_is_english();
	$directory = food_seo_v2_directory();
	$page      = food_seo_v2_page_number();
	$base      = '';

	if ( food_seo_v2_is_home() ) {
		return $english ? 'Quinnoa | Clear food information' : 'Quinnoa | Información clara sobre alimentos';
	}

	if ( in_array( $directory, array( 'foods', 'topics', 'latest' ), true ) ) {
		$labels = array(
			'foods'  => $english ? 'Foods by family' : 'Alimentos por familias',
			'topics' => $english ? 'Food topics' : 'Temas de alimentación',
			'latest' => $english ? 'Latest food articles' : 'Últimos artículos sobre alimentos',
		);
		$base = $labels[ $directory ];
	} elseif ( get_query_var( 'food_editorial_page' ) && function_exists( 'food_editorial_pages' ) ) {
		$key   = get_query_var( 'food_editorial_page' );
		$pages = food_editorial_pages();
		$lang  = $english ? 'en' : 'es';
		if ( isset( $pages[ $key ][ $lang ]['title'] ) ) {
			$base = $pages[ $key ][ $lang ]['title'];
		}
	} elseif ( is_singular( 'post' ) ) {
		$base = get_the_title( get_queried_object_id() );
	} elseif ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$name = $english && function_exists( 'food_family_display' ) ? food_family_display( $term->slug ) : $term->name;
			$base = $english ? sprintf( '%s articles', $name ) : sprintf( 'Artículos sobre %s', $name );
		}
	} elseif ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$base = $english && function_exists( 'food_topic_display' ) ? food_topic_display( $term ) : $term->name;
		}
	} elseif ( is_search() ) {
		$base = sprintf( $english ? 'Search results for “%s”' : 'Resultados para “%s”', get_search_query() );
	} elseif ( is_singular() ) {
		$base = get_the_title( get_queried_object_id() );
	}

	if ( '' === $base ) {
		return $title;
	}
	if ( $page > 1 && ( 'latest' === $directory || is_category() || is_tax( 'food_topic' ) ) ) {
		$base .= $english ? sprintf( ' – Page %d', $page ) : sprintf( ' – Página %d', $page );
	}
	return $base . ' | Quinnoa';
}
add_filter( 'pre_get_document_title', 'food_seo_v2_title', 100 );

function food_seo_v2_description() {
	$english   = food_seo_v2_is_english();
	$directory = food_seo_v2_directory();

	if ( food_seo_v2_is_home() ) {
		return $english
			? 'Clear, reliable information about food, with useful data and context on composition, characteristics, storage and everyday use.'
			: 'Información clara y rigurosa sobre alimentos, con datos y contexto sobre su composición, características, conservación y uso cotidiano.';
	}
	if ( 'foods' === $directory ) {
		return $english
			? 'Quinnoa articles organized by food family, with clear information about each group of foods.'
			: 'Artículos de Quinnoa organizados por familias de alimentos, con información clara sobre cada grupo.';
	}
	if ( 'topics' === $directory ) {
		return $english
			? 'Quinnoa articles organized by topic, covering food and nutrition from different areas and perspectives.'
			: 'Artículos de Quinnoa organizados por temas, con contenidos de alimentación abordados desde distintos ámbitos y enfoques.';
	}
	if ( 'latest' === $directory ) {
		return food_seo_v2_paginated_text( $english ? 'All Quinnoa articles together in one archive about food and nutrition.' : 'Todos los artículos de Quinnoa reunidos en un solo archivo sobre alimentos y alimentación.' );
	}

	$editorial = get_query_var( 'food_editorial_page' );
	if ( $editorial ) {
		$descriptions = array(
			'about' => array(
				'es' => 'Conoce Quinnoa, su enfoque editorial y cómo organizamos información clara y rigurosa sobre alimentos.',
				'en' => 'About Quinnoa, our editorial approach and how we organize clear, reliable information about food.',
			),
			'contact' => array(
				'es' => 'Página de contacto de Quinnoa para enviar consultas y mensajes al equipo.',
				'en' => 'Quinnoa contact page for sending questions and messages to the team.',
			),
			'privacy' => array(
				'es' => 'Información sobre privacidad, tratamiento de datos personales y cookies en Quinnoa.',
				'en' => 'Information about privacy, personal data processing and cookies on Quinnoa.',
			),
		);
		$lang = $english ? 'en' : 'es';
		return isset( $descriptions[ $editorial ][ $lang ] ) ? $descriptions[ $editorial ][ $lang ] : '';
	}

	if ( is_singular( 'post' ) ) {
		$excerpt = get_the_excerpt( get_queried_object_id() );
		return $excerpt ? food_seo_v2_trim( $excerpt ) : food_seo_v2_trim( get_post_field( 'post_content', get_queried_object_id() ) );
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$name        = $english && function_exists( 'food_family_display' ) ? food_family_display( $term->slug ) : $term->name;
			$description = term_description( $term );
			if ( $english ) {
				$short       = function_exists( 'food_family_display' ) ? food_family_display( $term->slug, 'short' ) : '';
				$description = sprintf( 'Quinnoa articles about %s. %s', $name, $short );
			} elseif ( ! $description ) {
				$description = sprintf( 'Artículos de Quinnoa sobre %s, con información clara y contextualizada sobre este grupo de alimentos.', $name );
			}
			return food_seo_v2_paginated_text( $description );
		}
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$name        = $english && function_exists( 'food_topic_display' ) ? food_topic_display( $term ) : $term->name;
			$description = term_description( $term );
			if ( $english ) {
				$description = sprintf( 'Quinnoa articles about %s, with clear explanations, useful data and practical context.', $name );
			} elseif ( ! $description ) {
				$description = sprintf( 'Artículos de Quinnoa sobre %s, con explicaciones claras, datos útiles y contexto práctico.', $name );
			}
			return food_seo_v2_paginated_text( $description );
		}
	}
	return '';
}

function food_seo_v2_add_page( $url ) {
	$page = food_seo_v2_page_number();
	return $page > 1 && $url ? trailingslashit( $url ) . 'page/' . $page . '/' : $url;
}

function food_seo_v2_canonical_url() {
	if ( is_404() || is_search() || is_tag() || is_author() || is_date() || is_attachment() ) {
		return '';
	}
	$lang      = food_seo_v2_language();
	$directory = food_seo_v2_directory();

	if ( food_seo_v2_is_home() ) {
		return function_exists( 'food_language_home_url' ) ? food_language_home_url( $lang ) : home_url( '/' );
	}
	if ( in_array( $directory, array( 'foods', 'topics', 'latest' ), true ) && function_exists( 'food_directory_url' ) ) {
		$url = food_directory_url( $directory, $lang );
		return 'latest' === $directory ? food_seo_v2_add_page( $url ) : $url;
	}
	$editorial = get_query_var( 'food_editorial_page' );
	if ( $editorial && function_exists( 'food_editorial_page_url' ) ) {
		return food_editorial_page_url( $editorial, $lang );
	}
	if ( is_singular() ) {
		return get_permalink( get_queried_object_id() );
	}
	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_category_url_for_language' ) ) {
			return food_seo_v2_add_page( food_category_url_for_language( $term, $lang ) );
		}
	}
	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_topic_url_for_language' ) ) {
			return food_seo_v2_add_page( food_topic_url_for_language( $term, $lang ) );
		}
	}
	return '';
}

function food_seo_v2_hreflang_urls() {
	$directory = food_seo_v2_directory();
	if ( in_array( $directory, array( 'foods', 'topics', 'latest' ), true ) && function_exists( 'food_directory_url' ) ) {
		$es = food_directory_url( $directory, 'es' );
		$en = food_directory_url( $directory, 'en' );
		if ( 'latest' === $directory ) {
			$es = food_seo_v2_add_page( $es );
			$en = food_seo_v2_add_page( $en );
		}
		return array( 'es' => $es, 'en' => $en );
	}
	return function_exists( 'food_seo_hreflang_urls' ) ? food_seo_hreflang_urls() : array();
}

function food_seo_v2_head() {
	/* Remove legacy and route-specific emitters before their later priorities. */
	remove_action( 'wp_head', 'food_seo_head_meta', 2 );
	remove_action( 'wp_head', 'food_directory_extra_head', 2 );
	remove_action( 'wp_head', 'food_directory_canonical', 3 );
	remove_action( 'wp_head', 'food_seo_hreflang', 4 );
	remove_action( 'wp_head', 'food_directory_extra_hreflang', 4 );
	remove_action( 'wp_head', 'food_editorial_page_head_links', 4 );
	remove_action( 'wp_head', 'food_seo_schema', 20 );
	remove_action( 'wp_head', 'food_directory_extra_schema', 20 );
	remove_action( 'wp_head', 'food_article_schema', 20 );

	$canonical   = food_seo_v2_canonical_url();
	$description = food_seo_v2_description();
	if ( ! $canonical ) {
		return;
	}
	$title = wp_get_document_title();

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";

	$pairs = food_seo_v2_hreflang_urls();
	if ( ! empty( $pairs['es'] ) && ! empty( $pairs['en'] ) ) {
		echo '<link rel="alternate" hreflang="es" href="' . esc_url( $pairs['es'] ) . '">' . "\n";
		echo '<link rel="alternate" hreflang="en" href="' . esc_url( $pairs['en'] ) . '">' . "\n";
		echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $pairs['es'] ) . '">' . "\n";
	}

	$type = is_singular( 'post' ) ? 'article' : 'website';
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:site_name" content="Quinnoa">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( food_seo_v2_locale() ) . '">' . "\n";
	echo '<meta property="og:locale:alternate" content="' . esc_attr( food_seo_v2_is_english() ? 'es_ES' : 'en_US' ) . '">' . "\n";

	$image = is_singular( 'post' ) && has_post_thumbnail( get_queried_object_id() ) ? get_the_post_thumbnail_url( get_queried_object_id(), 'full' ) : '';
	echo '<meta name="twitter:card" content="' . esc_attr( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( get_the_title( get_queried_object_id() ) ) . '">' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	if ( is_singular( 'post' ) ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( DATE_W3C, get_queried_object_id() ) ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( DATE_W3C, get_queried_object_id() ) ) . '">' . "\n";
	}

	if ( function_exists( 'food_seo_v2_schema_graph' ) ) {
		$graph = food_seo_v2_schema_graph( $canonical, $description );
		if ( $graph ) {
			echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_head', 'food_seo_v2_head', 0 );

function food_seo_v2_robots( $robots ) {
	if ( ! isset( $robots['noindex'] ) ) {
		$robots['max-image-preview'] = 'large';
		$robots['max-snippet'] = '-1';
		$robots['max-video-preview'] = '-1';
	}
	return $robots;
}
add_filter( 'wp_robots', 'food_seo_v2_robots', 100 );

function food_seo_v2_cleanup_taxonomy_copy() {
	if ( '1' === get_option( 'food_seo_v2_taxonomy_copy' ) ) {
		return;
	}
	$parent = get_category_by_slug( 'alimentos' );
	if ( $parent instanceof WP_Term ) {
		wp_update_term( $parent->term_id, 'category', array( 'description' => 'Artículos de Quinnoa organizados por familias de alimentos.' ) );
	}
	update_option( 'food_seo_v2_taxonomy_copy', '1' );
}
add_action( 'init', 'food_seo_v2_cleanup_taxonomy_copy', 40 );
