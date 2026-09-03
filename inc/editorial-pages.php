<?php
/**
 * Virtual bilingual editorial pages for Quinnoa.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Directory and localized taxonomy routing must be registered before init. */
$food_language_slugs_early = get_template_directory() . '/inc/language-slugs.php';
if ( file_exists( $food_language_slugs_early ) ) {
	require_once $food_language_slugs_early;
}

function food_editorial_pages() {
	return array(
		'about' => array(
			'es' => array(
				'slug'     => 'acerca-de',
				'title'    => 'Acerca de',
				'eyebrow'  => 'Quinnoa',
				'intro'    => '',
				'sections' => array(),
			),
			'en' => array(
				'slug'     => 'about',
				'title'    => 'About',
				'eyebrow'  => 'Quinnoa',
				'intro'    => '',
				'sections' => array(),
			),
		),
		'contact' => array(
			'es' => array(
				'slug'     => 'contacto',
				'title'    => 'Contacto',
				'eyebrow'  => 'Quinnoa',
				'intro'    => '',
				'sections' => array(),
			),
			'en' => array(
				'slug'     => 'contact',
				'title'    => 'Contact',
				'eyebrow'  => 'Quinnoa',
				'intro'    => '',
				'sections' => array(),
			),
		),
		'privacy' => array(
			'es' => array(
				'slug'     => 'privacidad',
				'title'    => 'Privacidad y cookies',
				'eyebrow'  => 'Información legal',
				'intro'    => '',
				'sections' => array(),
			),
			'en' => array(
				'slug'     => 'privacy',
				'title'    => 'Privacy & cookies',
				'eyebrow'  => 'Legal information',
				'intro'    => '',
				'sections' => array(),
			),
		),
	);
}

function food_editorial_page_url( $key, $language = '' ) {
	$pages = food_editorial_pages();
	$language = $language ?: ( function_exists( 'food_current_language' ) ? food_current_language() : 'es' );
	if ( ! isset( $pages[ $key ][ $language ] ) ) {
		return function_exists( 'food_language_home_url' ) ? food_language_home_url( $language ) : home_url( '/' );
	}
	$slug = $pages[ $key ][ $language ]['slug'];
	return 'en' === $language ? home_url( '/en/' . $slug . '/' ) : home_url( '/' . $slug . '/' );
}

function food_editorial_page_query_vars( $vars ) {
	$vars[] = 'food_editorial_page';
	return $vars;
}
add_filter( 'query_vars', 'food_editorial_page_query_vars' );

function food_register_editorial_page_rewrites() {
	foreach ( food_editorial_pages() as $key => $languages ) {
		add_rewrite_rule( '^' . preg_quote( $languages['es']['slug'], '#' ) . '/?$', 'index.php?food_editorial_page=' . $key . '&food_lang=es', 'top' );
		add_rewrite_rule( '^en/' . preg_quote( $languages['en']['slug'], '#' ) . '/?$', 'index.php?food_editorial_page=' . $key . '&food_lang=en', 'top' );
	}
	if ( '6' !== get_option( 'food_editorial_pages_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'food_editorial_pages_rewrite_version', '6' );
	}
}
add_action( 'init', 'food_register_editorial_page_rewrites', 99 );

/**
 * Resolve editorial routes independently of WordPress rewrite precedence.
 * This keeps /en/about/ et al. from ever being interpreted as article slugs.
 */
function food_resolve_editorial_page_request( $wp ) {
	if ( ! $wp instanceof WP ) {
		return;
	}

	$request = trim( (string) $wp->request, '/' );
	if ( '' === $request ) {
		return;
	}

	foreach ( food_editorial_pages() as $key => $languages ) {
		foreach ( array( 'es', 'en' ) as $language ) {
			$expected = 'en' === $language ? 'en/' . $languages[ $language ]['slug'] : $languages[ $language ]['slug'];
			if ( $request !== $expected ) {
				continue;
			}

			$wp->query_vars = array(
				'food_editorial_page' => $key,
				'food_lang'           => $language,
			);
			return;
		}
	}
}
add_action( 'parse_request', 'food_resolve_editorial_page_request', 1 );

function food_prepare_editorial_page_query( $query ) {
	if ( ! $query instanceof WP_Query || ! $query->is_main_query() || ! $query->get( 'food_editorial_page' ) ) {
		return;
	}
	$query->is_404 = false;
	$query->is_home = false;
}
add_action( 'parse_query', 'food_prepare_editorial_page_query', 1 );

function food_editorial_page_prevent_404( $preempt, $query ) {
	if ( $query instanceof WP_Query && $query->get( 'food_editorial_page' ) ) {
		$query->is_404 = false;
		return false;
	}
	return $preempt;
}
add_filter( 'pre_handle_404', 'food_editorial_page_prevent_404', 10, 2 );

function food_editorial_page_template( $template ) {
	if ( get_query_var( 'food_editorial_page' ) ) {
		$editorial_template = get_template_directory() . '/page-editorial.php';
		return file_exists( $editorial_template ) ? $editorial_template : $template;
	}
	return $template;
}
add_filter( 'template_include', 'food_editorial_page_template', 98 );

function food_editorial_page_document_title( $title ) {
	$key = get_query_var( 'food_editorial_page' );
	if ( ! $key ) {
		return $title;
	}
	$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	$pages = food_editorial_pages();
	return isset( $pages[ $key ][ $language ] ) ? $pages[ $key ][ $language ]['title'] . ' | Quinnoa' : $title;
}
add_filter( 'pre_get_document_title', 'food_editorial_page_document_title', 20 );

function food_editorial_page_head_links() {
	$key = get_query_var( 'food_editorial_page' );
	if ( ! $key || ! isset( food_editorial_pages()[ $key ] ) ) {
		return;
	}
	$current = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	echo '<link rel="canonical" href="' . esc_url( food_editorial_page_url( $key, $current ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( food_editorial_page_url( $key, 'es' ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( food_editorial_page_url( $key, 'en' ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( food_editorial_page_url( $key, 'es' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'food_editorial_page_head_links', 4 );

function food_handle_contact_form() {
	if ( 'contact' !== get_query_var( 'food_editorial_page' ) || 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	$current_url = food_editorial_page_url( 'contact', $language );
	if ( ! isset( $_POST['food_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['food_contact_nonce'] ) ), 'food_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $current_url ) );
		exit;
	}
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $current_url ) );
		exit;
	}
	$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( '' === $name || ! is_email( $email ) || strlen( $message ) < 10 ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $current_url ) );
		exit;
	}
	$subject = 'en' === $language ? 'Quinnoa contact form' : 'Formulario de contacto Quinnoa';
	$body = "Name: {$name}\nEmail: {$email}\nLanguage: {$language}\n\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent = wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );
	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', $current_url ) );
	exit;
}
