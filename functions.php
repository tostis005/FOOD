<?php
/**
 * FOOD theme functions.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_theme_setup() {
	load_theme_textdomain( 'food', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );
	add_image_size( 'food-card', 760, 500, true );
	add_image_size( 'food-hero', 1400, 860, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'food' ),
			'footer'  => __( 'Menú del pie', 'food' ),
		)
	);
}
add_action( 'after_setup_theme', 'food_theme_setup' );

function food_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'food-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_script( 'food-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'food_enqueue_assets' );

function food_widgets_init() {
	$areas = array(
		'home-ad'    => 'Publicidad · portada',
		'article-ad' => 'Publicidad · artículo',
		'footer-1'   => 'Pie · columna 1',
		'footer-2'   => 'Pie · columna 2',
	);

	foreach ( $areas as $id => $name ) {
		register_sidebar(
			array(
				'name'          => __( $name, 'food' ),
				'id'            => $id,
				'before_widget' => '<div class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'food_widgets_init' );

function food_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'food_excerpt_length', 999 );

function food_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 210 ) );
	return sprintf( _n( '%s min de lectura', '%s min de lectura', $minutes, 'food' ), $minutes );
}

function food_category_fallback() {
	$items = array(
		'Carnes'                => 'carnes',
		'Jamón'                 => 'jamon',
		'Quesos'                => 'quesos',
		'Aceites'               => 'aceites',
		'Legumbres'             => 'legumbres',
		'Frutas y verduras'     => 'frutas-verduras',
		'Seguridad alimentaria' => 'seguridad-alimentaria',
	);

	echo '<ul class="menu food-fallback-menu">';
	foreach ( $items as $label => $slug ) {
		$term = get_category_by_slug( $slug );
		$url  = $term ? get_category_link( $term ) : home_url( '/?s=' . rawurlencode( $label ) );
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

function food_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="Migas de pan"><a href="' . esc_url( home_url( '/' ) ) . '">Inicio</a><span>›</span>';
	if ( is_single() ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			echo '<a href="' . esc_url( get_category_link( $categories[0] ) ) . '">' . esc_html( $categories[0]->name ) . '</a><span>›</span>';
		}
		echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() ) {
		echo '<span aria-current="page">' . esc_html( single_cat_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		echo '<span aria-current="page">Buscar</span>';
	}
	echo '</nav>';
}

function food_article_schema() {
	if ( ! is_single() || 'post' !== get_post_type() ) {
		return;
	}

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'datePublished'    => get_the_date( DATE_W3C ),
		'dateModified'     => get_the_modified_date( DATE_W3C ),
		'mainEntityOfPage' => get_permalink(),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( has_post_thumbnail() ) {
		$schema['image'] = array( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'food_article_schema', 20 );

function food_body_classes( $classes ) {
	$classes[] = 'food-theme';
	return $classes;
}
add_filter( 'body_class', 'food_body_classes' );
