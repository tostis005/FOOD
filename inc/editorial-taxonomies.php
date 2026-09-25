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

/**
 * English archive descriptions live next to the editorial taxonomy so English
 * hubs never fall back to generic "Articles about this topic" copy.
 */
function food_topic_description_en( $slug ) {
	$descriptions = array(
		'nutricion-composicion' => 'Protein, fats, carbohydrates, fiber, vitamins, minerals, calories and food composition explained with practical context.',
		'rankings-mejores-fuentes' => 'Rankings and source guides that compare foods using a clear nutritional, culinary or quality criterion.',
		'comparativas' => 'Side-by-side differences between foods, varieties, formats and methods, with comparable data and the context needed to interpret it.',
		'seguridad-alimentaria' => 'Food-safety guidance on handling, cooking, storage, spoilage signs and when food should be discarded.',
		'conservacion-almacenamiento' => 'How long foods keep and how refrigeration, containers, temperature and storage conditions affect quality and safety.',
		'congelacion-descongelacion' => 'Which foods freeze well, how freezing changes them, how long they keep and safer ways to thaw them.',
		'cocina-ciencia-alimentos' => 'What happens inside food when heat, water, mixing, fermentation and other cooking processes change its structure.',
		'preparacion-tecnicas-cocina' => 'Practical methods, temperatures, timings and techniques for more reliable cooking results.',
		'salud-consumo-habitual' => 'General educational context on eating patterns, frequency of consumption and how foods can fit into an everyday diet.',
		'conceptos-nutricion' => 'Clear explanations of nutrition concepts such as protein, fiber, glycemic response, energy density and nutrient quality.',
		'mitos-preguntas-frecuentes' => 'Direct answers to common food questions and careful checks of popular nutrition and cooking claims.',
		'procesamiento-produccion-elaboracion' => 'How foods are produced, processed, fermented, cured and manufactured, and what those processes change.',
		'compra-calidad-maduracion' => 'How to choose foods, interpret labels and quality signals, judge ripeness and understand origin or commercial categories.',
	);
	return isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : '';
}

function food_family_description_en( $slug ) {
	$descriptions = array(
		'alimentacion-general' => 'Cross-cutting food guides covering everyday questions, nutrition, cooking, safety and concepts that apply to more than one food family.',
		'carnes' => 'Meat types and cuts, quality, storage, food safety, nutrition and the cooking science behind better results.',
		'pescados-mariscos' => 'Fish and seafood species, freshness, safety, storage, nutrition, buying cues and cooking techniques.',
		'huevos' => 'Egg freshness, labeling, storage, food safety, nutrition and the science behind different cooking methods.',
		'lacteos-quesos' => 'Milk, yogurt, cheese and other dairy foods: composition, varieties, processing, storage and quality.',
		'legumbres-soja' => 'Lentils, chickpeas, beans, soy and soy foods: nutrition, soaking, cooking, storage and practical uses.',
		'frutos-secos-semillas' => 'Nuts and seeds: nutrition, portions, storage, roasting, quality and differences between varieties.',
		'cereales-pseudocereales-derivados' => 'Rice, oats, wheat, quinoa, bread, pasta, flour and other grain foods: nutrition, processing, storage and cooking.',
		'tuberculos' => 'Potatoes, sweet potatoes and other tubers: nutrition, storage, food safety, preparation and cooking.',
		'verduras-hortalizas-setas' => 'Vegetables and mushrooms: seasonality, freshness, storage, safety, nutrition and cooking.',
		'frutas' => 'Fruit ripeness, seasonality, storage, safety, nutrition and practical signs of quality.',
		'aceites-grasas' => 'Olive oil, other oils and culinary fats: composition, quality, storage, cooking uses and how they differ.',
		'bebidas' => 'Water, coffee, tea, infusions and other drinks: composition, preparation, storage and everyday consumption.',
		'chocolate-cacao-dulces' => 'Chocolate, cocoa and sweet foods: ingredients, composition, processing, quality and storage.',
		'fermentados' => 'Fermented foods: microbes, fermentation processes, food safety, storage, production and consumption.',
		'algas-especias-otros-alimentos' => 'Seaweeds, spices, condiments and other foods: composition, uses, quality, safety and storage.',
	);
	return isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : '';
}

