<?php
/**
 * Visual identities for Pommelo editorial taxonomies.
 *
 * A post may belong to a food family, an informational topic, both or neither.
 * For fallback artwork, food family always wins; topic is used only when there
 * is no product/food family assigned.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_topic_icon_svg( $slug ) {
	$paths = array(
		'seguridad-alimentaria' => '<path d="M32 8 51 15v14c0 13-8 22-19 27-11-5-19-14-19-27V15l19-7Z"/><path d="m23 32 6 6 13-14"/>',
		'nutricion' => '<circle cx="32" cy="32" r="21"/><path d="M32 11v42M11 32h42"/><path d="M20 20c6 2 10 6 12 12M44 20c-6 2-10 6-12 12"/>',
		'cocina-tecnica' => '<path d="M11 27h42v20H11z"/><path d="M17 27c2-8 8-13 15-13s13 5 15 13"/><path d="M26 9h12M32 9v5"/><path d="M16 47v6M48 47v6"/>',
		'conservacion' => '<path d="M17 14h30v40H17z"/><path d="M17 26h30M32 30v19"/><path d="M26 36h12M29 33l6 6M35 33l-6 6"/>',
		'compra-eleccion' => '<path d="M15 15h28l8 8-25 25-12-12 25-25"/><circle cx="37" cy="21" r="3"/><path d="m23 34 5 5 10-11"/>',
		'origen-calidad' => '<path d="M32 55s16-15 16-29a16 16 0 1 0-32 0c0 14 16 29 16 29Z"/><circle cx="32" cy="26" r="6"/><path d="m25 50-5 7 12-2 12 2-5-7"/>',
		'comparativas' => '<path d="M12 20h40M12 44h40"/><path d="m20 14-8 6 8 6M44 38l8 6-8 6"/><circle cx="32" cy="20" r="4"/><circle cx="32" cy="44" r="4"/>',
		'preguntas-frecuentes' => '<path d="M13 15h38v30H31l-10 9v-9h-8V15Z"/><path d="M26 26c1-5 11-6 12 0 1 5-6 5-6 10"/><circle cx="32" cy="40" r="1.5"/>',
		'platos-menus' => '<circle cx="31" cy="32" r="18"/><circle cx="31" cy="32" r="10"/><path d="M8 14v36M5 14v12c0 4 6 4 6 0V14M54 14v36M50 14c7 5 7 13 4 19"/>',
	);

	$path = isset( $paths[ $slug ] )
		? $paths[ $slug ]
		: '<circle cx="32" cy="32" r="20"/><path d="M21 36c6-10 14-15 24-13-2 10-8 16-19 19"/>';

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

	$priority = array(
		'seguridad-alimentaria',
		'nutricion',
		'cocina-tecnica',
		'conservacion',
		'compra-eleccion',
		'origen-calidad',
		'comparativas',
		'preguntas-frecuentes',
		'platos-menus',
	);

	foreach ( $priority as $slug ) {
		if ( isset( $by_slug[ $slug ] ) ) {
			return $by_slug[ $slug ];
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
			'svg'   => function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( $food->slug ) : '',
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
		'slug'  => 'general',
		'label' => 'Pommelo',
		'class' => 'family-general',
		'svg'   => function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( '' ) : '',
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

	if ( 'category' === $term->taxonomy && 'alimentos' !== $term->slug ) {
		$ancestors = get_ancestors( $term->term_id, 'category' );
		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, 'category' );
			if ( $ancestor instanceof WP_Term && 'alimentos' === $ancestor->slug ) {
				return array(
					'type'  => 'food',
					'slug'  => $term->slug,
					'label' => $term->name,
					'class' => 'family-' . sanitize_html_class( $term->slug ),
					'svg'   => function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( $term->slug ) : '',
				);
			}
		}
	}

	return array(
		'type'  => 'general',
		'slug'  => 'general',
		'label' => $term->name,
		'class' => 'family-general',
		'svg'   => function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( '' ) : '',
	);
}
