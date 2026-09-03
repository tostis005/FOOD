<?php
/**
 * Dynamic bilingual XML sitemaps for Quinnoa.
 *
 * /sitemap.xml    -> sitemap index
 * /sitemap-es.xml -> Spanish home, editorial pages and published posts
 * /sitemap-en.xml -> English home, editorial pages and published posts
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep the custom bilingual sitemap as the single sitemap system.
 */
function food_disable_core_sitemaps() {
	return false;
}
add_filter( 'wp_sitemaps_enabled', 'food_disable_core_sitemaps' );

/**
 * Escape a value for XML output.
 */
function food_sitemap_xml_escape( $value ) {
	return function_exists( 'esc_xml' )
		? esc_xml( (string) $value )
		: htmlspecialchars( (string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8' );
}

/**
 * Return all published blog posts for one language.
 */
function food_sitemap_posts( $language ) {
	$language = 'en' === $language ? 'en' : 'es';

	if ( function_exists( 'food_language_query_clause' ) ) {
		$language_clause = food_language_query_clause( $language );
	} elseif ( 'en' === $language ) {
		$language_clause = array(
			'key'     => '_food_language',
			'value'   => 'en',
			'compare' => '=',
		);
	} else {
		$language_clause = array(
			'relation' => 'OR',
			array(
				'key'     => '_food_language',
				'value'   => 'es',
				'compare' => '=',
			),
			array(
				'key'     => '_food_language',
				'compare' => 'NOT EXISTS',
			),
		);
	}

	return get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => array( $language_clause ),
			'food_language_bypass'   => 1,
		)
	);
}

/**
 * Return the static/public editorial URLs for one language.
 */
function food_sitemap_static_urls( $language ) {
	$language = 'en' === $language ? 'en' : 'es';
	$urls     = array();

	$urls[] = function_exists( 'food_language_home_url' )
		? food_language_home_url( $language )
		: ( 'en' === $language ? home_url( '/en/' ) : home_url( '/' ) );

	if ( function_exists( 'food_editorial_pages' ) && function_exists( 'food_editorial_page_url' ) ) {
		foreach ( array_keys( food_editorial_pages() ) as $key ) {
			$urls[] = food_editorial_page_url( $key, $language );
		}
	}

	return array_values( array_unique( array_filter( $urls ) ) );
}

/**
 * Output the sitemap index containing exactly the Spanish and English maps.
 */
function food_render_sitemap_index() {
	status_header( 200 );
	nocache_headers();
	header( 'Content-Type: application/xml; charset=UTF-8' );

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	foreach ( array( 'es', 'en' ) as $language ) {
		$location = home_url( '/sitemap-' . $language . '.xml' );
		echo "  <sitemap>\n";
		echo '    <loc>' . food_sitemap_xml_escape( $location ) . "</loc>\n";
		echo "  </sitemap>\n";
	}
	echo "</sitemapindex>\n";
	exit;
}

/**
 * Output one language-specific URL sitemap.
 */
function food_render_language_sitemap( $language ) {
	$language = 'en' === $language ? 'en' : 'es';
	$posts    = food_sitemap_posts( $language );
	$seen     = array();

	status_header( 200 );
	nocache_headers();
	header( 'Content-Type: application/xml; charset=UTF-8' );

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	foreach ( food_sitemap_static_urls( $language ) as $url ) {
		if ( isset( $seen[ $url ] ) ) {
			continue;
		}
		$seen[ $url ] = true;
		echo "  <url>\n";
		echo '    <loc>' . food_sitemap_xml_escape( $url ) . "</loc>\n";
		echo "  </url>\n";
	}

	foreach ( $posts as $post ) {
		$url = get_permalink( $post );
		if ( ! $url || isset( $seen[ $url ] ) ) {
			continue;
		}
		$seen[ $url ] = true;
		$lastmod      = get_post_modified_time( DATE_W3C, true, $post );

		echo "  <url>\n";
		echo '    <loc>' . food_sitemap_xml_escape( $url ) . "</loc>\n";
		if ( $lastmod ) {
			echo '    <lastmod>' . food_sitemap_xml_escape( $lastmod ) . "</lastmod>\n";
		}
		echo "  </url>\n";
	}

	echo "</urlset>\n";
	exit;
}

/**
 * Serve the XML endpoints directly from the request path so they do not rely
 * on permalink rewrite state or a physical sitemap file in the web root.
 */
function food_handle_sitemap_request( $wp ) {
	if ( ! $wp instanceof WP ) {
		return;
	}

	$request = trim( (string) $wp->request, '/' );
	if ( 'sitemap.xml' === $request ) {
		food_render_sitemap_index();
	}
	if ( 'sitemap-es.xml' === $request ) {
		food_render_language_sitemap( 'es' );
	}
	if ( 'sitemap-en.xml' === $request ) {
		food_render_language_sitemap( 'en' );
	}
}
add_action( 'parse_request', 'food_handle_sitemap_request', 1 );

/**
 * Advertise the sitemap index in WordPress' virtual robots.txt.
 */
function food_sitemap_robots_txt( $output ) {
	$sitemap = home_url( '/sitemap.xml' );
	if ( false === strpos( $output, $sitemap ) ) {
		$output = rtrim( $output ) . "\nSitemap: " . $sitemap . "\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'food_sitemap_robots_txt', 20 );
