</main>

<footer class="site-footer">
	<div class="container footer-main">
		<div>
			<div class="footer-brand"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'FOOD' ); ?></div>
			<p class="footer-copy">Información práctica para entender mejor los alimentos: cómo elegirlos, conservarlos, cocinarlos y disfrutarlos con criterio.</p>
		</div>
		<div>
			<?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); else : ?>
				<h3 class="widget-title">Explora</h3>
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'fallback_cb' => 'food_category_fallback' ) ); ?>
			<?php endif; ?>
		</div>
		<div>
			<?php if ( is_active_sidebar( 'footer-2' ) ) : dynamic_sidebar( 'footer-2' ); else : ?>
				<h3 class="widget-title">Sobre FOOD</h3>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/sobre-nosotros/' ) ); ?>">Sobre nosotros</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
					<li><a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>">Privacidad</a></li>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<span>Contenido divulgativo. Consulta fuentes profesionales cuando exista un riesgo para la salud.</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
