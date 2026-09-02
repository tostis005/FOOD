<?php
/**
 * Idempotent publisher for the FOOD reference article.
 * Usage: php publish-reference-article.php /path/to/wordpress /path/to/article.html
 */

if ( PHP_SAPI !== 'cli' ) {
    fwrite( STDERR, "CLI only.\n" );
    exit( 1 );
}

if ( $argc < 3 ) {
    fwrite( STDERR, "Usage: php publish-reference-article.php <wp-root> <article-file>\n" );
    exit( 1 );
}

$wp_root      = rtrim( $argv[1], '/' );
$article_file = $argv[2];
$wp_load      = $wp_root . '/wp-load.php';

if ( ! is_readable( $wp_load ) ) {
    fwrite( STDERR, "Cannot read {$wp_load}\n" );
    exit( 1 );
}

if ( ! is_readable( $article_file ) ) {
    fwrite( STDERR, "Cannot read {$article_file}\n" );
    exit( 1 );
}

require_once $wp_load;

$title   = '¿Por qué la carne suelta agua en la sartén? Causas y cómo evitarlo';
$slug    = 'por-que-la-carne-suelta-agua-en-la-sarten';
$excerpt = 'La carne suelta agua porque contiene mucha humedad de forma natural y sus proteínas pierden parte de su capacidad para retenerla al calentarse. Si la sartén está poco caliente o demasiado llena, esa humedad se acumula y la carne se cuece en vez de dorarse.';
$content = file_get_contents( $article_file );

if ( false === $content || '' === trim( $content ) ) {
    fwrite( STDERR, "Article content is empty.\n" );
    exit( 1 );
}

$category = get_term_by( 'slug', 'carnes', 'category' );
if ( ! $category ) {
    $created = wp_insert_term(
        'Carnes',
        'category',
        array(
            'slug'        => 'carnes',
            'description' => 'Guías sobre carne: tipos, calidad, conservación, cocina y nutrición práctica.',
        )
    );

    if ( is_wp_error( $created ) ) {
        fwrite( STDERR, 'Could not create category: ' . $created->get_error_message() . "\n" );
        exit( 1 );
    }

    $category_id = (int) $created['term_id'];
} else {
    $category_id = (int) $category->term_id;
}

$admins = get_users(
    array(
        'role'   => 'administrator',
        'number' => 1,
        'fields' => 'ID',
    )
);
$author_id = ! empty( $admins ) ? (int) $admins[0] : 1;

$existing = get_page_by_path( $slug, OBJECT, 'post' );

$post_data = array(
    'post_title'    => $title,
    'post_name'     => $slug,
    'post_excerpt'  => $excerpt,
    'post_content'  => $content,
    'post_status'   => 'publish',
    'post_type'     => 'post',
    'post_author'   => $author_id,
    'post_category' => array( $category_id ),
    'comment_status'=> 'closed',
);

if ( $existing instanceof WP_Post ) {
    $post_data['ID'] = $existing->ID;
    $post_id = wp_update_post( wp_slash( $post_data ), true );
    $action  = 'updated';
} else {
    $post_id = wp_insert_post( wp_slash( $post_data ), true );
    $action  = 'created';
}

if ( is_wp_error( $post_id ) ) {
    fwrite( STDERR, 'Could not publish article: ' . $post_id->get_error_message() . "\n" );
    exit( 1 );
}

wp_set_post_tags(
    $post_id,
    array( 'carne', 'cocina', 'sartén', 'dorar carne', 'técnicas de cocina' ),
    false
);

update_post_meta( $post_id, '_food_reference_article', '1' );
update_post_meta( $post_id, '_food_reference_purpose', 'Modelo editorial SEO para futuros artículos FOOD' );

clean_post_cache( $post_id );

$permalink = get_permalink( $post_id );
echo "REFERENCE_ARTICLE_ID={$post_id}\n";
echo "REFERENCE_ARTICLE_ACTION={$action}\n";
echo "REFERENCE_ARTICLE_URL={$permalink}\n";
