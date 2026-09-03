<?php
/**
 * Article-specific SEO overrides backed by the editorial JSON metadata.
 *
 * Imported articles already store their curated SEO title and meta description
 * in post meta. The native SEO layer historically fell back to the visible H1
 * and excerpt, which meant those curated fields were ignored unless a third-
 * party SEO plugin happened to read them. Keep the H1 and quick answer intact
 * while using the dedicated fields in the document head.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_article_seo_document_title( $title ) {
	if ( ! is_singular( 'post' ) ) {
		return $title;
	}

	$post_id   = get_queried_object_id();
	$seo_title = trim( (string) get_post_meta( $post_id, '_food_seo_title', true ) );
	if ( '' === $seo_title ) {
		return $title;
	}

	return $seo_title . ' | Quinnoa';
}
add_filter( 'pre_get_document_title', 'food_article_seo_document_title', 200 );

/**
 * Temporarily substitute the curated meta description while SEO v2 builds the
 * head. The filter is removed immediately afterwards so the visible Quick
 * answer continues to use the article excerpt.
 */
function food_article_seo_meta_excerpt( $excerpt, $post = null ) {
	if ( ! is_singular( 'post' ) ) {
		return $excerpt;
	}

	$post_id = $post instanceof WP_Post ? $post->ID : get_queried_object_id();
	if ( (int) $post_id !== (int) get_queried_object_id() ) {
		return $excerpt;
	}

	$description = trim( (string) get_post_meta( $post_id, '_food_meta_description', true ) );
	return '' !== $description ? $description : $excerpt;
}

function food_article_seo_prepare_meta_description() {
	if ( is_singular( 'post' ) ) {
		add_filter( 'get_the_excerpt', 'food_article_seo_meta_excerpt', 200, 2 );
	}
}
add_action( 'wp_head', 'food_article_seo_prepare_meta_description', -10 );

function food_article_seo_restore_excerpt() {
	remove_filter( 'get_the_excerpt', 'food_article_seo_meta_excerpt', 200 );
}
add_action( 'wp_head', 'food_article_seo_restore_excerpt', 1 );
