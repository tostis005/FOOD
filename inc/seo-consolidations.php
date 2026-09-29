<?php
/**
 * High-confidence SEO consolidations between duplicate article intents.
 *
 * The source article remains available in WordPress long enough for routing,
 * but public requests are permanently redirected to the canonical article in
 * the same language. The same map is also used by sitemaps and internal links.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_seo_consolidation_map_path() {
	$runtime = get_template_directory() . '/inc/data/seo-consolidations.json';
	if ( is_readable( $runtime ) ) {
		return $runtime;
	}

	$source = get_template_directory() . '/content/articles/SEO-CONSOLIDATIONS.json';
	return is_readable( $source ) ? $source : '';
}

function food_seo_consolidation_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$path = food_seo_consolidation_map_path();
	if ( '' === $path ) {
		$map = array();
		return $map;
	}

	$decoded = json_decode( (string) file_get_contents( $path ), true );
	$map     = array();

	if ( is_array( $decoded ) ) {
		foreach ( $decoded as $source => $target ) {
			$source = (int) $source;
			$target = (int) $target;
			if ( $source > 0 && $target > 0 && $source !== $target ) {
				$map[ $source ] = $target;
			}
		}
	}

	return $map;
}

function food_seo_consolidation_target_number( $article_number ) {
	$article_number = (int) $article_number;
	$map            = food_seo_consolidation_map();
	$seen           = array();

	while ( isset( $map[ $article_number ] ) && ! isset( $seen[ $article_number ] ) ) {
		$seen[ $article_number ] = true;
		$article_number          = (int) $map[ $article_number ];
	}

	return ! empty( $seen ) ? $article_number : 0;
}

function food_seo_consolidation_canonical_number( $article_number ) {
	$article_number = (int) $article_number;
	$target         = food_seo_consolidation_target_number( $article_number );
	return $target > 0 ? $target : $article_number;
}

function food_seo_consolidation_post( $article_number, $language ) {
	$article_number = (int) $article_number;
	$language       = 'en' === $language ? 'en' : 'es';
	$posts          = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'food_language_bypass'   => 1,
			'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'   => '_food_article_number',
					'value' => (string) $article_number,
				),
				array(
					'key'   => '_food_language',
					'value' => $language,
				),
			),
		)
	);

	return ! empty( $posts ) && $posts[0] instanceof WP_Post ? $posts[0] : null;
}

function food_seo_redirect_consolidated_article() {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}

	$post_id = get_queried_object_id();
	$number  = (int) get_post_meta( $post_id, '_food_article_number', true );
	$target  = food_seo_consolidation_target_number( $number );
	if ( $target < 1 ) {
		return;
	}

	$language = (string) get_post_meta( $post_id, '_food_language', true );
	$language = 'en' === $language ? 'en' : 'es';
	$canonical = food_seo_consolidation_post( $target, $language );
	if ( ! $canonical instanceof WP_Post ) {
		return;
	}

	$url = get_permalink( $canonical );
	if ( ! $url ) {
		return;
	}

	wp_safe_redirect( $url, 301, 'Quinnoa SEO consolidation' );
	exit;
}
add_action( 'template_redirect', 'food_seo_redirect_consolidated_article', 1 );
