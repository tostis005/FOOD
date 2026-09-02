<?php
/* Apply the public brand name before WordPress prints title metadata. */
if ( 'Pometum' !== get_option( 'blogname' ) ) {
	update_option( 'blogname', 'Pometum' );
}
if ( 'Guías sobre alimentos, calidad, nutrición y cocina' !== get_option( 'blogdescription' ) ) {
	update_option( 'blogdescription', 'Guías sobre alimentos, calidad, nutrición y cocina' );
}

if ( ! function_exists( 'food_pometum_logo' ) ) {
	function food_pometum_logo( $class = '' ) {
		$class_attr = $class ? ' ' . sanitize_html_class( $class ) : '';
		?>
		<span class="pometum-logo<?php echo esc_attr( $class_attr ); ?>">
			<span class="pometum-wordmark" aria-hidden="true"><span>p</span><span class="pometum-o">o</span><span>metum</span></span>
			<span class="screen-reader-text">Pometum</span>
		</span>
		<?php
	}
}

if ( ! function_exists( 'food_pommelo_logo' ) ) {
	function food_pommelo_logo( $class = '' ) {
		food_pometum_logo( $class );
	}
}

$food_english  = function_exists( 'food_is_english' ) && food_is_english();
$food_home_url = function_exists( 'food_language_home_url' ) ? food_language_home_url() : home_url( '/' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<?php
	$css_files = array(
		'food-v4.css',
		'food-v5.css',
		'food-v6.css',
		'food-v6-icons.css',
		'pommelo-v1.css',
		'pommelo-v2.css',
		'pommelo-v3.css',
		'pommelo-v4.css',
		'pommelo-v5-icons.css',
		'pommelo-v6-artwork.css',
		'pommelo-v6-optical-tune.css',
		'pometum-v1.css',
		'pometum-v2.css',
	);
	foreach ( $css_files as $css_file ) :
		$css_path = get_template_directory() . '/assets/css/' . $css_file;
		if ( ! file_exists( $css_path ) ) {
			continue;
		}
		?>
		<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/' . $css_file . '?ver=' . filemtime( $css_path ) ); ?>">
	<?php endforeach; ?>
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cellipse cx='23' cy='25' rx='14' ry='16' fill='none' stroke='%23394536' stroke-width='5'/%3E%3Cpath d='M32 9c4-4 8-4 11-1-2 5-6 7-11 6' fill='%23D96C55'/%3E%3C/svg%3E">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#contenido"><?php echo esc_html( $food_english ? 'Skip to content' : 'Saltar al contenido' ); ?></a>

<header class="site-header">
	<div class="container header-main">
		<div class="site-branding">
			<a href="<?php echo esc_url( $food_home_url ); ?>" rel="home" aria-label="Pometum">
				<?php food_pometum_logo(); ?>
				<div class="site-tagline"><?php echo esc_html( $food_english ? 'Food · quality · cooking' : 'Alimentos · calidad · cocina' ); ?></div>
			</a>
		</div>

		<nav class="primary-nav" id="primary-menu" aria-label="<?php echo esc_attr( $food_english ? 'Main menu' : 'Menú principal' ); ?>">
			<?php
			if ( $food_english && function_exists( 'food_language_nav_fallback' ) ) {
				food_language_nav_fallback();
			} else {
				wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => function_exists( 'food_language_nav_fallback' ) ? 'food_language_nav_fallback' : 'food_primary_nav_fallback' ) );
			}
			?>
		</nav>

		<div class="header-actions">
			<?php if ( function_exists( 'food_language_switcher' ) ) { food_language_switcher(); } ?>
			<a class="header-search" href="<?php echo esc_url( add_query_arg( 's', '', $food_home_url ) ); ?>" aria-label="<?php echo esc_attr( $food_english ? 'Search Pometum' : 'Buscar en Pometum' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg>
			</a>
			<button class="menu-toggle" type="button" aria-controls="mobile-menu-overlay" aria-expanded="false" aria-label="<?php echo esc_attr( $food_english ? 'Open menu' : 'Abrir menú' ); ?>">
				<span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span><span></span></span><span class="menu-toggle-label"><?php echo esc_html( $food_english ? 'Menu' : 'Menú' ); ?></span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu-overlay" id="mobile-menu-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( $food_english ? 'Pometum menu' : 'Menú de Pometum' ); ?>">
	<div class="mobile-menu-shell">
		<div class="mobile-menu-top">
			<a class="mobile-brand" href="<?php echo esc_url( $food_home_url ); ?>" rel="home" aria-label="Pometum"><?php food_pometum_logo( 'is-mobile' ); ?></a>
			<button class="mobile-menu-close" type="button" aria-label="<?php echo esc_attr( $food_english ? 'Close menu' : 'Cerrar menú' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"></path></svg>
			</button>
		</div>

		<div class="mobile-menu-content">
			<div class="mobile-menu-eyebrow"><?php echo esc_html( $food_english ? 'Know your food better' : 'Conoce mejor lo que comes' ); ?></div>
			<nav class="mobile-primary-nav" aria-label="<?php echo esc_attr( $food_english ? 'Mobile menu' : 'Menú móvil' ); ?>">
				<?php
				if ( $food_english && function_exists( 'food_language_nav_fallback' ) ) {
					food_language_nav_fallback();
				} else {
					wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => function_exists( 'food_language_nav_fallback' ) ? 'food_language_nav_fallback' : 'food_primary_nav_fallback' ) );
				}
				?>
			</nav>

			<div class="mobile-menu-explore">
				<span class="mobile-menu-explore-title"><?php echo esc_html( $food_english ? 'Explore by food' : 'Explora por alimento' ); ?></span>
				<div class="mobile-food-links">
					<?php foreach ( food_family_definitions() as $slug => $family ) : ?>
						<a href="<?php echo esc_url( food_category_url( $slug, $family['name'] ) ); ?>"><?php echo esc_html( function_exists( 'food_family_display' ) ? food_family_display( $slug ) : $family['name'] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>

			<form class="mobile-menu-search" role="search" method="get" action="<?php echo esc_url( $food_home_url ); ?>">
				<label class="screen-reader-text" for="mobile-food-search"><?php echo esc_html( $food_english ? 'Search Pometum' : 'Buscar en Pometum' ); ?></label>
				<input id="mobile-food-search" type="search" name="s" placeholder="<?php echo esc_attr( $food_english ? 'What do you want to know?' : '¿Qué quieres saber?' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit"><?php echo esc_html( $food_english ? 'Search' : 'Buscar' ); ?></button>
			</form>
			<p class="mobile-menu-note"><?php echo esc_html( $food_english ? 'Clear guides on food, quality, nutrition, safety, storage and cooking.' : 'Guías claras sobre alimentos, calidad, nutrición, seguridad, conservación y cocina.' ); ?></p>
		</div>
	</div>
</div>

<main id="contenido" class="site-content">
