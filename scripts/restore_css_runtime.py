from pathlib import Path

functions = Path('functions.php')
text = functions.read_text(encoding='utf-8')
old = """function food_enqueue_assets() {
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
new = """function food_enqueue_assets() {
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
if old not in text:
    raise SystemExit('Current consolidated enqueue block not found')
functions.write_text(text.replace(old, new, 1), encoding='utf-8')

header = Path('header.php')
text = header.read_text(encoding='utf-8')
needle = "\t<?php wp_head(); ?>\n"
css_block = """\t<?php wp_head(); ?>
\t<?php
\t$css_files = array(
\t\t'food-v4.css',
\t\t'food-v5.css',
\t\t'food-v6.css',
\t\t'food-v6-icons.css',
\t\t'pommelo-v1.css',
\t\t'pommelo-v2.css',
\t\t'pommelo-v3.css',
\t\t'pommelo-v4.css',
\t\t'pommelo-v5-icons.css',
\t\t'pommelo-v6-artwork.css',
\t\t'pommelo-v6-optical-tune.css',
\t\t'pometum-v1.css',
\t\t'pometum-v2.css',
\t\t'pometum-v3.css',
\t\t'pometum-v4.css',
\t\t'pometum-v5-mobile-centering.css',
\t\t'pometum-v6-ui.css',
\t\t'pometum-v7-polish.css',
\t);
\tforeach ( $css_files as $css_file ) :
\t\t$css_path = get_template_directory() . '/assets/css/' . $css_file;
\t\tif ( ! file_exists( $css_path ) ) {
\t\t\tcontinue;
\t\t}
\t\t?>
\t\t<link rel=\"stylesheet\" href=\"<?php echo esc_url( get_template_directory_uri() . '/assets/css/' . $css_file . '?ver=' . filemtime( $css_path ) ); ?>\">
\t<?php endforeach; ?>
"""
if '$css_files = array(' not in text:
    if needle not in text:
        raise SystemExit('wp_head marker not found')
    text = text.replace(needle, css_block, 1)
header.write_text(text, encoding='utf-8')

single = Path('single.php')
text = single.read_text(encoding='utf-8')
old_start = "<?php\nget_header();\n?>"
new_start = """<?php
$food_article_layout_css = get_template_directory() . '/assets/css/article-layout-v2.css';
if ( file_exists( $food_article_layout_css ) ) {
\twp_enqueue_style(
\t\t'food-article-layout-v2',
\t\tget_template_directory_uri() . '/assets/css/article-layout-v2.css',
\t\tarray( 'food-style' ),
\t\t(string) filemtime( $food_article_layout_css )
\t);
}
get_header();
?>"""
if old_start not in text:
    raise SystemExit('single.php header block not found')
single.write_text(text.replace(old_start, new_start, 1), encoding='utf-8')
