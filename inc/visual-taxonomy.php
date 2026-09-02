<?php
/**
 * Visual identities for the Pommelo editorial taxonomies.
 *
 * These SVGs are lightweight interface artwork, not article photography. A
 * post can belong to a food family, an article type, both or neither. When no
 * featured image exists, the food family always has visual priority; the
 * article type is used only when no food family is assigned.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_category_icon_svg( $slug ) {
	$paths = array(
		'alimentacion-general' => '<circle cx="32" cy="32" r="18"/><circle cx="32" cy="32" r="11"/><path d="M9 13v38M6 13v10c0 4 6 4 6 0V13M55 13v38M50 13c7 5 8 13 5 20"/>',
		'carnes' => '<path d="M14 39c-4-11 4-23 17-27 12-4 24 1 28 10 5 11-4 24-18 29-13 5-23-1-27-12Z"/><path d="M37 20c5-3 12-1 14 4 2 5-2 10-8 12-6 2-11-1-12-5-2-4 1-9 6-11Z"/><circle cx="42" cy="28" r="3.5"/>',
		'pescados-mariscos' => '<path d="M13 32c10-12 23-17 36-11 6 3 10 7 12 11-2 5-6 9-12 12-13 6-26 1-36-12Z"/><path d="m13 32-9-9v18l9-9Z"/><circle cx="49" cy="28" r="1.8"/><path d="M35 22c-3 7-3 13 0 20"/>',
		'huevos' => '<path d="M23 10c7 0 13 14 13 24 0 8-5 14-13 14S10 42 10 34c0-10 6-24 13-24Z"/><path d="M43 18c6 0 11 12 11 20 0 7-5 12-11 12s-11-5-11-12c0-8 5-20 11-20Z"/>',
		'lacteos-quesos' => '<path d="M12 18h17l3 8v27H9V26l3-8Z"/><path d="M15 10h11v8H15z"/><path d="m38 52 6-24 15 8-5 16H38Z"/><path d="m44 28 10-5 5 13"/><circle cx="47" cy="41" r="2.2"/><circle cx="53" cy="46" r="1.8"/>',
		'legumbres-soja' => '<path d="M11 34h42c-2 14-9 21-21 21S13 48 11 34Z"/><path d="M9 34h46"/><ellipse cx="21" cy="27" rx="5" ry="3.8" transform="rotate(-20 21 27)"/><ellipse cx="33" cy="24" rx="5" ry="3.8" transform="rotate(16 33 24)"/><ellipse cx="45" cy="28" rx="5" ry="3.8" transform="rotate(-10 45 28)"/><path d="M18 13c10 1 18 5 24 12M18 13c-3 6-2 12 3 16"/>',
		'frutos-secos-semillas' => '<path d="M17 15c10-4 20 4 19 15-1 10-9 20-18 20-8 0-13-8-10-17 2-8 4-14 9-18Z"/><path d="M21 18c3 8 3 18-1 28M13 29c7 1 14 4 20 10"/><ellipse cx="47" cy="23" rx="5" ry="8" transform="rotate(28 47 23)"/><ellipse cx="49" cy="43" rx="4" ry="7" transform="rotate(-18 49 43)"/>',
		'cereales-pseudocereales-derivados' => '<path d="M27 55V10"/><path d="M27 19c-7 0-11-4-13-9 7 0 11 4 13 9Zm0 10c-7 0-11-4-13-9 7 0 11 4 13 9Zm0 10c-7 0-11-4-13-9 7 0 11 4 13 9Zm0-20c7 0 11-4 13-9-7 0-11 4-13 9Zm0 10c7 0 11-4 13-9-7 0-11 4-13 9Zm0 10c7 0 11-4 13-9-7 0-11 4-13 9Z"/><path d="M39 53c0-9 4-15 11-15s11 6 11 15H39Z"/><path d="M45 44h10M43 49h14"/>',
		'tuberculos' => '<path d="M13 34c0-12 10-22 23-22 12 0 21 7 21 18 0 13-11 24-25 24-11 0-19-8-19-20Z"/><circle cx="25" cy="27" r="1.5"/><circle cx="42" cy="22" r="1.5"/><circle cx="39" cy="40" r="1.5"/><path d="M24 13c-1-5 1-8 5-10M44 14c2-4 5-6 9-6"/>',
		'verduras-hortalizas-setas' => '<path d="M18 55 30 20l10 6-17 30-5-1Z"/><path d="M29 20c-5-7-3-12 2-15 4 5 4 10-2 15Zm3 2c5-7 10-8 14-5-2 6-7 9-14 5Z"/><path d="M39 35c2-8 7-12 13-12s11 4 13 12H39Z"/><path d="M47 35v15h10V35"/>',
		'frutas' => '<path d="M32 20c13-7 23 3 20 16-3 11-12 18-20 18S15 47 12 36c-3-13 7-23 20-16Z"/><path d="M32 20c-4-6-2-11 3-15"/><path d="M35 13c5-4 10-4 15-1-4 5-9 7-15 5"/>',
		'aceites-grasas' => '<path d="M20 16h24l5 11v29H15V27l5-11Z"/><path d="M24 7h16v9H24z"/><path d="M21 36h22"/><path d="M54 25c5 7 8 11 8 15 0 5-4 9-8 9s-8-4-8-9c0-4 3-8 8-15Z"/>',
		'bebidas' => '<path d="M13 20h28l-3 34H16l-3-34Z"/><path d="M11 20h32M24 20l8-12"/><path d="M45 27h7c6 0 6 12 0 12h-11"/><path d="M21 33c4 2 8 2 12 0"/>',
		'chocolate-cacao-dulces' => '<rect x="10" y="13" width="34" height="42" rx="2"/><path d="M21 13v42M33 13v42M10 27h34M10 41h34"/><path d="M51 18c7 4 10 11 7 17-3 6-10 8-16 5 7-4 10-11 9-22Z"/><path d="M48 22c2 5 2 10-2 15"/>',
		'fermentados' => '<path d="M16 18h32l-3 37H19l-3-37Z"/><path d="M14 18h36v-7H14z"/><path d="M22 29c7-4 13 4 20 0M22 39c6-3 13 3 20 0"/><circle cx="27" cy="25" r="1.8"/><circle cx="38" cy="35" r="1.8"/><circle cx="31" cy="46" r="1.8"/>',
		'algas-especias-otros-alimentos' => '<path d="M21 55c-4-13-2-24 5-34M26 38c-8-4-12-10-12-18 8 2 13 8 12 18Zm2 5c8-5 14-12 16-21 6 9 2 18-16 21Z"/><path d="M43 52c2-10 7-18 14-24M45 40c-6-3-8-7-8-12 6 1 9 5 8 12M49 45c6-3 9-7 10-12 4 6 1 11-10 12"/>',
	);

	$path = isset( $paths[ $slug ] )
		? $paths[ $slug ]
		: $paths['alimentacion-general'];

	return '<svg class="food-family-svg" viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
}

function food_topic_icon_svg( $slug ) {
	$paths = array(
		'nutricion-composicion' => '<circle cx="32" cy="32" r="21"/><path d="M32 11v21h21M32 32 18 48"/><path d="M17 19c6 0 11 3 15 8"/>',
		'rankings-mejores-fuentes' => '<path d="M8 52h48M13 35h12v17H13zM26 25h12v27H26zM39 15h12v37H39z"/><path d="m45 7 2 4 5 1-4 3 1 5-4-2-4 2 1-5-4-3 5-1 2-4Z"/>',
		'comparativas' => '<path d="M9 18h46M9 46h46"/><path d="m18 12-9 6 9 6M46 40l9 6-9 6"/><circle cx="31" cy="18" r="4"/><circle cx="34" cy="46" r="4"/>',
		'seguridad-alimentaria' => '<path d="M32 8 51 15v14c0 13-8 22-19 27-11-5-19-14-19-27V15l19-7Z"/><path d="m23 32 6 6 13-14"/>',
		'conservacion-almacenamiento' => '<path d="M15 12h34v42H15z"/><path d="M15 27h34M32 31v18"/><path d="M22 19h7M35 19h7"/>',
		'congelacion-descongelacion' => '<path d="M32 8v48M11 20l42 24M11 44l42-24M26 13l6 6 6-6M26 51l6-6 6 6M13 27l8 2-2-8M45 43l-2-8 8 2M13 37l8-2-2 8M45 21l-2 8 8-2"/>',
		'cocina-ciencia-alimentos' => '<path d="M9 40h27v9H9zM13 31h23v9H13z"/><path d="M45 10v18c0 4-5 7-5 13a9 9 0 0 0 18 0c0-6-5-9-5-13V10H45Z"/><path d="M45 30h8M48 15h5"/>',
		'preparacion-tecnicas-cocina' => '<path d="M9 45h42v10H9z"/><path d="m17 38 26-25 6 6-26 25"/><path d="m12 19 16 16M13 15l5 5M8 20l5 5"/>',
		'salud-consumo-habitual' => '<circle cx="32" cy="32" r="21"/><path d="M22 30c0-8 10-10 10-3 0-7 10-5 10 3 0 7-10 13-10 13S22 37 22 30Z"/><path d="M14 32h8M42 32h8"/>',
		'conceptos-nutricion' => '<path d="M9 15h21c5 0 8 3 8 8v31c0-5-3-8-8-8H9V15Z"/><path d="M55 15H34c-5 0-8 3-8 8v31c0-5 3-8 8-8h21V15Z"/><path d="M15 24h10M15 31h10M40 24h9M40 31h9"/>',
		'mitos-preguntas-frecuentes' => '<path d="M11 14h42v31H32L21 54v-9H11V14Z"/><path d="M25 25c1-6 13-7 14 0 1 6-7 6-7 12"/><circle cx="32" cy="42" r="1.5"/>',
		'procesamiento-produccion-elaboracion' => '<circle cx="24" cy="34" r="8"/><path d="M24 19v7M24 42v7M9 34h7M32 34h7M13 23l5 5M30 40l5 5M13 45l5-5M30 28l5-5"/><path d="M41 19h14v33H37V29h4V19Z"/><path d="M45 26h6M45 34h6M45 42h6"/>',
		'compra-calidad-maduracion' => '<path d="M13 25h38l-4 27H17l-4-27Z"/><path d="M22 25c0-7 4-12 10-12s10 5 10 12"/><path d="m24 38 6 6 12-14"/>',
	);

	$path = isset( $paths[ $slug ] )
		? $paths[ $slug ]
		: '<circle cx="32" cy="32" r="20"/><path d="M22 37c5-9 13-14 23-12-2 9-8 15-18 17"/>';

	return '<svg class="food-topic-svg" viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
}

function food_get_visual_topic( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'food_topic' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	$by_slug = array();
	foreach ( $terms as $term ) {
		$by_slug[ $term->slug ] = $term;
	}

	if ( function_exists( 'food_topic_definitions' ) ) {
		foreach ( array_keys( food_topic_definitions() ) as $slug ) {
			if ( isset( $by_slug[ $slug ] ) ) {
				return $by_slug[ $slug ];
			}
		}
	}

	return reset( $terms );
}

function food_get_post_visual_context( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$food    = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category( $post_id ) : null;

	if ( $food instanceof WP_Term ) {
		return array(
			'type'  => 'food',
			'slug'  => $food->slug,
			'label' => $food->name,
			'class' => 'family-' . sanitize_html_class( $food->slug ),
			'svg'   => food_category_icon_svg( $food->slug ),
		);
	}

	$topic = food_get_visual_topic( $post_id );
	if ( $topic instanceof WP_Term ) {
		return array(
			'type'  => 'topic',
			'slug'  => $topic->slug,
			'label' => $topic->name,
			'class' => 'topic-' . sanitize_html_class( $topic->slug ),
			'svg'   => food_topic_icon_svg( $topic->slug ),
		);
	}

	return array(
		'type'  => 'general',
		'slug'  => 'alimentacion-general',
		'label' => 'Pommelo',
		'class' => 'family-alimentacion-general',
		'svg'   => food_category_icon_svg( 'alimentacion-general' ),
	);
}

function food_get_term_visual_context( $term = null ) {
	$term = $term instanceof WP_Term ? $term : get_queried_object();
	if ( ! $term instanceof WP_Term ) {
		return null;
	}

	if ( 'food_topic' === $term->taxonomy ) {
		return array(
			'type'  => 'topic',
			'slug'  => $term->slug,
			'label' => $term->name,
			'class' => 'topic-' . sanitize_html_class( $term->slug ),
			'svg'   => food_topic_icon_svg( $term->slug ),
		);
	}

	if ( 'category' === $term->taxonomy ) {
		if ( 'alimentos' === $term->slug ) {
			return array(
				'type'  => 'food',
				'slug'  => 'alimentacion-general',
				'label' => $term->name,
				'class' => 'family-alimentacion-general',
				'svg'   => food_category_icon_svg( 'alimentacion-general' ),
			);
		}

		$definitions = function_exists( 'food_family_definitions' ) ? food_family_definitions() : array();
		if ( isset( $definitions[ $term->slug ] ) ) {
			return array(
				'type'  => 'food',
				'slug'  => $term->slug,
				'label' => $term->name,
				'class' => 'family-' . sanitize_html_class( $term->slug ),
				'svg'   => food_category_icon_svg( $term->slug ),
			);
		}
	}

	return array(
		'type'  => 'general',
		'slug'  => 'alimentacion-general',
		'label' => $term->name,
		'class' => 'family-alimentacion-general',
		'svg'   => food_category_icon_svg( 'alimentacion-general' ),
	);
}
