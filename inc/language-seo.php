<?php
/**
 * Front-end SEO metadata for Quinnoa's native bilingual layer.
 *
 * Keeps titles, descriptions, canonicals, hreflang, social metadata and
 * structured data consistent without requiring a third-party SEO plugin.
 *
 * @package FOOD
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Taxonomy URL localization must be available before canonical and hreflang
 * tags are generated.
 */
$food_language_slugs = get_template_directory() . '/inc/language-slugs.php';
if ( file_exists( $food_language_slugs ) ) {
	require_once $food_language_slugs;
	if ( function_exists( 'food_register_fully_localized_taxonomy_rewrites' ) ) {
		food_register_fully_localized_taxonomy_rewrites();
	}
	if ( function_exists( 'food_redirect_localized_taxonomy_canonical' ) ) {
		food_redirect_localized_taxonomy_canonical();
	}
	if ( function_exists( 'food_directory_canonical' ) ) {
		remove_action( 'wp_head', 'food_directory_canonical', 3 );
	}
}

function food_seo_is_english() {
	return function_exists( 'food_is_english' ) && food_is_english();
}

function food_seo_language_code() {
	return food_seo_is_english() ? 'en' : 'es';
}

function food_seo_locale() {
	return food_seo_is_english() ? 'en_US' : 'es_ES';
}

function food_seo_is_home() {
	return is_front_page() || ( function_exists( 'food_is_english_home_request' ) && food_is_english_home_request() );
}

function food_seo_editorial_key() {
	$key = get_query_var( 'food_editorial_page' );
	return is_string( $key ) ? $key : '';
}

function food_seo_directory_key() {
	$key = get_query_var( 'food_directory' );
	return is_string( $key ) ? $key : '';
}

function food_seo_trim_description( $text, $length = 160 ) {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
	if ( '' === $text ) {
		return '';
	}
	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) <= $length ) {
		return $text;
	}
	if ( ! function_exists( 'mb_strlen' ) && strlen( $text ) <= $length ) {
		return $text;
	}
	return wp_html_excerpt( $text, $length - 1, '…' );
}

function food_seo_editorial_description( $key, $language ) {
	$descriptions = array(
		'about' => array(
			'es' => 'Conoce Quinnoa, un espacio digital para entender mejor los alimentos y las preguntas que los rodean.',
			'en' => 'About Quinnoa, a digital space for understanding food better and exploring the questions around it.',
		),
		'contact' => array(
			'es' => 'Página de contacto de Quinnoa. Puedes enviarnos un mensaje a través del formulario.',
			'en' => 'Quinnoa contact page. You can send us a message using the contact form.',
		),
		'privacy' => array(
			'es' => 'Información sobre privacidad, datos personales y cookies en Quinnoa.',
			'en' => 'Information about privacy, personal data and cookies on Quinnoa.',
		),
	);
	return isset( $descriptions[ $key ][ $language ] ) ? $descriptions[ $key ][ $language ] : '';
}

