<?php
/** English-facing document title localization for the native Pometum language layer. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_localize_english_document_title( $parts ) {
	if ( ! function_exists( 'food_is_english' ) || ! food_is_english() ) {
		return $parts;
	}

	if ( function_exists( 'food_is_english_home_request' ) && food_is_english_home_request() ) {
		$parts['title']   = 'Pometum';
		$parts['tagline'] = 'Food, quality, nutrition and cooking guides';
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
