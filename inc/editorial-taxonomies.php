<?php
/**
 * FOOD editorial dimensions and homepage helpers.
 *
 * Categories answer "what food is this about?" while food_topic answers
 * "what kind of question does this article solve?".
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_topic_definitions() {
	return array(
		'seguridad-alimentaria' => array(
			'name'        => 'Seguridad alimentaria',
			'description' => 'Cuándo un alimento es seguro, cuándo descartarlo y cómo reducir riesgos al manipularlo.',
		),
		'nutricion' => array(
			'name'        => 'Nutrición',
			'description' => 'Proteínas, grasas, hidratos, fibra, energía y composición nutricional explicadas con contexto.',
		),
		'cocina-tecnica' => array(
			'name'        => 'Cocina y técnica',
			'description' => 'Qué ocurre al cocinar, por qué ocurre y cómo mejorar el resultado.',
		),
		'conservacion' => array(
			'name'        => 'Conservación',
			'description' => 'Cómo guardar, congelar, descongelar y mantener los alimentos en buenas condiciones.',
		),
		'compra-eleccion' => array(
			'name'        => 'Compra y elección',
			'description' => 'Claves prácticas para comparar productos, leer etiquetas y elegir con criterio.',
		),
		'origen-calidad' => array(
			'name'        => 'Origen y calidad',
			'description' => 'Procedencia, DOP, sellos, variedades, categorías comerciales y señales de calidad.',
		),
		'comparativas' => array(
			'name'        => 'Comparativas',
			'description' => 'Diferencias entre alimentos, cortes, variedades o productos para decidir mejor.',
		),
		'preguntas-frecuentes' => array(
			'name'        => 'Preguntas frecuentes',
			'description' => 'Dudas concretas y respuestas directas sobre situaciones habituales con alimentos.',
		),
		'platos-menus' => array(
			'name'        => 'Platos y menús',
			'description' => 'Combinaciones, platos cotidianos y maneras de construir comidas completas.',
		),
	);
}

function food_register_topic_taxonomy() {
	register_taxonomy(
		'food_topic',
		array( 'post' ),
		array(
			'labels' => array(
				'name'                       => __( 'Temas', 'food' ),
				'singular_name'              => __( 'Tema', 'food' ),
				'search_items'               => __( 'Buscar temas', 'food' ),
				'all_items'                  => __( 'Todos los temas', 'food' ),
				'edit_item'                  => __( 'Editar tema', 'food' ),
				'update_item'                => __( 'Actualizar tema', 'food' ),
				'add_new_item'               => __( 'Añadir tema', 'food' ),
				'new_item_name'              => __( 'Nombre del tema', 'food' ),
				'menu_name'                  => __( 'Temas', 'food' ),
				'popular_items'              => __( 'Temas frecuentes', 'food' ),
				'separate_items_with_commas' => __( 'Separa temas con comas', 'food' ),
			),
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => false,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'tema', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'food_register_topic_taxonomy', 8 );

function food_ensure_topic_terms() {
	$version = '1';
	if ( get_option( 'food_topic_structure_version' ) === $version ) {
		return;
	}

	foreach ( food_topic_definitions() as $slug => $definition ) {
		$term = get_term_by( 'slug', $slug, 'food_topic' );
		if ( $term ) {
			wp_update_term( $term->term_id, 'food_topic', array( 'description' => $definition['description'] ) );
			continue;
		}

		wp_insert_term(
			$definition['name'],
			'food_topic',
			array(
				'slug'        => $slug,
				'description' => $definition['description'],
			)
		);
	}

	flush_rewrite_rules( false );
	update_option( 'food_topic_structure_version', $version );
}
add_action( 'init', 'food_ensure_topic_terms', 22 );

/**
 * Carry the original transversal categories into the new independent taxonomy.
 * We keep the old category relationship for now so no existing URL is broken.
 */
function food_migrate_legacy_topics() {
	if ( get_option( 'food_legacy_topics_migrated_v1' ) ) {
		return;
	}

	$map = array(
		'seguridad-alimentaria' => 'seguridad-alimentaria',
		'nutricion'             => 'nutricion',
		'cocina'                => 'cocina-tecnica',
		'platos-menus'          => 'platos-menus',
		'origen-calidad'        => 'origen-calidad',
	);

	foreach ( $map as $category_slug => $topic_slug ) {
		$category = get_category_by_slug( $category_slug );
		if ( ! $category ) {
			continue;
		}

		$post_ids = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'category'       => $category->term_id,
			)
		);

		foreach ( $post_ids as $post_id ) {
			wp_set_object_terms( $post_id, $topic_slug, 'food_topic', true );
		}
	}

	update_option( 'food_legacy_topics_migrated_v1', 1 );
}
add_action( 'init', 'food_migrate_legacy_topics', 45 );

