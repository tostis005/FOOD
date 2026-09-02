</main>

<footer class="site-footer">
	<div class="container footer-main footer-main-v5">
		<div>
			<div class="pometum-footer-brand"><?php if ( function_exists( 'food_pometum_logo' ) ) { food_pometum_logo( 'is-footer' ); } else { echo esc_html( get_bloginfo( 'name' ) ?: 'Pometum' ); } ?></div>
			<p class="footer-copy">Guías claras para conocer mejor los alimentos: cómo elegir calidad, entender su composición, conservarlos con seguridad y cocinarlos con criterio.</p>
		</div>
		<nav aria-label="Enlaces del pie">
			<ul class="footer-links-v5">
				<li><a href="<?php echo esc_url( home_url( '/sobre-nosotros/' ) ); ?>">Sobre Pometum</a></li>
				<li><a href="<?php echo esc_url( home_url( '/metodologia/' ) ); ?>">Metodología</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
				<li><a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>">Privacidad</a></li>
			</ul>
		</nav>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<span>Contenido divulgativo. Consulta fuentes profesionales cuando exista un riesgo para la salud.</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
