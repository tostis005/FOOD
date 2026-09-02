<?php
/**
 * Bulk JSON importer for FOOD articles.
 *
 * Usage:
 *   php import-articles.php <wp-root> <articles-root> [--language=es|en|all] [--from=1] [--to=25] [--status=publish|draft|review|json]
 *
 * The importer is idempotent. It first matches _food_source_id and then falls
 * back to the post slug. Re-running the importer updates managed articles.
 */

if ( PHP_SAPI !== 'cli' ) {
    fwrite( STDERR, "CLI only.\n" );
    exit( 1 );
}

if ( $argc < 3 ) {
    fwrite( STDERR, "Usage: php import-articles.php <wp-root> <articles-root> [--language=es|en|all] [--from=1] [--to=25] [--status=publish|draft|review|json]\n" );
    exit( 1 );
}

$wp_root       = rtrim( $argv[1], '/' );
$articles_root = rtrim( $argv[2], '/' );
$wp_load       = $wp_root . '/wp-load.php';

if ( ! is_readable( $wp_load ) ) {
    fwrite( STDERR, "Cannot read {$wp_load}\n" );
    exit( 1 );
}

if ( ! is_dir( $articles_root ) ) {
    fwrite( STDERR, "Articles directory not found: {$articles_root}\n" );
    exit( 1 );
}

$options = array(
    'language' => 'es',
    'from'     => 1,
    'to'       => PHP_INT_MAX,
    'status'   => 'json',
);

foreach ( array_slice( $argv, 3 ) as $argument ) {
    if ( 0 !== strpos( $argument, '--' ) || false === strpos( $argument, '=' ) ) {
        continue;
    }
    list( $key, $value ) = explode( '=', substr( $argument, 2 ), 2 );
    if ( array_key_exists( $key, $options ) ) {
        $options[ $key ] = $value;
    }
}

$options['from'] = max( 1, (int) $options['from'] );
$options['to']   = max( $options['from'], (int) $options['to'] );

if ( ! in_array( $options['language'], array( 'es', 'en', 'all' ), true ) ) {
    fwrite( STDERR, "Invalid --language. Use es, en or all.\n" );
    exit( 1 );
}

if ( ! in_array( $options['status'], array( 'publish', 'draft', 'review', 'json' ), true ) ) {
    fwrite( STDERR, "Invalid --status. Use publish, draft, review or json.\n" );
    exit( 1 );
}

require_once $wp_load;

if ( function_exists( 'food_register_topic_taxonomy' ) ) {
    food_register_topic_taxonomy();
}
if ( function_exists( 'food_ensure_editorial_structure' ) ) {
    food_ensure_editorial_structure();
}
if ( function_exists( 'food_ensure_topic_terms' ) ) {
    food_ensure_topic_terms();
}

/** JSON vocabulary -> WordPress category slugs. */
function food_import_family_map() {
    return array(
        'general'                 => null,
        'meat'                    => 'carnes',
        'fish-seafood'            => 'pescados-mariscos',
        'eggs'                    => 'huevos',
        'dairy-cheese'            => 'lacteos-quesos',
        'legumes-soy'             => 'legumbres-soja',
        'nuts-seeds'              => 'frutos-secos-semillas',
        'grains-pseudocereals'    => 'cereales-pseudocereales-derivados',
        'tubers'                  => 'tuberculos',
        'vegetables-mushrooms'    => 'verduras-hortalizas-setas',
        'fruit'                   => 'frutas',
        'oils-fats'               => 'aceites-grasas',
        'beverages'               => 'bebidas',
        'cocoa-sweets'            => 'chocolate-cacao-dulces',
        'fermented'               => 'fermentados',
        'other-foods'             => 'algas-especias-otros-alimentos',
    );
}

/** JSON vocabulary -> food_topic term slugs. */
function food_import_topic_map() {
    return array(
        'nutrition-composition'     => 'nutricion-composicion',
        'rankings'                  => 'rankings-mejores-fuentes',
        'comparisons'               => 'comparativas',
        'food-safety'               => 'seguridad-alimentaria',
        'storage'                   => 'conservacion-almacenamiento',
        'freezing-thawing'          => 'congelacion-descongelacion',
        'cooking-science'           => 'cocina-ciencia-alimentos',
        'cooking-techniques'        => 'preparacion-tecnicas-cocina',
        'health-daily-consumption'  => 'salud-consumo-habitual',
        'nutrition-concepts'        => 'conceptos-nutricion',
        'myths-faq'                 => 'mitos-preguntas-frecuentes',
        'processing-production'     => 'procesamiento-produccion-elaboracion',
        'buying-quality-ripeness'   => 'compra-calidad-maduracion',
    );
}