/**
 * Correct the small amount of seed content that predates the two-dimensional
 * taxonomy. This is deliberately explicit, not a keyword-based classifier.
 */
function food_migrate_known_food_families() {
	if ( get_option( 'food_known_food_families_migrated_v1' ) ) {
		return;
	}

	$known_posts = array(
		'por-que-la-carne-suelta-agua-en-la-sarten' => 'carnes',
	);

	foreach ( $known_posts as $post_slug => $category_slug ) {
		$post     = get_page_by_path( $post_slug, OBJECT, 'post' );
		$category = get_category_by_slug( $category_slug );
		if ( $post instanceof WP_Post && $category instanceof WP_Term ) {
			wp_set_post_categories( $post->ID, array( $category->term_id ), true );
		}
	}

	update_option( 'food_known_food_families_migrated_v1', 1 );
}
add_action( 'init', 'food_migrate_known_food_families', 50 );

function food_topic_url( $slug, $fallback_label = '' ) {
	$term = get_term_by( 'slug', $slug, 'food_topic' );
	if ( $term && ! is_wp_error( $term ) ) {
		$link = get_term_link( $term );
		if ( ! is_wp_error( $link ) ) {
			return $link;
		}
	}

	$search = $fallback_label ? $fallback_label : str_replace( '-', ' ', $slug );
	return home_url( '/?s=' . rawurlencode( $search ) );
}

function food_primary_nav_fallback() {
	$items = array(
		array( 'Alimentos', food_category_url( 'alimentos', 'Alimentos' ) ),
		array( 'Seguridad', food_topic_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ),
		array( 'Nutrición', food_topic_url( 'nutricion', 'Nutrición' ) ),
		array( 'Cocina', food_topic_url( 'cocina-tecnica', 'Cocina' ) ),
		array( 'Origen y calidad', food_topic_url( 'origen-calidad', 'Origen y calidad' ) ),
	);

	echo '<ul class="menu food-fallback-menu">';
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[1] ), esc_html( $item[0] ) );
	}
	echo '</ul>';
}

function food_get_primary_food_category( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return null;
	}

	foreach ( $categories as $category ) {
		if ( 'alimentos' === $category->slug ) {
			continue;
		}
		$ancestors = get_ancestors( $category->term_id, 'category' );
		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, 'category' );
			if ( $ancestor && ! is_wp_error( $ancestor ) && 'alimentos' === $ancestor->slug ) {
				return $category;
			}
		}
	}

	return null;
}

function food_get_primary_topic( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'food_topic' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}
	return $terms[0];
}

/**
 * Small vector illustrations used as professional, lightweight fallbacks.
 */
