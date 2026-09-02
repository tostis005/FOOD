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

/* Backward-compatible alias for older templates while the visual layer settles. */
if ( ! function_exists( 'food_pommelo_logo' ) ) {
	function food_pommelo_logo( $class = '' ) {
		food_pometum_logo( $class );
	}
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<?php
	$food_v4_css              = get_template_directory() . '/assets/css/food-v4.css';
	$food_v5_css              = get_template_directory() . '/assets/css/food-v5.css';
	$food_v6_css              = get_template_directory() . '/assets/css/food-v6.css';
	$food_v6_icons_css        = get_template_directory() . '/assets/css/food-v6-icons.css';
	$pommelo_css              = get_template_directory() . '/assets/css/pommelo-v1.css';
	$pommelo_v2_css           = get_template_directory() . '/assets/css/pommelo-v2.css';
	$pommelo_v3_css           = get_template_directory() . '/assets/css/pommelo-v3.css';
	$pommelo_v4_css           = get_template_directory() . '/assets/css/pommelo-v4.css';
	$pommelo_v5_icons_css     = get_template_directory() . '/assets/css/pommelo-v5-icons.css';
	$pommelo_v6_artwork_css   = get_template_directory() . '/assets/css/pommelo-v6-artwork.css';
	$pommelo_v6_optical_css   = get_template_directory() . '/assets/css/pommelo-v6-optical-tune.css';
	$pometum_v1_css           = get_template_directory() . '/assets/css/pometum-v1.css';
	?>
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v4.css?ver=' . ( file_exists( $food_v4_css ) ? filemtime( $food_v4_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v5.css?ver=' . ( file_exists( $food_v5_css ) ? filemtime( $food_v5_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v6.css?ver=' . ( file_exists( $food_v6_css ) ? filemtime( $food_v6_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v6-icons.css?ver=' . ( file_exists( $food_v6_icons_css ) ? filemtime( $food_v6_icons_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v1.css?ver=' . ( file_exists( $pommelo_css ) ? filemtime( $pommelo_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v2.css?ver=' . ( file_exists( $pommelo_v2_css ) ? filemtime( $pommelo_v2_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v3.css?ver=' . ( file_exists( $pommelo_v3_css ) ? filemtime( $pommelo_v3_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v4.css?ver=' . ( file_exists( $pommelo_v4_css ) ? filemtime( $pommelo_v4_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v5-icons.css?ver=' . ( file_exists( $pommelo_v5_icons_css ) ? filemtime( $pommelo_v5_icons_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v6-artwork.css?ver=' . ( file_exists( $pommelo_v6_artwork_css ) ? filemtime( $pommelo_v6_artwork_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v6-optical-tune.css?ver=' . ( file_exists( $pommelo_v6_optical_css ) ? filemtime( $pommelo_v6_optical_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pometum-v1.css?ver=' . ( file_exists( $pometum_v1_css ) ? filemtime( $pometum_v1_css ) : '1' ) ); ?>">
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cellipse cx='23' cy='25' rx='14' ry='16' fill='none' stroke='%23394536' stroke-width='5'/%3E%3Cpath d='M32 9c4-4 8-4 11-1-2 5-6 7-11 6' fill='%23D96C55'/%3E%3C/svg%3E">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'food' ); ?></a>

<header class="site-header">
	<div class="container header-main">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Pometum, inicio">
				<?php food_pometum_logo(); ?>
				<div class="site-tagline">Alimentos · calidad · cocina</div>
			</a>
		</div>

		<nav class="primary-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Menú principal', 'food' ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'food_primary_nav_fallback' ) ); ?>
		</nav>

		<div class="header-actions">
			<a class="header-search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar en Pometum', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg>
			</a>
			<button class="menu-toggle" type="button" aria-controls="mobile-menu-overlay" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menú', 'food' ); ?>">
				<span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span><span></span></span><span class="menu-toggle-label">Menú</span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu-overlay" id="mobile-menu-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Menú de Pometum">
	<div class="mobile-menu-shell">
		<div class="mobile-menu-top">
			<a class="mobile-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Pometum, inicio"><?php food_pometum_logo( 'is-mobile' ); ?></a>
			<button class="mobile-menu-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar menú', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"></path></svg>
			</button>
		</div>

		<div class="mobile-menu-content">
			<div class="mobile-menu-eyebrow">Conoce mejor lo que comes</div>
			<nav class="mobile-primary-nav" aria-label="<?php esc_attr_e( 'Menú móvil', 'food' ); ?>">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'food_primary_nav_fallback' ) ); ?>
			</nav>

			<div class="mobile-menu-explore">
				<span class="mobile-menu-explore-title">Explora por alimento</span>
				<div class="mobile-food-links">
					<?php foreach ( food_family_definitions() as $slug => $family ) : ?>
						<a href="<?php echo esc_url( food_category_url( $slug, $family['name'] ) ); ?>"><?php echo esc_html( $family['name'] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>

			<form class="mobile-menu-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="mobile-food-search">Buscar en Pometum</label>
				<input id="mobile-food-search" type="search" name="s" placeholder="¿Qué quieres saber?" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar</button>
			</form>
			<p class="mobile-menu-note">Guías claras sobre alimentos, calidad, nutrición, seguridad, conservación y cocina.</p>
		</div>
	</div>
</div>

<main id="contenido" class="site-content">