function food_import_required_string( $data, $key, $file ) {
    if ( ! isset( $data[ $key ] ) || ! is_string( $data[ $key ] ) || '' === trim( $data[ $key ] ) ) {
        throw new RuntimeException( "Missing or invalid '{$key}' in {$file}" );
    }
    return trim( $data[ $key ] );
}

function food_import_find_existing( $source_id, $slug ) {
    $ids = get_posts(
        array(
            'post_type'      => 'post',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_food_source_id',
            'meta_value'     => $source_id,
        )
    );

    if ( ! empty( $ids ) ) {
        return get_post( (int) $ids[0] );
    }

    $by_slug = get_page_by_path( $slug, OBJECT, 'post' );
    return $by_slug instanceof WP_Post ? $by_slug : null;
}

function food_import_sources_html( $sources, $language ) {
    if ( empty( $sources ) || ! is_array( $sources ) ) {
        return '';
    }

    $title = 'en' === $language ? 'Sources' : 'Fuentes';
    $html  = '<h2>' . esc_html( $title ) . '</h2><ul class="food-article-sources">';

    foreach ( $sources as $source ) {
        if ( empty( $source['name'] ) || empty( $source['url'] ) ) {
            continue;
        }

        $name = esc_html( (string) $source['name'] );
        $url  = esc_url( (string) $source['url'] );
        $note = ! empty( $source['note'] ) ? ' — ' . esc_html( (string) $source['note'] ) : '';
        $html .= '<li><a href="' . $url . '" rel="noopener noreferrer">' . $name . '</a>' . $note . '</li>';
    }

    return $html . '</ul>';
}

function food_import_status( $json_status, $override ) {
    if ( 'json' !== $override ) {
        return 'review' === $override ? 'draft' : $override;
    }

    if ( 'publish' === $json_status ) {
        return 'publish';
    }

    return 'draft';
}

function food_import_apply_taxonomies( $post_id, $taxonomy ) {
    $family_map = food_import_family_map();
    $topic_map  = food_import_topic_map();

    $family_key  = isset( $taxonomy['food_family'] ) ? (string) $taxonomy['food_family'] : 'general';
    $family_slug = array_key_exists( $family_key, $family_map ) ? $family_map[ $family_key ] : null;

    // Every imported guide remains inside the aggregate Alimentos category.
    // A concrete food-family child is added only when one exists. This is
    // deliberate: general/transversal guides then fall back to article-type
    // artwork instead of the generic food-family artwork.
    $category_ids = array();
    $parent       = get_category_by_slug( 'alimentos' );
    if ( $parent instanceof WP_Term ) {
        $category_ids[] = (int) $parent->term_id;
    }

    if ( $family_slug ) {
        $family = get_category_by_slug( $family_slug );
        if ( ! $family instanceof WP_Term ) {
            throw new RuntimeException( "Food family term not found: {$family_slug}" );
        }
        $category_ids[] = (int) $family->term_id;
    }

    if ( ! empty( $category_ids ) ) {
        wp_set_post_categories( $post_id, array_values( array_unique( $category_ids ) ), false );
    }

    $topic_ids = array();
    $types     = ! empty( $taxonomy['article_types'] ) && is_array( $taxonomy['article_types'] )
        ? $taxonomy['article_types']
        : array();

    foreach ( $types as $type_key ) {
        if ( ! isset( $topic_map[ $type_key ] ) ) {
            continue;
        }
        $term = get_term_by( 'slug', $topic_map[ $type_key ], 'food_topic' );
        if ( $term instanceof WP_Term ) {
            $topic_ids[] = (int) $term->term_id;
        }
    }

    wp_set_object_terms( $post_id, array_values( array_unique( $topic_ids ) ), 'food_topic', false );

    $primary_key  = isset( $taxonomy['primary_article_type'] ) ? (string) $taxonomy['primary_article_type'] : '';
    $primary_slug = isset( $topic_map[ $primary_key ] ) ? $topic_map[ $primary_key ] : '';

    update_post_meta( $post_id, '_food_primary_article_type', $primary_slug );
    update_post_meta( $post_id, '_food_food_family', $family_slug ? $family_slug : '' );
    update_post_meta( $post_id, '_food_visual_basis', $family_slug ? 'food-family' : ( $primary_slug ? 'article-type' : 'general' ) );
    update_post_meta( $post_id, '_food_visual_slug', $family_slug ? $family_slug : ( $primary_slug ? $primary_slug : 'alimentacion-general' ) );
}

