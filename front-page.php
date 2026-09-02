<?php get_header(); ?>

<section class="hero hero-editorial">
	<div class="container hero-grid">
		<div class="hero-copy">
			<div class="eyebrow">Entender la comida cambia cómo comes</div>
			<h1>Conoce lo que comes. Come mejor.</h1>
			<p>Guías claras sobre alimentos, seguridad alimentaria, nutrición, cocina, origen y calidad. Respuestas útiles para las dudas que aparecen antes de comprar, cocinar o comer.</p>
			<form class="hero-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="food-search">Buscar</label>
				<input id="food-search" type="search" name="s" placeholder="Ej.: ¿por qué amarga el aceite de oliva?" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar respuesta</button>
			</form>
			<div class="hero-prompts" aria-label="Búsquedas populares">
				<span>Prueba con:</span>
				<a href="<?php echo esc_url( home_url( '/?s=patata+verde' ) ); ?>">patata verde</a>
				<a href="<?php echo esc_url( home_url( '/?s=aceite+amargo' ) ); ?>">aceite amargo</a>
				<a href="<?php echo esc_url( home_url( '/?s=proteina+carne' ) ); ?>">proteína en carnes</a>
			</div>
		</div>

		<a class="hero-visual hero-feature" href="<?php echo esc_url( food_post_url_by_slug( 'por-que-la-carne-suelta-agua-en-la-sarten', 'carne suelta agua sartén' ) ); ?>">
			<div class="hero-feature-mark">FOOD explica</div>
			<div class="hero-label">
				<span>Cocina · guía práctica</span>
				<strong>¿Por qué suelta agua la carne en la sartén?</strong>
				<em>Entenderlo es la clave para conseguir un mejor dorado →</em>
			</div>
		</a>
	</div>
</section>

<section class="section food-departments">
	<div class="container">
		<div class="section-head editorial-section-head">
			<div>
				<div class="eyebrow">Explora por alimento</div>
				<h2>Empieza por lo que tienes delante</h2>
			</div>
			<p>Una estructura pensada para crecer: cada familia reúne dudas sobre calidad, conservación, cocina, nutrición y características del producto.</p>
		</div>

		<div class="department-grid">
			<?php
			$departments = array(
				array( 'Carnes', 'carnes', '🥩', 'Cortes, conservación, cocción y calidad.' ),
				array( 'Pescados y mariscos', 'pescados-mariscos', '🐟', 'Frescura, especies, seguridad y cocina.' ),
				array( 'Jamón y embutidos', 'jamon-embutidos', '🍖', 'Jamón, paleta, curados, origen y calidad.' ),
				array( 'Quesos y lácteos', 'quesos-lacteos', '🧀', 'Variedades, conservación, usos y calidad.' ),
				array( 'Aceites', 'aceites', '🫒', 'Sabor, usos, conservación y aceite de oliva.' ),
				array( 'Legumbres', 'legumbres', '🫘', 'Tipos, remojo, cocción y nutrición.' ),
				array( 'Frutas', 'frutas', '🍎', 'Maduración, temporada y conservación.' ),
				array( 'Verduras y hortalizas', 'verduras-hortalizas', '🥬', 'Estado, temporada, cocina y conservación.' ),
				array( 'Cereales, pan y pasta', 'cereales-pan-pasta', '🌾', 'Arroz, panes, cereales y pastas.' ),
				array( 'Huevos', 'huevos', '🥚', 'Etiquetado, seguridad, conservación y cocina.' ),
			);

			foreach ( $departments as $department ) : ?>
				<a class="department-card" href="<?php echo esc_url( food_category_url( $department[1], $department[0] ) ); ?>">
					<span class="department-icon" aria-hidden="true"><?php echo esc_html( $department[2] ); ?></span>
					<span class="department-copy">
						<strong><?php echo esc_html( $department[0] ); ?></strong>
						<small><?php echo esc_html( $department[3] ); ?></small>
					</span>
					<span class="department-arrow" aria-hidden="true">→</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section intent-section">
	<div class="container">
		<div class="section-head editorial-section-head">
			<div>
				<div class="eyebrow">Explora por la duda que quieres resolver</div>
				<h2>No todo empieza por un ingrediente</h2>
			</div>
			<p>Estas áreas agrupan búsquedas transversales: saber si algo se puede comer, comparar nutrientes, entender una técnica o reconocer un producto de calidad.</p>
		</div>

		<div class="intent-grid">
			<a class="intent-card intent-safety" href="<?php echo esc_url( food_category_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ); ?>">
				<span class="intent-number">01</span><strong>Seguridad alimentaria</strong><p>¿Se puede comer? ¿Está en mal estado? ¿Cómo lo conservo?</p><span>Ver respuestas →</span>
			</a>
			<a class="intent-card" href="<?php echo esc_url( food_category_url( 'nutricion', 'Nutrición' ) ); ?>">
				<span class="intent-number">02</span><strong>Nutrición</strong><p>Proteína, grasa, fibra y comparativas para entender mejor lo que comes.</p><span>Ver guías →</span>
			</a>
			<a class="intent-card" href="<?php echo esc_url( food_category_url( 'cocina', 'Cocina' ) ); ?>">
				<span class="intent-number">03</span><strong>Cocina</strong><p>Por qué ocurren las cosas en la sartén, el horno o la olla y cómo mejorarlas.</p><span>Aprender →</span>
			</a>
			<a class="intent-card" href="<?php echo esc_url( food_category_url( 'platos-menus', 'Platos y menús' ) ); ?>">
				<span class="intent-number">04</span><strong>Platos y menús</strong><p>Cómo combinar alimentos y construir comidas cotidianas completas y equilibradas.</p><span>Explorar →</span>
			</a>
			<a class="intent-card" href="<?php echo esc_url( food_category_url( 'origen-calidad', 'Origen y calidad' ) ); ?>">
				<span class="intent-number">05</span><strong>Origen y calidad</strong><p>DOP, sellos, etiquetado, procedencia y pistas para elegir mejor.</p><span>Entender →</span>
			</a>
		</div>
	</div>
