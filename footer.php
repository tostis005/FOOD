</main>

<footer class="site-footer">
	<div class="container footer-main footer-main-v5">
		<div>
			<div class="footer-brand"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'FOOD' ); ?></div>
			<p class="footer-copy">Información práctica para entender mejor los alimentos: qué son, cómo elegirlos, conservarlos, cocinarlos y cuándo conviene tener precaución.</p>
		</div>
		<nav aria-label="Enlaces del pie">
			<ul class="footer-links-v5">
				<li><a href="<?php echo esc_url( home_url( '/sobre-nosotros/' ) ); ?>">Sobre FOOD</a></li>
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