function food_seo_description() {
	$english  = food_seo_is_english();
	$language = $english ? 'en' : 'es';

	if ( food_seo_is_home() ) {
		return $english
			? 'Clear articles to understand food better, with useful data, practical context and answers to everyday questions.'
			: 'Artículos claros para entender mejor los alimentos, con datos útiles, contexto práctico y respuestas a dudas cotidianas.';
	}

	if ( 'topics' === food_seo_directory_key() ) {
		return $english
			? 'Explore Quinnoa articles by topic and choose the kind of food question you want to understand better.'
			: 'Explora los artículos de Quinnoa por tema y elige el tipo de pregunta sobre alimentación en el que quieres profundizar.';
	}

	$editorial_key = food_seo_editorial_key();
	if ( $editorial_key ) {
		return food_seo_editorial_description( $editorial_key, $language );
	}

	if ( is_singular( 'post' ) ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			return food_seo_trim_description( $excerpt );
		}
		return food_seo_trim_description( get_post_field( 'post_content', get_queried_object_id() ) );
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			if ( $english ) {
				$name  = function_exists( 'food_family_display' ) ? food_family_display( $term->slug ) : $term->name;
				$short = function_exists( 'food_family_display' ) ? food_family_display( $term->slug, 'short' ) : '';
				return food_seo_trim_description( sprintf( 'Explore Quinnoa articles about %s. %s', $name, $short ) );
			}
			$description = term_description( $term );
			if ( $description ) {
				return food_seo_trim_description( $description );
			}
			if ( 'alimentos' === $term->slug ) {
				return 'Explora Quinnoa por grupos de alimentos y accede a todos los artículos relacionados con cada uno.';
			}
			return food_seo_trim_description( sprintf( 'Artículos de Quinnoa sobre %s, explicados desde distintos enfoques y preguntas.', $term->name ) );
		}
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			if ( $english ) {
				$name = function_exists( 'food_topic_display' ) ? food_topic_display( $term ) : $term->name;
				return food_seo_trim_description( sprintf( 'Quinnoa articles about %s, with clear explanations, useful data and practical context.', $name ) );
			}
			$description = term_description( $term );
			if ( $description ) {
				return food_seo_trim_description( $description );
			}
		}
	}

	if ( is_search() ) {
		return $english ? 'Search results on Quinnoa.' : 'Resultados de búsqueda en Quinnoa.';
	}

	return '';
}

function food_seo_add_page_number( $url ) {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	if ( $paged <= 1 || ! $url ) {
		return $url;
	}
	return trailingslashit( $url ) . 'page/' . $paged . '/';
}

function food_seo_canonical_url() {
	if ( is_404() || is_search() || is_tag() || is_author() || is_date() || is_attachment() ) {
		return '';
	}

	$language = food_seo_language_code();
	if ( food_seo_is_home() ) {
		$url = function_exists( 'food_language_home_url' ) ? food_language_home_url( $language ) : home_url( '/' );
		return food_seo_add_page_number( $url );
	}

	if ( 'topics' === food_seo_directory_key() && function_exists( 'food_directory_url' ) ) {
		return food_directory_url( 'topics', $language );
	}

	$editorial_key = food_seo_editorial_key();
	if ( $editorial_key && function_exists( 'food_editorial_page_url' ) ) {
		return food_editorial_page_url( $editorial_key, $language );
	}

	if ( is_singular( 'post' ) ) {
		return get_permalink( get_queried_object_id() );
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_category_url_for_language' ) ) {
			return food_seo_add_page_number( food_category_url_for_language( $term, $language ) );
		}
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_topic_url_for_language' ) ) {
			return food_seo_add_page_number( food_topic_url_for_language( $term, $language ) );
		}
	}

	return '';
}

function food_seo_document_title( $parts ) {
	$english        = food_seo_is_english();
	$editorial_key  = food_seo_editorial_key();
	$editorial_data = $editorial_key && function_exists( 'food_editorial_pages' ) ? food_editorial_pages() : array();

	if ( food_seo_is_home() ) {
		$parts['title']   = 'Quinnoa';
		$parts['tagline'] = $english ? 'Understand food better' : 'Entender mejor los alimentos';
		return $parts;
	}

	if ( 'topics' === food_seo_directory_key() ) {
		$parts['title'] = $english ? 'Topics' : 'Temas';
		$parts['site']  = 'Quinnoa';
		unset( $parts['tagline'] );
		return $parts;
	}

	if ( $editorial_key && isset( $editorial_data[ $editorial_key ][ $english ? 'en' : 'es' ]['title'] ) ) {
		$parts['title'] = $editorial_data[ $editorial_key ][ $english ? 'en' : 'es' ]['title'];
		$parts['site']  = 'Quinnoa';
		unset( $parts['tagline'] );
		return $parts;
	}

	if ( is_singular( 'post' ) ) {
		$parts['title'] = get_the_title( get_queried_object_id() );
		$parts['site']  = 'Quinnoa';
		unset( $parts['tagline'] );
		return $parts;
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$parts['title'] = $english && function_exists( 'food_family_display' ) ? food_family_display( $term->slug ) : $term->name;
			$parts['site']  = 'Quinnoa';
		}
		return $parts;
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$parts['title'] = $english && function_exists( 'food_topic_display' ) ? food_topic_display( $term ) : $term->name;
			$parts['site']  = 'Quinnoa';
		}
		return $parts;
	}

	if ( is_search() ) {
		$parts['title'] = sprintf( $english ? 'Search results for “%s”' : 'Resultados para “%s”', get_search_query() );
		$parts['site']  = 'Quinnoa';
	}

	return $parts;
}
add_filter( 'document_title_parts', 'food_seo_document_title', 40 );
add_filter( 'document_title_separator', function () { return '|'; } );

