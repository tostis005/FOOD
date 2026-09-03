<?php
$food_english = function_exists( 'food_is_english' ) && food_is_english();
$food_footer_language = $food_english ? 'en' : 'es';
?>
</main>

<footer class="site-footer">
	<div class="container footer-main footer-main-v5">
		<div>
			<div class="pometum-footer-brand"><?php if ( function_exists( 'food_pometum_logo' ) ) { food_pometum_logo( 'is-footer' ); } else { echo esc_html( get_bloginfo( 'name' ) ?: 'Quinnoa' ); } ?></div>
			<p class="footer-copy"><?php echo esc_html( $food_english ? 'A place to understand food better and discover what lies behind what we eat.' : 'Un espacio para entender mejor los alimentos y descubrir lo que hay detrás de lo que comemos.' ); ?></p>
		</div>
		<nav aria-label="<?php echo esc_attr( $food_english ? 'Footer links' : 'Enlaces del pie' ); ?>">
			<ul class="footer-links-v5">
				<li><a href="<?php echo esc_url( function_exists( 'food_editorial_page_url' ) ? food_editorial_page_url( 'about', $food_footer_language ) : home_url( '/acerca-de/' ) ); ?>"><?php echo esc_html( $food_english ? 'About' : 'Acerca de' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'food_editorial_page_url' ) ? food_editorial_page_url( 'contact', $food_footer_language ) : home_url( '/contacto/' ) ); ?>"><?php echo esc_html( $food_english ? 'Contact' : 'Contacto' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'food_editorial_page_url' ) ? food_editorial_page_url( 'privacy', $food_footer_language ) : home_url( '/privacidad/' ) ); ?>"><?php echo esc_html( $food_english ? 'Privacy' : 'Privacidad' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'food_editorial_page_url' ) ? food_editorial_page_url( 'cookies', $food_footer_language ) : home_url( '/politica-de-cookies/' ) ); ?>"><?php echo esc_html( $food_english ? 'Cookies' : 'Cookies' ); ?></a></li>
			</ul>
		</nav>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<span><?php echo esc_html( $food_english ? 'General educational information. Medical and food-safety advice from qualified professionals and official authorities takes priority.' : 'Información divulgativa de carácter general. En cuestiones médicas y de seguridad alimentaria prevalecen las indicaciones de profesionales cualificados y organismos oficiales.' ); ?></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