function food_import_apply_language( $post_id, $language, $translation_group ) {
    update_post_meta( $post_id, '_food_language', $language );
    update_post_meta( $post_id, '_food_translation_group', $translation_group );

    if ( function_exists( 'pll_set_post_language' ) ) {
        pll_set_post_language( $post_id, $language );
    }
}

function food_import_save_seo_meta( $post_id, $seo ) {
    $title       = isset( $seo['title'] ) ? (string) $seo['title'] : '';
    $description = isset( $seo['meta_description'] ) ? (string) $seo['meta_description'] : '';
    $intent      = isset( $seo['search_intent'] ) ? (string) $seo['search_intent'] : '';

    update_post_meta( $post_id, '_food_seo_title', $title );
    update_post_meta( $post_id, '_food_meta_description', $description );
    update_post_meta( $post_id, '_food_search_intent', $intent );

    // Populate common SEO-plugin fields when those plugins are installed.
    if ( defined( 'WPSEO_VERSION' ) ) {
        update_post_meta( $post_id, '_yoast_wpseo_title', $title );
        update_post_meta( $post_id, '_yoast_wpseo_metadesc', $description );
    }
    if ( defined( 'RANK_MATH_VERSION' ) ) {
        update_post_meta( $post_id, 'rank_math_title', $title );
        update_post_meta( $post_id, 'rank_math_description', $description );
    }
}

function food_import_link_polylang_translations( $translation_groups ) {
    if ( ! function_exists( 'pll_save_post_translations' ) ) {
        return;
    }

    foreach ( array_unique( $translation_groups ) as $group ) {
        $posts = get_posts(
            array(
                'post_type'      => 'post',
                'post_status'    => 'any',
                'posts_per_page' => -1,
                'meta_key'       => '_food_translation_group',
                'meta_value'     => $group,
            )
        );

        $translations = array();
        foreach ( $posts as $post ) {
            $lang = get_post_meta( $post->ID, '_food_language', true );
            if ( in_array( $lang, array( 'es', 'en' ), true ) ) {
                $translations[ $lang ] = (int) $post->ID;
            }
        }

        if ( count( $translations ) > 1 ) {
            pll_save_post_translations( $translations );
        }
    }
}

$languages = 'all' === $options['language'] ? array( 'es', 'en' ) : array( $options['language'] );
$files     = array();

foreach ( $languages as $language ) {
    $directory = $articles_root . '/' . $language;
    if ( ! is_dir( $directory ) ) {
        continue;
    }
    foreach ( glob( $directory . '/*.json' ) ?: array() as $file ) {
        $files[] = $file;
    }
}

sort( $files, SORT_NATURAL );

$created            = 0;
$updated            = 0;
$skipped            = 0;
$failed             = 0;
$translation_groups = array();