function food_category_icon_svg( $slug ) {
	$paths = array(
		'carnes' => '<path d="M16 34c0-10 9-19 21-19 9 0 16 5 16 13 0 11-12 21-26 21-7 0-11-5-11-15Z"/><path d="M33 23c5-3 11-1 12 3 1 5-4 10-10 10-5 0-8-3-7-7 0-2 2-4 5-6Z"/>',
		'pescados-mariscos' => '<path d="M12 32c9-11 20-16 31-12 6 2 10 7 11 12-2 6-6 10-12 12-11 3-21-1-30-12Z"/><path d="M12 32 5 23v18l7-9Z"/><circle cx="45" cy="28" r="1.8"/>',
		'jamon-embutidos' => '<path d="M19 45c-5-5-4-13 2-22 6-9 15-13 22-8 8 5 8 15 2 23-7 10-20 14-26 7Z"/><path d="m19 45-7 7"/><circle cx="10" cy="54" r="4"/>',
		'quesos-lacteos' => '<path d="m11 43 9-25 33 11-8 24H11Z"/><path d="M20 18 45 9l8 20"/><circle cx="28" cy="37" r="3"/><circle cx="40" cy="44" r="2.5"/>',
		'aceites' => '<path d="M32 8c7 10 13 18 13 27 0 8-6 15-13 15s-13-7-13-15c0-9 6-17 13-27Z"/><path d="M42 16c4-5 9-7 14-7-1 6-5 10-11 11"/>',
		'legumbres' => '<path d="M18 18c10-6 22 2 19 13-2 8-10 15-18 15-9 0-14-8-11-16 2-5 5-9 10-12Z"/><path d="M42 28c7-4 15 1 14 9-1 7-7 12-13 12-7 0-11-6-9-12 1-4 4-7 8-9Z"/>',
		'frutas' => '<path d="M32 19c-4-5-2-10 3-13"/><path d="M35 13c5-4 10-4 15-1-4 5-9 7-15 5"/><path d="M32 20c14-8 24 3 21 16-3 12-13 19-21 19S14 48 11 36c-3-13 7-24 21-16Z"/>',
		'verduras-hortalizas' => '<path d="M31 54C13 47 9 29 19 13c10 4 17 11 18 21 2-10 8-17 17-21 8 18-2 36-23 41Z"/><path d="M31 54c1-13 4-25 12-35"/>',
		'cereales-pan-pasta' => '<path d="M31 56V13"/><path d="M31 21c-8 0-13-4-15-10 8 0 13 4 15 10Zm0 11c-8 0-13-4-15-10 8 0 13 4 15 10Zm0 11c-8 0-13-4-15-10 8 0 13 4 15 10Zm0-22c8 0 13-4 15-10-8 0-13 4-15 10Zm0 11c8 0 13-4 15-10-8 0-13 4-15 10Zm0 11c8 0 13-4 15-10-8 0-13 4-15 10Z"/>',
		'huevos' => '<path d="M32 8c8 0 17 18 17 30 0 10-7 18-17 18s-17-8-17-18c0-12 9-30 17-30Z"/><path d="M23 39c2 6 6 9 12 9"/>',
	);

	$path = isset( $paths[ $slug ] )
		? $paths[ $slug ]
		: '<circle cx="32" cy="32" r="19"/><path d="M21 38c6-11 15-16 25-14-2 10-8 17-19 19"/><path d="M27 43c3-8 8-14 15-18"/>';

	return '<svg class="food-family-svg" viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
}

/**
 * WordPress' localized default sample post is useful during installation, but
 * should never become a homepage recommendation.
 */
function food_home_ignored_post_ids() {
	$ids = array();
	foreach ( array( 'hello-world', 'hola-mundo' ) as $sample_slug ) {
		$sample = get_page_by_path( $sample_slug, OBJECT, 'post' );
		if ( $sample instanceof WP_Post ) {
			$ids[] = (int) $sample->ID;
		}
	}
	return array_values( array_unique( $ids ) );
}

function food_get_home_feature_post() {
	$ignored = food_home_ignored_post_ids();
	$sticky  = array_values( array_diff( array_map( 'intval', (array) get_option( 'sticky_posts' ) ), $ignored ) );

	$base_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
		'post__not_in'        => $ignored,
	);

	if ( ! empty( $sticky ) ) {
		$sticky_args             = $base_args;
		$sticky_args['post__in'] = $sticky;
		$query = new WP_Query( $sticky_args );
		if ( $query->have_posts() ) {
			return $query->posts[0];
		}
	}

	$query = new WP_Query( $base_args );
	return $query->have_posts() ? $query->posts[0] : null;
}

function food_get_rotating_post_ids( $count = 5, $exclude = array() ) {
	$count      = max( 1, (int) $count );
	$exclude    = array_values( array_unique( array_merge( array_filter( array_map( 'intval', (array) $exclude ) ), food_home_ignored_post_ids() ) ) );
	$cache_key  = 'food_home_rotation_' . $count . '_' . md5( implode( ',', $exclude ) );
	$cached_ids = get_transient( $cache_key );

	if ( is_array( $cached_ids ) ) {
		return $cached_ids;
	}

	$candidates = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 60,
			'fields'         => 'ids',
			'post__not_in'   => $exclude,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( count( $candidates ) > $count ) {
		shuffle( $candidates );
	}
	$ids = array_slice( $candidates, 0, $count );
	set_transient( $cache_key, $ids, 6 * HOUR_IN_SECONDS );
	return $ids;
}

function food_clear_home_rotation_cache() {
	global $wpdb;
	$like = $wpdb->esc_like( '_transient_food_home_rotation_' ) . '%';
	$keys = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );
	foreach ( $keys as $option_name ) {
		$key = str_replace( '_transient_', '', $option_name );
		delete_transient( $key );
	}
}
add_action( 'save_post_post', 'food_clear_home_rotation_cache' );
add_action( 'deleted_post', 'food_clear_home_rotation_cache' );

function food_topic_archive_robots( $robots ) {
	if ( is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && (int) $term->count < 3 ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		}
	}
	return $robots;
}
add_filter( 'wp_robots', 'food_topic_archive_robots' );