function food_seo_robots( $robots ) {
	if ( is_search() || is_404() || is_author() || is_date() || is_attachment() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	if ( ! isset( $robots['noindex'] ) ) {
		$robots['max-image-preview'] = 'large';
	}
	return $robots;
}
add_filter( 'wp_robots', 'food_seo_robots', 30 );

/* Replace WordPress' singular-only canonical with one canonical strategy. */
remove_action( 'wp_head', 'rel_canonical' );

function food_seo_head_meta() {
	$description = food_seo_description();
	$canonical   = food_seo_canonical_url();
	$title       = wp_get_document_title();
	$english     = food_seo_is_english();

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $canonical ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}

	if ( ! $canonical || is_404() ) {
		return;
	}

	$type = is_singular( 'post' ) ? 'article' : 'website';
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:site_name" content="Quinnoa">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( food_seo_locale() ) . '">' . "\n";
	echo '<meta property="og:locale:alternate" content="' . esc_attr( $english ? 'es_ES' : 'en_US' ) . '">' . "\n";

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
}
add_action( 'wp_head', 'food_seo_head_meta', 2 );

/* The old hreflang helper falls back to the other language home when a post has
 * no translation. That is useful for the UI switcher, but incorrect as an SEO
 * equivalence signal, so SEO hreflang is generated independently here. */
remove_action( 'wp_head', 'food_language_hreflang', 4 );

function food_seo_hreflang_urls() {
	$pairs = array();

	if ( food_seo_is_home() ) {
		$pairs['es'] = food_seo_add_page_number( function_exists( 'food_language_home_url' ) ? food_language_home_url( 'es' ) : home_url( '/' ) );
		$pairs['en'] = food_seo_add_page_number( function_exists( 'food_language_home_url' ) ? food_language_home_url( 'en' ) : home_url( '/en/' ) );
		return $pairs;
	}

	if ( 'topics' === food_seo_directory_key() && function_exists( 'food_directory_url' ) ) {
		$pairs['es'] = food_directory_url( 'topics', 'es' );
		$pairs['en'] = food_directory_url( 'topics', 'en' );
		return $pairs;
	}

	$editorial_key = food_seo_editorial_key();
	if ( $editorial_key && function_exists( 'food_editorial_page_url' ) ) {
		$pairs['es'] = food_editorial_page_url( $editorial_key, 'es' );
		$pairs['en'] = food_editorial_page_url( $editorial_key, 'en' );
		return $pairs;
	}

	if ( is_singular( 'post' ) && function_exists( 'food_translation_post' ) ) {
		$current_id       = get_queried_object_id();
		$current_language = function_exists( 'food_post_language' ) ? food_post_language( $current_id ) : food_seo_language_code();
		$other_language   = 'en' === $current_language ? 'es' : 'en';
		$translation      = food_translation_post( $current_id, $other_language );
		if ( $translation instanceof WP_Post ) {
			$pairs[ $current_language ] = get_permalink( $current_id );
			$pairs[ $other_language ]   = get_permalink( $translation );
		}
		return $pairs;
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_category_url_for_language' ) ) {
			$pairs['es'] = food_seo_add_page_number( food_category_url_for_language( $term, 'es' ) );
			$pairs['en'] = food_seo_add_page_number( food_category_url_for_language( $term, 'en' ) );
		}
		return $pairs;
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_topic_url_for_language' ) ) {
			$pairs['es'] = food_seo_add_page_number( food_topic_url_for_language( $term, 'es' ) );
			$pairs['en'] = food_seo_add_page_number( food_topic_url_for_language( $term, 'en' ) );
		}
	}

	return $pairs;
}

