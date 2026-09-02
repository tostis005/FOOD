<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'food' ); ?></a>

<header class="site-header">
	<div class="container header-main">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<div class="site-title"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'FOOD' ); ?></div>
				<div class="site-tagline">Guía práctica de alimentación</div>
			</a>
		</div>

		<nav class="primary-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Menú principal', 'food' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'food_category_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="header-search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar en FOOD', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
					<circle cx="11" cy="11" r="6.5"></circle>
					<path d="m16 16 4 4"></path>
				</svg>
			</a>

			<button class="menu-toggle" type="button" aria-controls="mobile-menu-overlay" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menú', 'food' ); ?>">
				<span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span><span></span></span>
				<span class="menu-toggle-label">Menú</span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu-overlay" id="mobile-menu-overlay" aria-hidden="true">
	<div class="mobile-menu-shell">
		<div class="mobile-menu-top">
			<a class="mobile-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<strong><?php echo esc_html( get_bloginfo( 'name' ) ?: 'FOOD' ); ?></strong>
				<span>Guía práctica de alimentación</span>
			</a>
			<button class="mobile-menu-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar menú', 'food' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
					<path d="M6 6l12 12M18 6 6 18"></path>
				</svg>
			</button>
		</div>

		<div class="mobile-menu-content">
			<div class="mobile-menu-eyebrow">Secciones</div>
			<nav class="mobile-primary-nav" aria-label="<?php esc_attr_e( 'Menú móvil', 'food' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'fallback_cb'    => 'food_category_fallback',
					)
				);
				?>
			</nav>

			<div class="mobile-menu-explore">
				<span class="mobile-menu-explore-title">Por alimento</span>
				<div class="mobile-food-links">
					<a href="<?php echo esc_url( food_category_url( 'carnes', 'Carnes' ) ); ?>">🥩 Carnes</a>
					<a href="<?php echo esc_url( food_category_url( 'pescados-mariscos', 'Pescados y mariscos' ) ); ?>">🐟 Pescados</a>
					<a href="<?php echo esc_url( food_category_url( 'jamon-embutidos', 'Jamón y embutidos' ) ); ?>">🍖 Jamón</a>
					<a href="<?php echo esc_url( food_category_url( 'quesos-lacteos', 'Quesos y lácteos' ) ); ?>">🧀 Quesos</a>
					<a href="<?php echo esc_url( food_category_url( 'aceites', 'Aceites' ) ); ?>">🫒 Aceites</a>
					<a href="<?php echo esc_url( food_category_url( 'legumbres', 'Legumbres' ) ); ?>">🫘 Legumbres</a>
					<a href="<?php echo esc_url( food_category_url( 'frutas', 'Frutas' ) ); ?>">🍎 Frutas</a>
					<a href="<?php echo esc_url( food_category_url( 'verduras-hortalizas', 'Verduras y hortalizas' ) ); ?>">🥬 Verduras</a>
				</div>
			</div>

			<form class="mobile-menu-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="mobile-food-search">Buscar en FOOD</label>
				<input id="mobile-food-search" type="search" name="s" placeholder="¿Qué quieres saber?" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar</button>
			</form>

			<p class="mobile-menu-note">Respuestas claras sobre alimentos, cocina, nutrición y seguridad alimentaria.</p>
		</div>
	</div>
</div>

<main id="contenido" class="site-content">
