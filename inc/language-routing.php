<?php
/**
 * Native bilingual routing for Pometum.
 *
 * Spanish is the default/root language. English lives under /en/.
 * Article language is stored in _food_language by the source-controlled importer.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_language_definitions() {
	return array(
		'es' => array( 'label' => 'Español', 'short' => 'ES', 'flag' => '🇪🇸', 'locale' => 'es-ES', 'prefix' => '' ),
		'en' => array( 'label' => 'English', 'short' => 'EN', 'flag' => '🇬🇧', 'locale' => 'en-US', 'prefix' => 'en' ),
	);
}

function food_current_language() {
	$requested = get_query_var( 'food_lang' );
	if ( in_array( $requested, array( 'es', 'en' ), true ) ) {
		return $requested;
	}

	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	return preg_match( '#^/en(?:/|$)#', $path ) ? 'en' : 'es';
}

function food_is_english() {
	return 'en' === food_current_language();
}

function food_language_home_url( $language = '' ) {
	$language = $language ?: food_current_language();
	return 'en' === $language ? home_url( '/en/' ) : home_url( '/' );
}

function food_language_query_clause( $language = '' ) {
	$language = $language ?: food_current_language();
	if ( 'en' === $language ) {
		return array(
			'key'     => '_food_language',
			'value'   => 'en',
			'compare' => '=',
		);
	}

	return array(
		'relation' => 'OR',
		array( 'key' => '_food_language', 'value' => 'es', 'compare' => '=' ),
		array( 'key' => '_food_language', 'compare' => 'NOT EXISTS' ),
	);
}

function food_merge_language_meta_query( $query, $language = '' ) {
	$existing = $query->get( 'meta_query' );
	$language_clause = food_language_query_clause( $language );
	if ( empty( $existing ) ) {
		$query->set( 'meta_query', array( $language_clause ) );
		return;
	}
	$query->set( 'meta_query', array( 'relation' => 'AND', $existing, $language_clause ) );
}

function food_language_query_vars( $vars ) {
	$vars[] = 'food_lang';
	$vars[] = 'food_lang_home';
	$vars[] = 'food_language_bypass';
	return $vars;
}
add_filter( 'query_vars', 'food_language_query_vars' );

function food_register_language_rewrites() {
	add_rewrite_rule( '^en/?$', 'index.php?food_lang=en&food_lang_home=1', 'top' );
	add_rewrite_rule( '^en/page/([0-9]+)/?$', 'index.php?food_lang=en&food_lang_home=1&paged=$matches[1]', 'top' );
	add_rewrite_rule( '^en/alimentos/?$', 'index.php?category_name=alimentos&food_lang=en', 'top' );
	add_rewrite_rule( '^en/alimentos/([^/]+)/page/([0-9]+)/?$', 'index.php?category_name=$matches[1]&food_lang=en&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^en/alimentos/([^/]+)/?$', 'index.php?category_name=$matches[1]&food_lang=en', 'top' );
	add_rewrite_rule( '^en/tema/([^/]+)/page/([0-9]+)/?$', 'index.php?food_topic=$matches[1]&food_lang=en&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^en/tema/([^/]+)/?$', 'index.php?food_topic=$matches[1]&food_lang=en', 'top' );
	add_rewrite_rule( '^en/(?!alimentos(?:/|$)|tema(?:/|$))([^/]+)/?$', 'index.php?name=$matches[1]&food_lang=en', 'top' );

	if ( '1' !== get_option( 'food_language_rewrite_v1' ) ) {
		flush_rewrite_rules( false );
		update_option( 'food_language_rewrite_v1', '1' );
	}
}
add_action( 'init', 'food_register_language_rewrites', 90 );

function food_prepare_english_home_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->get( 'food_lang_home' ) ) {
		return;
	}
	$query->is_404 = false;
	$query->is_home = true;
}
add_action( 'parse_query', 'food_prepare_english_home_query', 1 );

function food_prevent_english_home_404( $preempt, $wp_query ) {
	if ( $wp_query->get( 'food_lang_home' ) ) {
		$wp_query->is_404 = false;
		return false;
	}
	return $preempt;
}
add_filter( 'pre_handle_404', 'food_prevent_english_home_404', 10, 2 );

function food_english_home_template( $template ) {
	if ( get_query_var( 'food_lang_home' ) ) {
		$front = get_template_directory() . '/front-page.php';
		return file_exists( $front ) ? $front : $template;
	}
	return $template;
}
add_filter( 'template_include', 'food_english_home_template', 99 );

function food_filter_main_query_language( $query ) {
	if ( is_admin() || ! $query->is_main_query() || $query->get( 'food_language_bypass' ) ) {
		return;
	}

	if ( $query->is_archive() || $query->is_search() || $query->is_home() ) {
		food_merge_language_meta_query( $query );
	}
}
add_action( 'pre_get_posts', 'food_filter_main_query_language', 30 );

function food_filter_secondary_queries_by_language( $query ) {
	if ( is_admin() || $query->is_main_query() || $query->get( 'food_language_bypass' ) || ! did_action( 'wp' ) ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( ! empty( $post_type ) && 'post' !== $post_type && ! ( is_array( $post_type ) && in_array( 'post', $post_type, true ) ) ) {
		return;
	}
	food_merge_language_meta_query( $query );
}
add_action( 'pre_get_posts', 'food_filter_secondary_queries_by_language', 31 );

function food_post_language( $post_id ) {
	$language = get_post_meta( (int) $post_id, '_food_language', true );
	return 'en' === $language ? 'en' : 'es';
}

function food_localized_post_link( $permalink, $post ) {
	if ( ! $post instanceof WP_Post || 'post' !== $post->post_type || 'en' !== food_post_language( $post->ID ) ) {
		return $permalink;
	}
	return home_url( '/en/' . $post->post_name . '/' );
}
add_filter( 'post_link', 'food_localized_post_link', 20, 2 );

function food_localized_category_link( $link, $term_id ) {
	if ( ! food_is_english() ) {
		return $link;
	}
	$term = get_term( $term_id, 'category' );
	if ( ! $term || is_wp_error( $term ) ) {
		return $link;
	}
	if ( 'alimentos' === $term->slug ) {
		return home_url( '/en/alimentos/' );
	}
	return home_url( '/en/alimentos/' . $term->slug . '/' );
}
add_filter( 'category_link', 'food_localized_category_link', 20, 2 );

function food_localized_term_link( $termlink, $term, $taxonomy ) {
	if ( food_is_english() && 'food_topic' === $taxonomy && $term instanceof WP_Term ) {
		return home_url( '/en/tema/' . $term->slug . '/' );
	}
	return $termlink;
}
add_filter( 'term_link', 'food_localized_term_link', 20, 3 );

function food_category_url_for_language( $term, $language ) {
	if ( ! $term instanceof WP_Term ) {
		return food_language_home_url( $language );
	}
	if ( 'en' === $language ) {
		return 'alimentos' === $term->slug ? home_url( '/en/alimentos/' ) : home_url( '/en/alimentos/' . $term->slug . '/' );
	}
	$link = get_category_link( $term );
	return is_wp_error( $link ) ? home_url( '/' ) : $link;
}

function food_topic_url_for_language( $term, $language ) {
	if ( ! $term instanceof WP_Term ) {
		return food_language_home_url( $language );
	}
	if ( 'en' === $language ) {
		return home_url( '/en/tema/' . $term->slug . '/' );
	}
	$link = get_term_link( $term );
	return is_wp_error( $link ) ? home_url( '/' ) : $link;
}

function food_translation_post( $post_id, $language ) {
	$group = get_post_meta( (int) $post_id, '_food_translation_group', true );
	if ( ! $group ) {
		return null;
	}
	$posts = get_posts(
		array(
			'post_type'            => 'post',
			'post_status'          => 'publish',
			'posts_per_page'       => 1,
			'meta_query'           => array(
				array( 'key' => '_food_translation_group', 'value' => $group ),
				array( 'key' => '_food_language', 'value' => $language ),
			),
			'food_language_bypass' => 1,
		)
	);
	return ! empty( $posts ) ? $posts[0] : null;
}

function food_language_switch_url( $target_language ) {
	if ( is_singular( 'post' ) ) {
		$translation = food_translation_post( get_the_ID(), $target_language );
		return $translation instanceof WP_Post ? get_permalink( $translation ) : food_language_home_url( $target_language );
	}
	if ( is_category() ) {
		return food_category_url_for_language( get_queried_object(), $target_language );
	}
	if ( is_tax( 'food_topic' ) ) {
		return food_topic_url_for_language( get_queried_object(), $target_language );
	}
	return food_language_home_url( $target_language );
}

function food_language_switcher() {
	$current = food_current_language();
	$defs    = food_language_definitions();
	?>
	<details class="language-switcher">
		<summary aria-label="<?php echo esc_attr( 'es' === $current ? 'Cambiar idioma' : 'Change language' ); ?>">
			<span class="language-flag" aria-hidden="true"><?php echo esc_html( $defs[ $current ]['flag'] ); ?></span>
			<span><?php echo esc_html( $defs[ $current ]['short'] ); ?></span>
		</summary>
		<div class="language-switcher-menu">
			<?php foreach ( $defs as $code => $definition ) : ?>
				<a href="<?php echo esc_url( food_language_switch_url( $code ) ); ?>" <?php echo $code === $current ? 'aria-current="page"' : ''; ?>>
					<span class="language-flag" aria-hidden="true"><?php echo esc_html( $definition['flag'] ); ?></span>
					<span><?php echo esc_html( $definition['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</details>
	<?php
}

function food_language_attributes( $output ) {
	$locale = food_language_definitions()[ food_current_language() ]['locale'];
	if ( preg_match( '/lang="[^"]*"/', $output ) ) {
		return preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $locale ) . '"', $output );
	}
	return 'lang="' . esc_attr( $locale ) . '" ' . $output;
}
add_filter( 'language_attributes', 'food_language_attributes' );

function food_localized_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 210 ) );
	return 'en' === food_current_language() ? sprintf( '%d min read', $minutes ) : sprintf( '%d min de lectura', $minutes );
}

function food_family_english_labels() {
	return array(
		'alimentacion-general' => array( 'name' => 'Food basics', 'short' => 'General food questions and everyday fundamentals.' ),
		'carnes' => array( 'name' => 'Meat', 'short' => 'Cuts, quality, storage and cooking.' ),
		'pescados-mariscos' => array( 'name' => 'Fish & seafood', 'short' => 'Species, freshness, safety and cooking.' ),
		'huevos' => array( 'name' => 'Eggs', 'short' => 'Freshness, labeling, nutrition and cooking.' ),
		'lacteos-quesos' => array( 'name' => 'Dairy & cheese', 'short' => 'Milk, yogurt, cheese, quality and storage.' ),
		'legumbres-soja' => array( 'name' => 'Legumes & soy', 'short' => 'Beans, lentils, soy, soaking and cooking.' ),
		'frutos-secos-semillas' => array( 'name' => 'Nuts & seeds', 'short' => 'Nuts, seeds, nutrition and storage.' ),
		'cereales-pseudocereales-derivados' => array( 'name' => 'Grains & pseudocereals', 'short' => 'Rice, oats, quinoa, bread, pasta and flour.' ),
		'tuberculos' => array( 'name' => 'Tubers', 'short' => 'Potatoes, sweet potatoes, storage and cooking.' ),
		'verduras-hortalizas-setas' => array( 'name' => 'Vegetables & mushrooms', 'short' => 'Freshness, seasonality, mushrooms and cooking.' ),
		'frutas' => array( 'name' => 'Fruit', 'short' => 'Ripeness, seasonality, storage and quality.' ),
		'aceites-grasas' => array( 'name' => 'Oils & fats', 'short' => 'Olive oil, fats, quality and culinary uses.' ),
		'bebidas' => array( 'name' => 'Drinks', 'short' => 'Water, coffee, tea, infusions and more.' ),
		'chocolate-cacao-dulces' => array( 'name' => 'Chocolate, cocoa & sweets', 'short' => 'Chocolate, cocoa, ingredients and quality.' ),
		'fermentados' => array( 'name' => 'Fermented foods', 'short' => 'Fermentation, production, storage and use.' ),
		'algas-especias-otros-alimentos' => array( 'name' => 'Seaweed, spices & more', 'short' => 'Seaweed, spices, condiments and other foods.' ),
	);
}

function food_family_display( $slug, $field = 'name' ) {
	if ( food_is_english() ) {
		$labels = food_family_english_labels();
		if ( isset( $labels[ $slug ][ $field ] ) ) {
			return $labels[ $slug ][ $field ];
		}
	}
	$defs = function_exists( 'food_family_definitions' ) ? food_family_definitions() : array();
	return isset( $defs[ $slug ][ $field ] ) ? $defs[ $slug ][ $field ] : $slug;
}

function food_topic_english_labels() {
	return array(
		'nutricion-composicion' => 'Nutrition & composition',
		'rankings-mejores-fuentes' => 'Rankings & best sources',
		'comparativas' => 'Comparisons',
		'seguridad-alimentaria' => 'Food safety',
		'conservacion-almacenamiento' => 'Storage & shelf life',
		'congelacion-descongelacion' => 'Freezing & thawing',
		'cocina-ciencia-alimentos' => 'Cooking & food science',
		'preparacion-tecnicas-cocina' => 'Preparation & cooking techniques',
		'salud-consumo-habitual' => 'Health & everyday consumption',
		'conceptos-nutricion' => 'Nutrition concepts',
		'mitos-preguntas-frecuentes' => 'Myths & common questions',
		'procesamiento-produccion-elaboracion' => 'Processing & production',
		'compra-calidad-maduracion' => 'Buying, quality & ripeness',
	);
}

function food_topic_display( $term_or_slug ) {
	$slug = $term_or_slug instanceof WP_Term ? $term_or_slug->slug : (string) $term_or_slug;
	if ( food_is_english() ) {
		$labels = food_topic_english_labels();
		if ( isset( $labels[ $slug ] ) ) {
			return $labels[ $slug ];
		}
	}
	if ( $term_or_slug instanceof WP_Term ) {
		return $term_or_slug->name;
	}
	$defs = function_exists( 'food_topic_definitions' ) ? food_topic_definitions() : array();
	return isset( $defs[ $slug ]['name'] ) ? $defs[ $slug ]['name'] : $slug;
}

function food_language_nav_fallback() {
	$items = food_is_english()
		? array(
			array( 'Foods', food_category_url( 'alimentos', 'Foods' ) ),
			array( 'Nutrition', food_topic_url( 'nutricion-composicion', 'Nutrition' ) ),
			array( 'Safety', food_topic_url( 'seguridad-alimentaria', 'Food safety' ) ),
			array( 'Cooking', food_topic_url( 'cocina-ciencia-alimentos', 'Cooking' ) ),
			array( 'Storage', food_topic_url( 'conservacion-almacenamiento', 'Storage' ) ),
		)
		: array(
			array( 'Alimentos', food_category_url( 'alimentos', 'Alimentos' ) ),
			array( 'Nutrición', food_topic_url( 'nutricion-composicion', 'Nutrición' ) ),
			array( 'Seguridad', food_topic_url( 'seguridad-alimentaria', 'Seguridad' ) ),
			array( 'Cocina', food_topic_url( 'cocina-ciencia-alimentos', 'Cocina' ) ),
			array( 'Conservación', food_topic_url( 'conservacion-almacenamiento', 'Conservación' ) ),
		);

	echo '<ul class="menu food-fallback-menu">';
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[1] ), esc_html( $item[0] ) );
	}
	echo '</ul>';
}

function food_language_breadcrumbs() {
	if ( is_front_page() || get_query_var( 'food_lang_home' ) ) {
		return;
	}
	$english = food_is_english();
	$home_label = $english ? 'Home' : 'Inicio';
	$aria = $english ? 'Breadcrumbs' : 'Migas de pan';
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr( $aria ) . '"><a href="' . esc_url( food_language_home_url() ) . '">' . esc_html( $home_label ) . '</a><span>›</span>';
	if ( is_single() ) {
		$food_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
		if ( $food_category ) {
			echo '<a href="' . esc_url( food_category_url_for_language( $food_category, food_current_language() ) ) . '">' . esc_html( food_family_display( $food_category->slug ) ) . '</a><span>›</span>';
		}
		echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() || is_tax( 'food_topic' ) ) {
		$term = get_queried_object();
		$label = is_category() ? food_family_display( $term->slug ) : food_topic_display( $term );
		echo '<span aria-current="page">' . esc_html( $label ) . '</span>';
	} elseif ( is_search() ) {
		echo '<span aria-current="page">' . esc_html( $english ? 'Search' : 'Buscar' ) . '</span>';
	}
	echo '</nav>';
}

function food_language_hreflang() {
	$es = food_language_switch_url( 'es' );
	$en = food_language_switch_url( 'en' );
	if ( ! $es || ! $en ) {
		return;
	}
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( $es ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $en ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $es ) . '">' . "\n";
}
add_action( 'wp_head', 'food_language_hreflang', 4 );

function food_redirect_wrong_language_article() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$post_language = food_post_language( get_queried_object_id() );
	if ( $post_language !== food_current_language() ) {
		wp_safe_redirect( get_permalink( get_queried_object_id() ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'food_redirect_wrong_language_article', 2 );

function food_migrate_existing_language_meta() {
	if ( '1' === get_option( 'food_language_meta_migration_v1' ) ) {
		return;
	}
	$ids = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'food_language_bypass' => 1 ) );
	foreach ( $ids as $post_id ) {
		if ( get_post_meta( $post_id, '_food_language', true ) ) {
			continue;
		}
		$source_json = (string) get_post_meta( $post_id, '_food_source_json', true );
		$source_id   = (string) get_post_meta( $post_id, '_food_source_id', true );
		$language    = ( 0 === strpos( $source_json, 'en/' ) || 0 === strpos( $source_id, 'en-' ) ) ? 'en' : 'es';
		update_post_meta( $post_id, '_food_language', $language );
	}
	update_option( 'food_language_meta_migration_v1', '1' );
}
add_action( 'init', 'food_migrate_existing_language_meta', 95 );
