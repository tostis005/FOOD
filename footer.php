<?php
$food_english = function_exists( 'food_is_english' ) && food_is_english();
?>
</main>

<footer class="site-footer">
	<div class="container footer-main footer-main-v5">
		<div>
			<div class="pometum-footer-brand"><?php if ( function_exists( 'food_pometum_logo' ) ) { food_pometum_logo( 'is-footer' ); } else { echo esc_html( get_bloginfo( 'name' ) ?: 'Pometum' ); } ?></div>
			<p class="footer-copy"><?php echo esc_html( $food_english ? 'Clear guides for understanding food: how to choose quality, read composition, store it safely and cook with confidence.' : 'Guías claras para conocer mejor los alimentos: cómo elegir calidad, entender su composición, conservarlos con seguridad y cocinarlos con criterio.' ); ?></p>
		</div>
		<nav aria-label="<?php echo esc_attr( $food_english ? 'Footer links' : 'Enlaces del pie' ); ?>">
			<ul class="footer-links-v5">
				<li><a href="<?php echo esc_url( home_url( '/sobre-nosotros/' ) ); ?>"><?php echo esc_html( $food_english ? 'About Pometum' : 'Sobre Pometum' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/metodologia/' ) ); ?>"><?php echo esc_html( $food_english ? 'Methodology' : 'Metodología' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php echo esc_html( $food_english ? 'Contact' : 'Contacto' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>"><?php echo esc_html( $food_english ? 'Privacy' : 'Privacidad' ); ?></a></li>
			</ul>
		</nav>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<span><?php echo esc_html( $food_english ? 'Educational content. Use professional guidance whenever food safety or health risk is involved.' : 'Contenido divulgativo. Consulta fuentes profesionales cuando exista un riesgo para la salud.' ); ?></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
