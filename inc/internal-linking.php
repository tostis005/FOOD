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

/**
 * Resolve the internal-link map used at runtime.
 *
 * Production deploys only the generated runtime copy because the source
 * content directory is intentionally excluded from rsync. Local development
 * can still read the editorial source directly.
 */
function food_internal_link_map_path() {
	$runtime_path = get_template_directory() . '/inc/data/internal-link-map.json';
	if ( is_readable( $runtime_path ) ) {
		return $runtime_path;
	}

	$source_path = get_template_directory() . '/content/articles/INTERNAL-LINK-MAP.json';
	if ( is_readable( $source_path ) ) {
		return $source_path;
	}

	return '';
}

function food_internal_link_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$path = food_internal_link_map_path();
	if ( '' === $path ) {
		$map = array();
		return $map;
	}

	$raw = file_get_contents( $path );
	if ( false === $raw ) {
		$map = array();
		return $map;
	}

	$decoded = json_decode( (string) $raw, true );
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

	$numbers  = food_internal_link_target_numbers( $post_id );
	$language = food_internal_link_language( $post_id );
	$posts = empty( $numbers ) ? array() : get_posts(
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

	/*
	 * The hand-curated graph currently covers the original library. Newer
	 * articles must not become isolated while the editorial map catches up, so
	 * supplement missing links with deterministic taxonomy-based suggestions.
	 */
	if ( count( $ordered ) < 3 ) {
		$fallback = food_internal_link_fallback_posts( $post_id, 3 );
		$seen_ids = array_map(
			function( $post ) {
				return (int) $post->ID;
			},
			$ordered
		);

		foreach ( $fallback as $post ) {
			if ( in_array( (int) $post->ID, $seen_ids, true ) ) {
				continue;
			}
			$ordered[]  = $post;
			$seen_ids[] = (int) $post->ID;
			if ( count( $ordered ) >= 3 ) {
				break;
			}
		}
	}

	$cache[ $post_id ] = $ordered;
	return $cache[ $post_id ];
}

/**
 * Find stable contextual links for articles that do not yet have a manual map
 * entry. Candidates must share the food family or at least one editorial topic.
 */
function food_internal_link_fallback_posts( $post_id, $limit = 3 ) {
	$post_id = (int) $post_id;
	$limit   = max( 1, (int) $limit );

	$language = food_internal_link_language( $post_id );
	$category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $post_id ) : null;
	$topics   = function_exists( 'food_get_article_topics' ) ? food_get_article_topics( $post_id ) : array();

	$tax_query = array( 'relation' => 'OR' );
	if ( $category instanceof WP_Term ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( (int) $category->term_id ),
		);
	}
	foreach ( $topics as $topic ) {
		if ( ! $topic instanceof WP_Term ) {
			continue;
		}
		$tax_query[] = array(
			'taxonomy' => 'food_topic',
			'field'    => 'term_id',
			'terms'    => array( (int) $topic->term_id ),
		);
	}

	if ( count( $tax_query ) < 2 ) {
		return array();
	}

	$candidates = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 30,
			'post__not_in'           => array( $post_id ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'food_language_bypass'   => 1,
			'meta_query'             => array(
				array(
					'key'     => '_food_language',
					'value'   => $language,
					'compare' => '=',
				),
			),
			'tax_query'              => $tax_query,
		)
	);

	if ( empty( $candidates ) ) {
		return array();
	}

	$current_topic_slugs = array();
	foreach ( $topics as $topic ) {
		if ( $topic instanceof WP_Term ) {
			$current_topic_slugs[ $topic->slug ] = true;
		}
	}
	$current_category_slug = $category instanceof WP_Term ? $category->slug : '';

	$scored = array();
	foreach ( $candidates as $candidate ) {
		$score = 0;

		$candidate_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $candidate->ID ) : null;
		if ( $current_category_slug && $candidate_category instanceof WP_Term && $candidate_category->slug === $current_category_slug ) {
			$score += 4;
		}

		$candidate_topics = function_exists( 'food_get_article_topics' ) ? food_get_article_topics( $candidate->ID ) : array();
		$topic_overlap    = 0;
		foreach ( $candidate_topics as $candidate_topic ) {
			if ( $candidate_topic instanceof WP_Term && isset( $current_topic_slugs[ $candidate_topic->slug ] ) ) {
				$topic_overlap++;
			}
		}
		$score += 3 * $topic_overlap;

		if ( $score <= 0 ) {
			continue;
		}

		$scored[] = array(
			'post'   => $candidate,
			'score'  => $score,
			'number' => food_internal_link_article_number( $candidate->ID ),
		);
	}

	usort(
		$scored,
		function( $a, $b ) {
			if ( $a['score'] !== $b['score'] ) {
				return $b['score'] <=> $a['score'];
			}
			return $b['number'] <=> $a['number'];
		}
	);

	return array_map(
		function( $row ) {
			return $row['post'];
		},
		array_slice( $scored, 0, $limit )
	);
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