function food_seo_hreflang() {
	$pairs = food_seo_hreflang_urls();
	if ( empty( $pairs['es'] ) || empty( $pairs['en'] ) ) {
		return;
	}
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( $pairs['es'] ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $pairs['en'] ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $pairs['es'] ) . '">' . "\n";
}
add_action( 'wp_head', 'food_seo_hreflang', 4 );

/* Replace the basic Article block with a single @graph that also identifies
 * the site and page, improving entity consistency across the site. */
remove_action( 'wp_head', 'food_article_schema', 20 );

function food_seo_schema() {
	$canonical = food_seo_canonical_url();
	if ( ! $canonical || is_404() || is_search() ) {
		return;
	}

	$home_url    = home_url( '/' );
	$description = food_seo_description();
	$language    = food_seo_is_english() ? 'en-US' : 'es-ES';
	$graph       = array();

	$graph[] = array(
		'@type' => 'Organization',
		'@id'   => $home_url . '#organization',
		'name'  => 'Quinnoa',
		'url'   => $home_url,
	);
	$graph[] = array(
		'@type'       => 'WebSite',
		'@id'         => $home_url . '#website',
		'url'         => $home_url,
		'name'        => 'Quinnoa',
		'inLanguage'  => array( 'es-ES', 'en-US' ),
		'publisher'   => array( '@id' => $home_url . '#organization' ),
	);
	$graph[] = array(
		'@type'       => 'WebPage',
		'@id'         => $canonical . '#webpage',
		'url'         => $canonical,
		'name'        => wp_get_document_title(),
		'description' => $description,
		'inLanguage'  => $language,
		'isPartOf'    => array( '@id' => $home_url . '#website' ),
	);

	if ( is_singular( 'post' ) ) {
		$post_id  = get_queried_object_id();
		$article  = array(
			'@type'            => 'Article',
			'@id'              => $canonical . '#article',
			'headline'         => get_the_title( $post_id ),
			'description'      => $description,
			'datePublished'    => get_the_date( DATE_W3C, $post_id ),
			'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
			'mainEntityOfPage' => array( '@id' => $canonical . '#webpage' ),
			'author'           => array( '@id' => $home_url . '#organization' ),
			'publisher'        => array( '@id' => $home_url . '#organization' ),
			'inLanguage'       => $language,
		);
		$category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $post_id ) : null;
		if ( $category instanceof WP_Term ) {
			$article['articleSection'] = function_exists( 'food_family_display' ) ? food_family_display( $category->slug ) : $category->name;
		}
		if ( has_post_thumbnail( $post_id ) ) {
			$article['image'] = array( get_the_post_thumbnail_url( $post_id, 'full' ) );
		}
		$graph[] = $article;
	}

	$breadcrumbs = array();
	if ( ! food_seo_is_home() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => food_seo_is_english() ? 'Home' : 'Inicio',
			'item'     => function_exists( 'food_language_home_url' ) ? food_language_home_url( food_seo_language_code() ) : $home_url,
		);
		$position = 2;
		if ( is_singular( 'post' ) ) {
			$category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
			if ( $category instanceof WP_Term ) {
				$category_url = function_exists( 'food_category_url_for_language' ) ? food_category_url_for_language( $category, food_seo_language_code() ) : get_category_link( $category );
				$breadcrumbs[] = array(
					'@type'    => 'ListItem',
					'position' => $position++,
					'name'     => function_exists( 'food_family_display' ) ? food_family_display( $category->slug ) : $category->name,
					'item'     => $category_url,
				);
			}
		}
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => is_singular( 'post' ) ? get_the_title( get_queried_object_id() ) : wp_get_document_title(),
			'item'     => $canonical,
		);
	}
	if ( count( $breadcrumbs ) > 1 ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $canonical . '#breadcrumb',
			'itemListElement' => $breadcrumbs,
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'food_seo_schema', 20 );