foreach ( $files as $file ) {
    try {
        $raw = file_get_contents( $file );
        if ( false === $raw ) {
            throw new RuntimeException( 'Could not read JSON.' );
        }

        $data = json_decode( $raw, true, 512, JSON_THROW_ON_ERROR );
        if ( ! is_array( $data ) ) {
            throw new RuntimeException( 'JSON root must be an object.' );
        }

        $number = isset( $data['article_number'] ) ? (int) $data['article_number'] : 0;
        if ( $number < $options['from'] || $number > $options['to'] ) {
            ++$skipped;
            continue;
        }

        $source_id         = food_import_required_string( $data, 'id', $file );
        $title             = food_import_required_string( $data, 'title', $file );
        $slug              = food_import_required_string( $data, 'slug', $file );
        $language          = food_import_required_string( $data, 'language', $file );
        $translation_group = food_import_required_string( $data, 'translation_group', $file );
        $content_html      = food_import_required_string( $data, 'content_html', $file );
        $excerpt           = isset( $data['excerpt'] ) ? (string) $data['excerpt'] : '';
        $seo               = isset( $data['seo'] ) && is_array( $data['seo'] ) ? $data['seo'] : array();
        $taxonomy          = isset( $data['taxonomy'] ) && is_array( $data['taxonomy'] ) ? $data['taxonomy'] : array();
        $sources           = isset( $data['sources'] ) && is_array( $data['sources'] ) ? $data['sources'] : array();
        $faq               = isset( $data['faq'] ) && is_array( $data['faq'] ) ? $data['faq'] : array();
        $image             = isset( $data['image'] ) && is_array( $data['image'] ) ? $data['image'] : array();
        $json_status       = isset( $data['status'] ) ? (string) $data['status'] : 'draft';
        $post_status       = food_import_status( $json_status, $options['status'] );

        $content = $content_html . food_import_sources_html( $sources, $language );

        $existing = food_import_find_existing( $source_id, $slug );
        $post_data = array(
            'post_title'     => $title,
            'post_name'      => $slug,
            'post_excerpt'   => $excerpt,
            'post_content'   => $content,
            'post_status'    => $post_status,
            'post_type'      => 'post',
            'comment_status' => 'closed',
        );

        if ( $existing instanceof WP_Post ) {
            $post_data['ID'] = (int) $existing->ID;
            $post_id         = wp_update_post( wp_slash( $post_data ), true );
            $action          = 'updated';
        } else {
            $admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
            $post_data['post_author'] = ! empty( $admins ) ? (int) $admins[0] : 1;
            $post_id = wp_insert_post( wp_slash( $post_data ), true );
            $action  = 'created';
        }

        if ( is_wp_error( $post_id ) ) {
            throw new RuntimeException( $post_id->get_error_message() );
        }

        food_import_apply_taxonomies( $post_id, $taxonomy );
        food_import_apply_language( $post_id, $language, $translation_group );
        food_import_save_seo_meta( $post_id, $seo );

        update_post_meta( $post_id, '_food_source_id', $source_id );
        update_post_meta( $post_id, '_food_article_number', $number );
        update_post_meta( $post_id, '_food_locale', isset( $data['locale'] ) ? (string) $data['locale'] : $language );
        update_post_meta( $post_id, '_food_market_context', isset( $data['market_context'] ) ? (string) $data['market_context'] : '' );
        update_post_meta( $post_id, '_food_source_json', str_replace( $articles_root . '/', '', $file ) );
        update_post_meta( $post_id, '_food_faq', wp_json_encode( $faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
        update_post_meta( $post_id, '_food_sources', wp_json_encode( $sources, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
        update_post_meta( $post_id, '_food_image_concept', isset( $image['concept'] ) ? (string) $image['concept'] : '' );
        update_post_meta( $post_id, '_food_image_alt', isset( $image['alt'] ) ? (string) $image['alt'] : '' );
        update_post_meta( $post_id, '_food_managed_article', '1' );

        // Imported posts intentionally have no generated featured image yet.
        // The theme renders the taxonomy visual: concrete food family first,
        // then primary article type for transversal/general guides.
        $translation_groups[] = $translation_group;
        clean_post_cache( $post_id );

        if ( 'created' === $action ) {
            ++$created;
        } else {
            ++$updated;
        }

        echo strtoupper( $action ) . " #{$number} [{$language}] post_id={$post_id} slug={$slug} visual=" . get_post_meta( $post_id, '_food_visual_slug', true ) . "\n";
    } catch ( Throwable $error ) {
        ++$failed;
        fwrite( STDERR, 'ERROR ' . basename( $file ) . ': ' . $error->getMessage() . "\n" );
    }
}

food_import_link_polylang_translations( $translation_groups );

echo "IMPORT_CREATED={$created}\n";
echo "IMPORT_UPDATED={$updated}\n";
echo "IMPORT_SKIPPED={$skipped}\n";
echo "IMPORT_FAILED={$failed}\n";

if ( $failed > 0 ) {
    exit( 2 );
}
