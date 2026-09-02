<?php
/** English-facing document title localization for the native Quinnoa language layer. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Taxonomy URL localization is loaded here before any document output. The
 * first request after deployment also persists the new rewrite table; from
 * then on /alimentos/... and /en/foods/... resolve natively in WordPress.
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
}

function food_localize_english_document_title( $parts ) {
	if ( ! function_exists( 'food_is_english' ) || ! food_is_english() ) {
		return $parts;
	}

	if ( function_exists( 'food_is_english_home_request' ) && food_is_english_home_request() ) {
		$parts['title']   = 'Quinnoa';
		$parts['tagline'] = 'Food, nutrition, quality, safety, storage and cooking';
		return $parts;
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_family_display' ) ) {
			$parts['title'] = food_family_display( $term->slug );
		}
	} elseif ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && function_exists( 'food_topic_display' ) ) {
			$parts['title'] = food_topic_display( $term );
		}
	} elseif ( is_search() ) {
		$parts['title'] = sprintf( 'Search results for “%s”', get_search_query() );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'food_localize_english_document_title', 30 );