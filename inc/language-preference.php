<?php
/**
 * Language preference handling for Quinnoa.
 *
 * A persistent cookie is written only after the visitor explicitly chooses
 * a language in the interface. The public URLs themselves remain stable:
 * `/` is always the Spanish home and `/en/` is always the English home unless
 * a returning visitor has an explicit saved preference.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_language_preference_cookie_name() {
	return 'quinnoa_language';
}

function food_language_preference() {
	$cookie_name = food_language_preference_cookie_name();
	if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
		return '';
	}

	$language = sanitize_key( wp_unslash( $_COOKIE[ $cookie_name ] ) );
	return in_array( $language, array( 'es', 'en' ), true ) ? $language : '';
}

/**
 * Redirect home requests only when the visitor has explicitly saved a
 * language preference. Browser language and apparent geographic location are
 * deliberately ignored so crawlers and first-time visitors always receive the
 * URL they requested.
 */
function food_redirect_home_for_language_preference() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_feed() ) {
		return;
	}

	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
	if ( ! in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
		return;
	}

	$is_spanish_home = is_front_page();
	$is_english_home = function_exists( 'food_is_english_home_request' ) && food_is_english_home_request();
	if ( ! $is_spanish_home && ! $is_english_home ) {
		return;
	}

	$preferred = food_language_preference();
	if ( ! $preferred ) {
		return;
	}

	$current = function_exists( 'food_current_language' ) ? food_current_language() : ( $is_english_home ? 'en' : 'es' );
	if ( $preferred === $current ) {
		return;
	}

	$target_url = function_exists( 'food_language_home_url' )
		? food_language_home_url( $preferred )
		: ( 'en' === $preferred ? home_url( '/en/' ) : home_url( '/' ) );

	nocache_headers();
	wp_safe_redirect( $target_url, 302, 'Quinnoa language preference' );
	exit;
}
add_action( 'template_redirect', 'food_redirect_home_for_language_preference', 1 );

/** Add a focused description for the dedicated cookie-policy page. */
function food_cookie_policy_meta_description() {
	if ( 'cookies' !== get_query_var( 'food_editorial_page' ) ) {
		return;
	}

	$english = function_exists( 'food_is_english' ) && food_is_english();
	$text    = $english
		? 'Information about the functional language-preference cookie used by Quinnoa, its duration and how to manage it.'
		: 'Información sobre la cookie funcional de preferencia de idioma de Quinnoa, su duración y cómo gestionarla.';

	echo '<meta name="description" content="' . esc_attr( $text ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $text ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $text ) . '">' . "\n";
}
add_action( 'wp_head', 'food_cookie_policy_meta_description', 1 );
