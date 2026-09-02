<?php
/**
 * Quinnoa editorial dimensions and homepage helpers.
 *
 * WordPress categories identify the food family. The food_topic taxonomy
 * identifies the kind of information the guide provides. Either dimension is
 * optional, so a post may use one, both, or neither when appropriate.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Definitive first-version classification by type of article. */
function food_topic_definitions() {
	return array(
		'nutricion-composicion' => array(
			'name'        => 'Nutrición y composición',
			'description' => 'Proteínas, grasas, hidratos, fibra, vitaminas, minerales, calorías y composición de los alimentos explicadas con contexto.',
		),
		'rankings-mejores-fuentes' => array(
			'name'        => 'Rankings y mejores fuentes',
			'description' => 'Listas y rankings para identificar los alimentos que más aportan un nutriente o cumplen mejor un criterio concreto.',
		),
		'comparativas' => array(
			'name'        => 'Comparativas',
			'description' => 'Diferencias entre alimentos, variedades, formatos o métodos para entender qué cambia y elegir con más criterio.',
		),
		'seguridad-alimentaria' => array(
			'name'        => 'Seguridad alimentaria',
			'description' => 'Cuándo un alimento es seguro, cuándo conviene descartarlo y cómo reducir riesgos al manipular, cocinar o conservar comida.',
		),
		'conservacion-almacenamiento' => array(
			'name'        => 'Conservación y almacenamiento',
			'description' => 'Cuánto duran los alimentos y cómo guardarlos en nevera, despensa o recipientes para mantenerlos en buenas condiciones.',
		),
		'congelacion-descongelacion' => array(
			'name'        => 'Congelación y descongelación',
			'description' => 'Qué alimentos se pueden congelar, cuánto duran congelados y cómo descongelarlos de forma segura y práctica.',
		),
		'cocina-ciencia-alimentos' => array(
			'name'        => 'Cocina y ciencia de los alimentos',
			'description' => 'Qué ocurre dentro de los alimentos al calentarlos, mezclarlos o transformarlos y por qué cambia el resultado al cocinar.',
		),
		'preparacion-tecnicas-cocina' => array(
			'name'        => 'Preparación y técnicas de cocina',
			'description' => 'Métodos, tiempos, temperaturas y técnicas para preparar alimentos con mejores resultados y menos errores.',
		),
		'salud-consumo-habitual' => array(
			'name'        => 'Salud y consumo habitual',
			'description' => 'Información general sobre frecuencia de consumo, patrones alimentarios y cómo encajan distintos alimentos en la dieta cotidiana.',
		),
		'conceptos-nutricion' => array(
			'name'        => 'Conceptos de nutrición',
			'description' => 'Explicaciones sencillas de conceptos como proteína, fibra, índice glucémico, densidad energética o calidad nutricional.',
		),
		'mitos-preguntas-frecuentes' => array(
			'name'        => 'Mitos y preguntas frecuentes',
			'description' => 'Respuestas directas a dudas habituales y revisión de afirmaciones populares sobre alimentos, cocina y nutrición.',
		),
		'procesamiento-produccion-elaboracion' => array(
			'name'        => 'Procesamiento, producción y elaboración',
			'description' => 'Cómo se producen, procesan, fermentan, curan o elaboran los alimentos y qué implica cada proceso.',
		),
		'compra-calidad-maduracion' => array(
			'name'        => 'Compra, calidad y maduración',
			'description' => 'Cómo elegir alimentos, interpretar señales de calidad, reconocer el punto de maduración y entender etiquetas, categorías y origen.',
		),
	);
}

