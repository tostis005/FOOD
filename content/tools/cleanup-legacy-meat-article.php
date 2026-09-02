<?php
/** One-off cleanup for the pre-JSON reference article duplicated by article #10. */

if ( PHP_SAPI !== 'cli' ) {
    fwrite( STDERR, "CLI only.\n" );
    exit( 1 );
}

if ( $argc < 2 ) {
    fwrite( STDERR, "Usage: php cleanup-legacy-meat-article.php <wp-root>\n" );
    exit( 1 );
}

$wp_root = rtrim( $argv[1], '/' );
$wp_load = $wp_root . '/wp-load.php';
if ( ! is_readable( $wp_load ) ) {
    fwrite( STDERR, "Cannot read {$wp_load}\n" );
    exit( 1 );
}

require_once $wp_load;

$canonical_ids = get_posts(
    array(
        'post_type'      => 'post',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_food_source_id',
        'meta_value'     => 'es-010-carne-suelta-agua-cocinar',
    )
);

if ( empty( $canonical_ids ) ) {
    fwrite( STDERR, "Canonical JSON article #10 not found.\n" );
    exit( 2 );
}

$canonical_id   = (int) $canonical_ids[0];
$legacy_slug    = 'por-que-la-carne-suelta-agua-en-la-sarten';
$canonical_slug = get_post_field( 'post_name', $canonical_id );
$legacy         = get_page_by_path( $legacy_slug, OBJECT, 'post' );

// WordPress uses _wp_old_slug to redirect requests for previous slugs to the
// current permalink. Store it even if the old reference post has already gone.
$old_slugs = get_post_meta( $canonical_id, '_wp_old_slug', false );
if ( ! in_array( $legacy_slug, $old_slugs, true ) ) {
    add_post_meta( $canonical_id, '_wp_old_slug', $legacy_slug, false );
}

$legacy_id = 0;
if ( $legacy instanceof WP_Post && (int) $legacy->ID !== $canonical_id ) {
    $legacy_id = (int) $legacy->ID;
    wp_trash_post( $legacy_id );
}

clean_post_cache( $canonical_id );

echo "CANONICAL_ID={$canonical_id}\n";
echo "CANONICAL_SLUG={$canonical_slug}\n";
echo "LEGACY_ID={$legacy_id}\n";
echo "LEGACY_SLUG={$legacy_slug}\n";
echo "LEGACY_REDIRECT_REGISTERED=1\n";