function food_taxonomy_archive_description( $term, $language = '' ) {
	if ( ! $term instanceof WP_Term ) {
		return '';
	}
	$language = $language ?: ( function_exists( 'food_current_language' ) ? food_current_language() : 'es' );

	if ( 'en' === $language ) {
		if ( 'food_topic' === $term->taxonomy ) {
			return food_topic_description_en( $term->slug );
		}
		if ( 'category' === $term->taxonomy ) {
			return food_family_description_en( $term->slug );
		}
	}

	return trim( wp_strip_all_tags( term_description( $term ) ) );
}

/**
 * Build useful cross-navigation for a taxonomy hub from the actual library.
 * A food-family page surfaces the main article topics represented there, while
 * a topic page surfaces the food families with the deepest coverage.
 */
function food_taxonomy_cross_links( $term, $language = '', $limit = 6 ) {
	if ( ! $term instanceof WP_Term || ! in_array( $term->taxonomy, array( 'category', 'food_topic' ), true ) ) {
		return array();
	}

	$language = 'en' === $language ? 'en' : 'es';
	$limit    = max( 1, (int) $limit );
	$cache_key = 'food_cross_' . $term->taxonomy . '_' . (int) $term->term_id . '_' . $language . '_v1';
	$cached    = get_transient( $cache_key );
	if ( is_array( $cached ) ) {
		return array_slice( $cached, 0, $limit );
	}

	$args = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'food_language_bypass'   => 1,
		'tax_query'              => array(
			array(
				'taxonomy' => $term->taxonomy,
				'field'    => 'term_id',
				'terms'    => array( (int) $term->term_id ),
			),
		),
	);
	if ( function_exists( 'food_language_query_clause' ) ) {
		$args['meta_query'] = array( food_language_query_clause( $language ) );
	}

	$post_ids = get_posts( $args );
	if ( empty( $post_ids ) ) {
		set_transient( $cache_key, array(), 12 * HOUR_IN_SECONDS );
		return array();
	}

	$other_taxonomy = 'category' === $term->taxonomy ? 'food_topic' : 'category';
	$terms          = wp_get_object_terms( $post_ids, $other_taxonomy, array( 'fields' => 'all_with_object_id' ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		set_transient( $cache_key, array(), 12 * HOUR_IN_SECONDS );
		return array();
	}

	$allowed = 'food_topic' === $other_taxonomy
		? array_fill_keys( array_keys( food_topic_definitions() ), true )
		: ( function_exists( 'food_family_definitions' ) ? array_fill_keys( array_keys( food_family_definitions() ), true ) : array() );

	$counts = array();
	$by_slug = array();
	foreach ( $terms as $other_term ) {
		if ( ! $other_term instanceof WP_Term || ! isset( $allowed[ $other_term->slug ] ) ) {
			continue;
		}
		$counts[ $other_term->slug ] = isset( $counts[ $other_term->slug ] ) ? $counts[ $other_term->slug ] + 1 : 1;
		$by_slug[ $other_term->slug ] = $other_term;
	}

	arsort( $counts, SORT_NUMERIC );
	$links = array();
	foreach ( $counts as $slug => $count ) {
		$other_term = $by_slug[ $slug ];
		if ( 'food_topic' === $other_taxonomy ) {
			$label = function_exists( 'food_topic_display' ) ? food_topic_display( $other_term ) : $other_term->name;
			$url   = function_exists( 'food_topic_url_for_language' ) ? food_topic_url_for_language( $other_term, $language ) : get_term_link( $other_term );
		} else {
			$label = function_exists( 'food_family_display' ) ? food_family_display( $other_term->slug ) : $other_term->name;
			$url   = function_exists( 'food_category_url_for_language' ) ? food_category_url_for_language( $other_term, $language ) : get_category_link( $other_term );
		}
		if ( is_wp_error( $url ) || ! $url ) {
			continue;
		}
		$links[] = array(
			'label' => $label,
			'url'   => $url,
			'count' => (int) $count,
		);
	}

	set_transient( $cache_key, $links, 12 * HOUR_IN_SECONDS );
	return array_slice( $links, 0, $limit );
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
