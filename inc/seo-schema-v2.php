<?php
/**
 * Structured data helpers for Quinnoa SEO v2.
 *
 * @package FOOD
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_seo_v2_primary_topic( $post_id ) {
	$terms = wp_get_post_terms( (int) $post_id, 'food_topic' );
	return ! is_wp_error( $terms ) && ! empty( $terms ) ? $terms[0] : null;
}

function food_seo_v2_breadcrumb_items( $canonical ) {
	$english = food_seo_v2_is_english();
	$lang    = $english ? 'en' : 'es';
	$home    = function_exists( 'food_language_home_url' ) ? food_language_home_url( $lang ) : home_url( '/' );
	$items   = array(
		array( '@type' => 'ListItem', 'position' => 1, 'name' => $english ? 'Home' : 'Inicio', 'item' => $home ),
	);
	$pos = 2;

	$directory = food_seo_v2_directory();
	if ( in_array( $directory, array( 'foods', 'topics', 'latest' ), true ) ) {
		$labels = array(
			'foods'  => $english ? 'Foods' : 'Alimentos',
			'topics' => $english ? 'Topics' : 'Temas',
			'latest' => $english ? 'Latest articles' : 'Últimos artículos',
		);
		$items[] = array( '@type' => 'ListItem', 'position' => $pos, 'name' => $labels[ $directory ], 'item' => $canonical );
		return $items;
	}

	if ( is_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$foods_url = function_exists( 'food_directory_url' ) ? food_directory_url( 'foods', $lang ) : $home;
			$name      = $english && function_exists( 'food_family_display' ) ? food_family_display( $term->slug ) : $term->name;
			$items[]   = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $english ? 'Foods' : 'Alimentos', 'item' => $foods_url );
			$items[]   = array( '@type' => 'ListItem', 'position' => $pos, 'name' => $name, 'item' => $canonical );
		}
		return $items;
	}

	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$topics_url = function_exists( 'food_directory_url' ) ? food_directory_url( 'topics', $lang ) : $home;
			$name       = $english && function_exists( 'food_topic_display' ) ? food_topic_display( $term ) : $term->name;
			$items[]    = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $english ? 'Topics' : 'Temas', 'item' => $topics_url );
			$items[]    = array( '@type' => 'ListItem', 'position' => $pos, 'name' => $name, 'item' => $canonical );
		}
		return $items;
	}

	if ( is_singular( 'post' ) ) {
		$post_id  = get_queried_object_id();
		$category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $post_id ) : null;
		if ( $category instanceof WP_Term ) {
			$foods_url = function_exists( 'food_directory_url' ) ? food_directory_url( 'foods', $lang ) : $home;
			$cat_url   = function_exists( 'food_category_url_for_language' ) ? food_category_url_for_language( $category, $lang ) : get_category_link( $category );
			$cat_name  = $english && function_exists( 'food_family_display' ) ? food_family_display( $category->slug ) : $category->name;
			$items[]   = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $english ? 'Foods' : 'Alimentos', 'item' => $foods_url );
			$items[]   = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $cat_name, 'item' => $cat_url );
		} else {
			$topic = food_seo_v2_primary_topic( $post_id );
			if ( $topic instanceof WP_Term ) {
				$topics_url = function_exists( 'food_directory_url' ) ? food_directory_url( 'topics', $lang ) : $home;
				$topic_url  = function_exists( 'food_topic_url_for_language' ) ? food_topic_url_for_language( $topic, $lang ) : get_term_link( $topic );
				$topic_name = $english && function_exists( 'food_topic_display' ) ? food_topic_display( $topic ) : $topic->name;
				$items[]    = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $english ? 'Topics' : 'Temas', 'item' => $topics_url );
				$items[]    = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => $topic_name, 'item' => $topic_url );
			}
		}
		$items[] = array( '@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title( $post_id ), 'item' => $canonical );
		return $items;
	}

	$editorial = get_query_var( 'food_editorial_page' );
	if ( $editorial && function_exists( 'food_editorial_pages' ) ) {
		$pages = food_editorial_pages();
		if ( isset( $pages[ $editorial ][ $lang ]['title'] ) ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $pos, 'name' => $pages[ $editorial ][ $lang ]['title'], 'item' => $canonical );
		}
	}
	return $items;
}

function food_seo_v2_schema_graph( $canonical, $description ) {
	$home_url = home_url( '/' );
	$lang     = food_seo_v2_is_english() ? 'en-US' : 'es-ES';
	$org_id   = $home_url . '#organization';
	$site_id  = $home_url . '#website';
	$page_id  = $canonical . '#webpage';
	$graph    = array();

	$graph[] = array(
		'@type' => 'Organization',
		'@id'   => $org_id,
		'name'  => 'Quinnoa',
		'url'   => $home_url,
		'logo'  => array( '@type' => 'ImageObject', 'url' => get_template_directory_uri() . '/assets/quinnoa-grain.svg' ),
	);
	$graph[] = array(
		'@type'      => 'WebSite',
		'@id'        => $site_id,
		'url'        => $home_url,
		'name'       => 'Quinnoa',
		'inLanguage' => array( 'es-ES', 'en-US' ),
		'publisher'  => array( '@id' => $org_id ),
	);

	$page = array(
		'@type'      => food_seo_v2_directory() ? 'CollectionPage' : 'WebPage',
		'@id'        => $page_id,
		'url'        => $canonical,
		'name'       => wp_get_document_title(),
		'inLanguage' => $lang,
		'isPartOf'   => array( '@id' => $site_id ),
	);
	if ( $description ) {
		$page['description'] = $description;
	}
	$graph[] = $page;

	if ( is_singular( 'post' ) ) {
		$post_id = get_queried_object_id();
		$article = array(
			'@type'            => 'Article',
			'@id'              => $canonical . '#article',
			'headline'         => get_the_title( $post_id ),
			'description'      => $description,
			'datePublished'    => get_the_date( DATE_W3C, $post_id ),
			'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
			'mainEntityOfPage' => array( '@id' => $page_id ),
			'author'           => array( '@id' => $org_id ),
			'publisher'        => array( '@id' => $org_id ),
			'inLanguage'       => $lang,
		);
		if ( has_post_thumbnail( $post_id ) ) {
			$article['image'] = array( get_the_post_thumbnail_url( $post_id, 'full' ) );
		}
		$category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $post_id ) : null;
		if ( $category instanceof WP_Term ) {
			$article['articleSection'] = food_seo_v2_is_english() && function_exists( 'food_family_display' ) ? food_family_display( $category->slug ) : $category->name;
		}
		$graph[] = $article;
	}

	$breadcrumbs = food_seo_v2_breadcrumb_items( $canonical );
	if ( count( $breadcrumbs ) > 1 ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $canonical . '#breadcrumb',
			'itemListElement' => $breadcrumbs,
		);
	}
	return $graph;
}
