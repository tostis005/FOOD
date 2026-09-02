<?php
/* Apply the public brand name before WordPress prints title metadata. */
if ( 'Pommelo' !== get_option( 'blogname' ) ) {
	update_option( 'blogname', 'Pommelo' );
}
if ( 'Guías sobre alimentos, nutrición y cocina' !== get_option( 'blogdescription' ) ) {
	update_option( 'blogdescription', 'Guías sobre alimentos, nutrición y cocina' );
}

if ( ! function_exists( 'food_pommelo_logo' ) ) {
	function food_pommelo_logo( $class = '' ) {
		$class_attr = $class ? ' ' . sanitize_html_class( $class ) : '';
		?>
		<span class="pommelo-logo<?php echo esc_attr( $class_attr ); ?>">
			<svg class="pommelo-logo-mark" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
				<circle cx="22" cy="25" r="17" fill="#ef7865"/>
				<circle cx="22" cy="25" r="13" fill="#fff0df"/>
				<circle cx="22" cy="25" r="10.6" fill="#ef7865"/>
				<g stroke="#fff0df" stroke-width="2.1" stroke-linecap="round">
					<path d="M22 25V14.4"/><path d="m22 25 9.2-5.3"/><path d="m22 25 9.2 5.3"/>
					<path d="M22 25v10.6"/><path d="m22 25-9.2 5.3"/><path d="m22 25-9.2-5.3"/>
				</g>
				<circle cx="22" cy="25" r="2.2" fill="#fff0df"/>
				<path d="M31.5 9.5c3.2-4.3 7.2-5.7 11.7-4.4-.8 5-4.2 8.1-10.2 9.1" fill="#64805f"/>
			</svg>
			<span class="pommelo-wordmark">Po<span class="pommelo-double">mm</span>elo</span>
		</span>
		<?php
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
	$food_v4_css       = get_template_directory() . '/assets/css/food-v4.css';
	$food_v5_css       = get_template_directory() . '/assets/css/food-v5.css';
	$food_v6_css       = get_template_directory() . '/assets/css/food-v6.css';
	$food_v6_icons_css = get_template_directory() . '/assets/css/food-v6-icons.css';
	$pommelo_css       = get_template_directory() . '/assets/css/pommelo-v1.css';
	$pommelo_v2_css    = get_template_directory() . '/assets/css/pommelo-v2.css';
	$pommelo_v3_css    = get_template_directory() . '/assets/css/pommelo-v3.css';
	$pommelo_v4_css    = get_template_directory() . '/assets/css/pommelo-v4.css';
	?>
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v4.css?ver=' . ( file_exists( $food_v4_css ) ? filemtime( $food_v4_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v5.css?ver=' . ( file_exists( $food_v5_css ) ? filemtime( $food_v5_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v6.css?ver=' . ( file_exists( $food_v6_css ) ? filemtime( $food_v6_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/food-v6-icons.css?ver=' . ( file_exists( $food_v6_icons_css ) ? filemtime( $food_v6_icons_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v1.css?ver=' . ( file_exists( $pommelo_css ) ? filemtime( $pommelo_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v2.css?ver=' . ( file_exists( $pommelo_v2_css ) ? filemtime( $pommelo_v2_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v3.css?ver=' . ( file_exists( $pommelo_v3_css ) ? filemtime( $pommelo_v3_css ) : '1' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/pommelo-v4.css?ver=' . ( file_exists( $pommelo_v4_css ) ? filemtime( $pommelo_v4_css ) : '1' ) ); ?>">
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Ccircle cx='22' cy='25' r='17' fill='%23ef7865'/%3E%3Ccircle cx='22' cy='25' r='13' fill='%23fff0df'/%3E%3Ccircle cx='22' cy='25' r='10.6' fill='%23ef7865'/%3E%3Cg stroke='%23fff0df' stroke-width='2.1'%3E%3Cpath d='M22 25V14.4'/%3E%3Cpath d='m22 25 9.2-5.3'/%3E%3Cpath d='m22 25 9.2 5.3'/%3E%3Cpath d='M22 25v10.6'/%3E%3Cpath d='m22 25-9.2 5.3'/%3E%3Cpath d='m22 25-9.2-5.3'/%3E%3C/g%3E%3Cpath d='M31.5 9.5c3.2-4.3 7.2-5.7 11.7-4.4-.8 5-4.2 8.1-10.2 9.1' fill='%2364805f'/%3E%3C/svg%3E">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'food' ); ?></a>

<header class="site-header">
	<div class="container header-main">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Pommelo, inicio">
				<?php food_pommelo_logo(); ?>
				<div class="site-tagline">Alimentos · nutrición · cocina</div>
			</a>
		</div>

		<nav class="primary-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Menú principal', 'food' ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'food_primary_nav_fallback' ) ); ?>
		</nav>

		<div class="header-actions">
			<a class="header-search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar en Pommelo', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg>
			</a>
			<button class="menu-toggle" type="button" aria-controls="mobile-menu-overlay" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menú', 'food' ); ?>">
				<span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span><span></span></span><span class="menu-toggle-label">Menú</span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu-overlay" id="mobile-menu-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Menú de Pommelo">
	<div class="mobile-menu-shell">
		<div class="mobile-menu-top">
			<a class="mobile-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Pommelo, inicio"><?php food_pommelo_logo( 'is-mobile' ); ?></a>
			<button class="mobile-menu-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar menú', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"></path></svg>
			</button>
		</div>

		<div class="mobile-menu-content">
			<div class="mobile-menu-eyebrow">Encuentra lo que buscas</div>
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
				<label class="screen-reader-text" for="mobile-food-search">Buscar en Pommelo</label>
				<input id="mobile-food-search" type="search" name="s" placeholder="¿Qué quieres saber?" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar</button>
			</form>
			<p class="mobile-menu-note">Información práctica sobre alimentos, nutrición, seguridad, conservación, calidad y cocina.</p>
		</div>
	</div>
</div>

<main id="contenido" class="site-content">
