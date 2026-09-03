<?php
/**
 * Curated internal article linking for Quinnoa.
 *
 * The editorial map uses stable article numbers rather than slugs. WordPress
 * resolves those numbers to the matching published post in the current
 * language, so ES never links to EN and future slug edits do not break the
 * cluster graph.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_internal_link_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$path = get_template_directory() . '/content/articles/INTERNAL-LINK-MAP.json';
	if ( ! file_exists( $path ) ) {
		$map = array();
		return $map;
	}

	$decoded = json_decode( (string) file_get_contents( $path ), true );
	$map     = is_array( $decoded ) ? $decoded : array();
	return $map;
}

function food_internal_link_article_number( $post_id ) {
	return (int) get_post_meta( (int) $post_id, '_food_article_number', true );
}

function food_internal_link_language( $post_id ) {
	$language = (string) get_post_meta( (int) $post_id, '_food_language', true );
	if ( in_array( $language, array( 'es', 'en' ), true ) ) {
		return $language;
	}
	return function_exists( 'food_is_english' ) && food_is_english() ? 'en' : 'es';
}

function food_internal_link_target_numbers( $post_id ) {
	$number = food_internal_link_article_number( $post_id );
	if ( $number < 1 ) {
		return array();
	}

	$map = food_internal_link_map();
	$key = (string) $number;
	if ( empty( $map[ $key ] ) || ! is_array( $map[ $key ] ) ) {
		return array();
	}

	$targets = array_values(
		array_unique(
			array_filter(
				array_map( 'intval', $map[ $key ] ),
				function( $target ) use ( $number ) {
					return $target > 0 && $target !== $number;
				}
			)
		)
	);
	return array_slice( $targets, 0, 5 );
}

function food_internal_link_posts( $post_id ) {
	static $cache = array();
	$post_id = (int) $post_id;
	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}

	$numbers = food_internal_link_target_numbers( $post_id );
	if ( empty( $numbers ) ) {
		$cache[ $post_id ] = array();
		return $cache[ $post_id ];
	}

	$language = food_internal_link_language( $post_id );
	$posts    = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => count( $numbers ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'food_language_bypass'   => 1,
			'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'     => '_food_article_number',
					'value'   => array_map( 'strval', $numbers ),
					'compare' => 'IN',
				),
				array(
					'key'     => '_food_language',
					'value'   => $language,
					'compare' => '=',
				),
			),
		)
	);

	$by_number = array();
	foreach ( $posts as $post ) {
		$target_number = food_internal_link_article_number( $post->ID );
		if ( $target_number > 0 ) {
			$by_number[ $target_number ] = $post;
		}
	}

	$ordered = array();
	foreach ( $numbers as $number ) {
		if ( isset( $by_number[ $number ] ) ) {
			$ordered[] = $by_number[ $number ];
		}
	}

	$cache[ $post_id ] = $ordered;
	return $cache[ $post_id ];
}

function food_internal_link_target_post_ids( $post_id ) {
	return array_map(
		function( $post ) {
			return (int) $post->ID;
		},
		food_internal_link_posts( $post_id )
	);
}

function food_internal_links_html( $post_id ) {
	$posts = food_internal_link_posts( $post_id );
	if ( empty( $posts ) ) {
		return '';
	}

	$english = 'en' === food_internal_link_language( $post_id );
	$label   = $english ? 'In context' : 'En contexto';
	$aria    = $english ? 'Related reading in this topic' : 'Lecturas relacionadas con este tema';

	$html  = '<aside class="article-context-links" aria-label="' . esc_attr( $aria ) . '">';
	$html .= '<strong class="article-context-links-title">' . esc_html( $label ) . '</strong>';
	$html .= '<ul>';
	foreach ( $posts as $post ) {
		$html .= '<li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( get_the_title( $post ) ) . '</a></li>';
	}
	$html .= '</ul></aside>';
	return $html;
}

/**
 * Put the cluster links before the source list when the importer has appended
 * one. This keeps citations as the final editorial element and makes the links
 * part of the article reading flow rather than duplicating the related-card
 * section below the article.
 */
function food_internal_links_inject( $content, $post_id ) {
	$links = food_internal_links_html( $post_id );
	if ( '' === $links ) {
		return $content;
	}

	$pattern = '#(<h2>\s*(?:Fuentes|Sources)\s*</h2>\s*<ul[^>]*class=["\'][^"\']*food-article-sources[^"\']*["\'][^>]*>)#iu';
	if ( preg_match( $pattern, $content ) ) {
		return preg_replace( $pattern, $links . '$1', $content, 1 );
	}

	return $content . $links;
}
