from pathlib import Path

functions = Path("functions.php")
functions_text = functions.read_text(encoding="utf-8")
old_enqueue = """function food_enqueue_assets() {
\t$version = wp_get_theme()->get( 'Version' );
\twp_enqueue_style( 'food-style', get_stylesheet_uri(), array(), $version );

\t$editorial_css = get_template_directory() . '/assets/css/editorial.css';
\tif ( file_exists( $editorial_css ) ) {
\t\twp_enqueue_style(
\t\t\t'food-editorial',
\t\t\tget_template_directory_uri() . '/assets/css/editorial.css',
\t\t\tarray( 'food-style' ),
\t\t\t(string) filemtime( $editorial_css )
\t\t);
\t}

\twp_enqueue_script( 'food-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
}
"""
new_enqueue = """function food_enqueue_assets() {
\t$version          = wp_get_theme()->get( 'Version' );
\t$consolidated_css = get_template_directory() . '/assets/css/quinnoa.css';

\tif ( file_exists( $consolidated_css ) ) {
\t\twp_enqueue_style(
\t\t\t'food-style',
\t\t\tget_template_directory_uri() . '/assets/css/quinnoa.css',
\t\t\tarray(),
\t\t\t(string) filemtime( $consolidated_css )
\t\t);
\t}

\twp_enqueue_script( 'food-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
}
"""
if old_enqueue not in functions_text:
    raise SystemExit("Expected enqueue function was not found")
functions.write_text(functions_text.replace(old_enqueue, new_enqueue, 1), encoding="utf-8")

header = Path("header.php")
header_text = header.read_text(encoding="utf-8")
old_head_css = """\t<?php
\tremove_action( 'wp_enqueue_scripts', 'food_enqueue_assets' );
\twp_dequeue_style( 'food-style' );
\twp_dequeue_style( 'food-editorial' );
\twp_dequeue_style( 'food-article-layout-v2' );
\twp_head();
\t$food_consolidated_css = get_template_directory() . '/assets/css/quinnoa.css';
\t?>
\t<?php if ( file_exists( $food_consolidated_css ) ) : ?>
\t\t<link rel=\"stylesheet\" href=\"<?php echo esc_url( get_template_directory_uri() . '/assets/css/quinnoa.css?ver=' . filemtime( $food_consolidated_css ) ); ?>\">
\t<?php endif; ?>"""
new_head_css = "\t<?php wp_head(); ?>"
if old_head_css not in header_text:
    raise SystemExit("Expected temporary consolidated CSS header block was not found")
header.write_text(header_text.replace(old_head_css, new_head_css, 1), encoding="utf-8")