</section>

<section class="section question-section">
	<div class="container">
		<div class="section-head">
			<div><div class="eyebrow">Resolver dudas</div><h2>Preguntas que nos hacemos todos</h2></div>
			<p>Consultas concretas, respuestas rápidas y explicaciones suficientemente profundas para tomar una decisión.</p>
		</div>
		<div class="quick-grid">
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=patata+verde' ) ); ?>"><span class="quick-icon">🥔</span><span><strong>¿Una patata verde se puede comer?</strong><br><span>Qué significa el color verde y cuándo conviene descartarla.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( food_post_url_by_slug( 'por-que-la-carne-suelta-agua-en-la-sarten', 'carne agua sartén' ) ); ?>"><span class="quick-icon">🥩</span><span><strong>¿Por qué la carne suelta agua?</strong><br><span>Temperatura, cantidad y cómo conseguir un buen dorado.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=jamon+denominacion+origen' ) ); ?>"><span class="quick-icon">🍖</span><span><strong>Denominaciones de origen del jamón</strong><br><span>Qué significan y cómo reconocerlas.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=carne+proteina+grasa' ) ); ?>"><span class="quick-icon">⚖️</span><span><strong>Carnes con más proteína y menos grasa</strong><br><span>Comparativa práctica para entender sus diferencias.</span></span><span class="quick-arrow">→</span></a>
		</div>
	</div>
</section>

<?php if ( is_active_sidebar( 'home-ad' ) ) : ?>
	<div class="container ad-slot"><?php dynamic_sidebar( 'home-ad' ); ?></div>
<?php else : ?>
	<div class="container ad-slot">Espacio preparado para publicidad / AdSense</div>
<?php endif; ?>

<section class="section latest-guides">
	<div class="container">
		<div class="section-head">
			<div><div class="eyebrow">Guías FOOD</div><h2>Para elegir, conservar, cocinar y comer mejor</h2></div>
			<a class="section-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">Ver todos los artículos →</a>
		</div>
		<div class="card-grid">
			<?php
			$food_latest = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6, 'ignore_sticky_posts' => false ) );
			if ( $food_latest->have_posts() ) :
				while ( $food_latest->have_posts() ) : $food_latest->the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				wp_reset_postdata();
			else :
				for ( $i = 0; $i < 3; $i++ ) : ?>
					<article class="post-card"><div class="card-media"><div class="card-placeholder"></div></div><div class="card-body"><div class="card-kicker">Guía FOOD</div><h2 class="card-title">Aquí aparecerán tus artículos</h2><p class="card-excerpt">Cada nueva guía se integrará automáticamente dentro de su categoría y en esta portada.</p></div></article>
				<?php endfor;
			endif;
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