function food_register_topic_taxonomy() {
	register_taxonomy(
		'food_topic',
		array( 'post' ),
		array(
			'labels' => array(
				'name'                       => __( 'Tipos de artículo', 'food' ),
				'singular_name'              => __( 'Tipo de artículo', 'food' ),
				'search_items'               => __( 'Buscar tipos de artículo', 'food' ),
				'all_items'                  => __( 'Todos los tipos', 'food' ),
				'edit_item'                  => __( 'Editar tipo', 'food' ),
				'update_item'                => __( 'Actualizar tipo', 'food' ),
				'add_new_item'               => __( 'Añadir tipo', 'food' ),
				'new_item_name'              => __( 'Nombre del tipo', 'food' ),
				'menu_name'                  => __( 'Tipos de artículo', 'food' ),
				'popular_items'              => __( 'Tipos frecuentes', 'food' ),
				'separate_items_with_commas' => __( 'Separa tipos con comas', 'food' ),
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
	$version = '2';
	if ( get_option( 'food_topic_structure_version' ) === $version ) {
		return;
	}

	foreach ( food_topic_definitions() as $slug => $definition ) {
		$term = get_term_by( 'slug', $slug, 'food_topic' );
		if ( $term ) {
			wp_update_term(
				$term->term_id,
				'food_topic',
				array(
					'name'        => $definition['name'],
					'description' => $definition['description'],
				)
			);
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
 * Move assignments made with the previous provisional taxonomy into the final
 * article-type vocabulary. Old empty terms are intentionally left in place so
 * any previously exposed URL fails gracefully and remains noindex when thin.
 */
function food_migrate_topic_terms_v2() {
	if ( get_option( 'food_topic_terms_migrated_v2' ) ) {
		return;
	}

	$map = array(
		'nutricion'             => 'nutricion-composicion',
		'cocina-tecnica'        => 'cocina-ciencia-alimentos',
		'conservacion'          => 'conservacion-almacenamiento',
		'compra-eleccion'       => 'compra-calidad-maduracion',
		'origen-calidad'        => 'compra-calidad-maduracion',
		'preguntas-frecuentes'  => 'mitos-preguntas-frecuentes',
		'platos-menus'          => 'preparacion-tecnicas-cocina',
		'comparativas'          => 'comparativas',
		'seguridad-alimentaria' => 'seguridad-alimentaria',
	);

	foreach ( $map as $old_slug => $new_slug ) {
		$old_term = get_term_by( 'slug', $old_slug, 'food_topic' );
		$new_term = get_term_by( 'slug', $new_slug, 'food_topic' );
		if ( ! $old_term || ! $new_term ) {
			continue;
		}

		$post_ids = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'tax_query'      => array(
					array(
						'taxonomy' => 'food_topic',
						'field'    => 'term_id',
						'terms'    => array( $old_term->term_id ),
					),
				),
			)
		);

		foreach ( $post_ids as $post_id ) {
			wp_set_object_terms( $post_id, (int) $new_term->term_id, 'food_topic', true );
			if ( $old_slug !== $new_slug ) {
				wp_remove_object_terms( $post_id, (int) $old_term->term_id, 'food_topic' );
			}
		}
	}

	update_option( 'food_topic_terms_migrated_v2', 1 );
}
add_action( 'init', 'food_migrate_topic_terms_v2', 42 );

/**
 * Carry legacy transversal WordPress categories into the current independent
 * topic taxonomy when they still exist on old posts.
 */
function food_migrate_legacy_categories_to_topics_v2() {
	if ( get_option( 'food_legacy_categories_to_topics_v2' ) ) {
		return;
	}

	$map = array(
		'seguridad-alimentaria' => 'seguridad-alimentaria',
		'nutricion'             => 'nutricion-composicion',
		'cocina'                => 'cocina-ciencia-alimentos',
		'platos-menus'          => 'preparacion-tecnicas-cocina',
		'origen-calidad'        => 'compra-calidad-maduracion',
	);

	foreach ( $map as $category_slug => $topic_slug ) {
		$category = get_category_by_slug( $category_slug );
		$topic    = get_term_by( 'slug', $topic_slug, 'food_topic' );
		if ( ! $category || ! $topic ) {
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
			wp_set_object_terms( $post_id, (int) $topic->term_id, 'food_topic', true );
		}
	}

	update_option( 'food_legacy_categories_to_topics_v2', 1 );
}
add_action( 'init', 'food_migrate_legacy_categories_to_topics_v2', 45 );

/** Keep the reference guide classified correctly after the vocabulary change. */
function food_migrate_known_seed_content_v2() {
	if ( get_option( 'food_known_seed_content_migrated_v2' ) ) {
		return;
	}

	$post = get_page_by_path( 'por-que-la-carne-suelta-agua-en-la-sarten', OBJECT, 'post' );
	if ( $post instanceof WP_Post ) {
		$category = get_category_by_slug( 'carnes' );
		$topic    = get_term_by( 'slug', 'cocina-ciencia-alimentos', 'food_topic' );
		if ( $category instanceof WP_Term ) {
			wp_set_post_categories( $post->ID, array( $category->term_id ), true );
		}
		if ( $topic instanceof WP_Term ) {
			wp_set_object_terms( $post->ID, (int) $topic->term_id, 'food_topic', true );
		}
	}

	update_option( 'food_known_seed_content_migrated_v2', 1 );
}
add_action( 'init', 'food_migrate_known_seed_content_v2', 50 );

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
		array( 'Nutrición', food_topic_url( 'nutricion-composicion', 'Nutrición y composición' ) ),
		array( 'Seguridad', food_topic_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ),
		array( 'Cocina', food_topic_url( 'cocina-ciencia-alimentos', 'Cocina y ciencia de los alimentos' ) ),
		array( 'Conservación', food_topic_url( 'conservacion-almacenamiento', 'Conservación y almacenamiento' ) ),
	);

	echo '<ul class="menu food-fallback-menu">';
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[1] ), esc_html( $item[0] ) );
	}
	echo '</ul>';
}

function food_get_primary_food_category( $post_id = 0 ) {
	$post_id    = $post_id ? (int) $post_id : get_the_ID();
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return null;
	}

	$by_slug = array();
	foreach ( $categories as $category ) {
		$by_slug[ $category->slug ] = $category;
	}

	if ( function_exists( 'food_family_definitions' ) ) {
		foreach ( array_keys( food_family_definitions() ) as $slug ) {
			if ( isset( $by_slug[ $slug ] ) ) {
				return $by_slug[ $slug ];
			}
		}
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
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'food_topic' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	$by_slug = array();
	foreach ( $terms as $term ) {
		$by_slug[ $term->slug ] = $term;
	}
	foreach ( array_keys( food_topic_definitions() ) as $slug ) {
		if ( isset( $by_slug[ $slug ] ) ) {
			return $by_slug[ $slug ];
		}
	}

	return reset( $terms );
}

function food_get_article_topics( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'food_topic' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return array();
	}

	$by_slug = array();
	foreach ( $terms as $term ) {
		$by_slug[ $term->slug ] = $term;
	}

	$ordered = array();
	foreach ( array_keys( food_topic_definitions() ) as $slug ) {
		if ( isset( $by_slug[ $slug ] ) ) {
			$ordered[] = $by_slug[ $slug ];
		}
	}
	return $ordered;
}

/** WordPress sample content should never become a homepage recommendation. */
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
