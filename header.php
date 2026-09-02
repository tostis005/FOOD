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

<div class="site-topline">
	<div class="container">
		<span>Guías claras sobre lo que comes</span>
		<span class="topline-secondary">Producto · cocina · nutrición · seguridad</span>
	</div>
</div>

<header class="site-header">
	<div class="container header-main">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<div class="site-title"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'FOOD' ); ?></div>
				<div class="site-tagline"><?php bloginfo( 'description' ); ?></div>
			</a>
		</div>

		<button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">Menú</button>
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

		<a class="header-search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar', 'food' ); ?>">⌕</a>
	</div>
</header>

<main id="contenido" class="site-content">
