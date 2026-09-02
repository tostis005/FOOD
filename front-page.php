<?php get_header(); ?>

<section class="home-hero">
	<div class="container home-hero-grid">
		<div class="hero-main">
			<span class="hero-kicker">Comida explicada sin complicaciones</span>
			<h1>Respuestas claras para comer mejor.</h1>
			<p>Entiende lo que compras, cocinas y comes. FOOD reúne guías prácticas sobre alimentos, seguridad, nutrición, cocina, origen y calidad.</p>

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

		<aside class="hero-question">
			<div class="hero-question-top">
				<span>Pregunta destacada</span>
				<span class="hero-question-number">01</span>
			</div>
			<h2>¿Por qué la carne suelta agua en la sartén?</h2>
			<p>La temperatura, la cantidad de carne y su propia humedad explican por qué a veces se cuece en vez de dorarse.</p>
			<a href="<?php echo esc_url( food_post_url_by_slug( 'por-que-la-carne-suelta-agua-en-la-sarten', 'carne suelta agua sartén' ) ); ?>">Leer la explicación →</a>
		</aside>
	</div>
</section>

<section class="section food-index">
	<div class="container">
		<header class="section-intro">
			<div>
				<span class="section-label">Explora por alimento</span>
				<h2>Empieza por lo que tienes delante</h2>
			</div>
			<p>Cada familia reúne preguntas sobre conservación, calidad, cocina, nutrición y características del producto.</p>
		</header>

		<div class="food-index-grid">
			<?php
			$departments = array(
				array( 'Carnes', 'carnes', '🥩', 'Cortes, conservación y cocción.' ),
				array( 'Pescados y mariscos', 'pescados-mariscos', '🐟', 'Frescura, especies y seguridad.' ),
				array( 'Jamón y embutidos', 'jamon-embutidos', '🍖', 'Curados, origen y calidad.' ),
				array( 'Quesos y lácteos', 'quesos-lacteos', '🧀', 'Variedades, usos y conservación.' ),
				array( 'Aceites', 'aceites', '🫒', 'Sabor, calidad y aceite de oliva.' ),
				array( 'Legumbres', 'legumbres', '🫘', 'Remojo, cocción y nutrición.' ),
				array( 'Frutas', 'frutas', '🍎', 'Maduración, temporada y conservación.' ),
				array( 'Verduras y hortalizas', 'verduras-hortalizas', '🥬', 'Estado, temporada y cocina.' ),
				array( 'Cereales, pan y pasta', 'cereales-pan-pasta', '🌾', 'Arroz, panes, cereales y pasta.' ),
				array( 'Huevos', 'huevos', '🥚', 'Etiquetado, seguridad y cocina.' ),
			);

			foreach ( $departments as $department ) : ?>
				<a class="food-index-item" href="<?php echo esc_url( food_category_url( $department[1], $department[0] ) ); ?>">
					<span class="food-index-icon" aria-hidden="true"><?php echo esc_html( $department[2] ); ?></span>
					<span class="food-index-name"><?php echo esc_html( $department[0] ); ?></span>
					<span class="food-index-arrow" aria-hidden="true">→</span>
					<span class="food-index-note"><?php echo esc_html( $department[3] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="needs-section">
	<div class="container needs-layout">
		<div class="needs-intro">
			<span class="section-label">También puedes empezar por tu duda</span>
			<h2>¿Qué quieres resolver?</h2>
			<p>No todas las búsquedas empiezan por un ingrediente. A veces lo importante es saber si algo se puede comer, comparar nutrientes o entender qué ha pasado al cocinar.</p>
		</div>

		<div class="needs-list">
			<a class="need-row" href="<?php echo esc_url( food_category_url( 'seguridad-alimentaria', 'Seguridad alimentaria' ) ); ?>">
				<span class="need-number">01</span><strong class="need-title">Seguridad alimentaria</strong><span class="need-copy">¿Se puede comer? ¿Está en mal estado? ¿Cómo conviene conservarlo?</span><span class="need-arrow">→</span>
			</a>
			<a class="need-row" href="<?php echo esc_url( food_category_url( 'nutricion', 'Nutrición' ) ); ?>">
				<span class="need-number">02</span><strong class="need-title">Nutrición</strong><span class="need-copy">Proteína, grasa, fibra, energía y comparativas entre alimentos.</span><span class="need-arrow">→</span>
			</a>
			<a class="need-row" href="<?php echo esc_url( food_category_url( 'cocina', 'Cocina' ) ); ?>">
				<span class="need-number">03</span><strong class="need-title">Cocina</strong><span class="need-copy">Técnicas, errores y explicaciones de lo que ocurre en la sartén, el horno o la olla.</span><span class="need-arrow">→</span>
			</a>
			<a class="need-row" href="<?php echo esc_url( food_category_url( 'platos-menus', 'Platos y menús' ) ); ?>">
				<span class="need-number">04</span><strong class="need-title">Platos y menús</strong><span class="need-copy">Ideas para combinar alimentos y construir comidas cotidianas completas.</span><span class="need-arrow">→</span>
			</a>
			<a class="need-row" href="<?php echo esc_url( food_category_url( 'origen-calidad', 'Origen y calidad' ) ); ?>">
				<span class="need-number">05</span><strong class="need-title">Origen y calidad</strong><span class="need-copy">DOP, sellos, etiquetado, procedencia y criterios para elegir mejor.</span><span class="need-arrow">→</span>
			</a>
		</div>
	</div>
</section>

<section class="section question-section">
	<div class="container">
		<header class="section-intro">
			<div>
				<span class="section-label">Dudas frecuentes</span>
				<h2>Preguntas que aparecen en cualquier cocina</h2>
			</div>
			<p>Consultas concretas con una respuesta rápida primero y una explicación completa después.</p>
		</header>

		<div class="question-list">
			<a class="question-row" href="<?php echo esc_url( home_url( '/?s=patata+verde' ) ); ?>">
				<span class="question-icon" aria-hidden="true">🥔</span><span class="question-copy"><strong>¿Una patata verde se puede comer?</strong><span>Qué significa el color verde y cuándo conviene descartarla.</span></span><span class="question-arrow">→</span>
			</a>
			<a class="question-row" href="<?php echo esc_url( food_post_url_by_slug( 'por-que-la-carne-suelta-agua-en-la-sarten', 'carne agua sartén' ) ); ?>">
				<span class="question-icon" aria-hidden="true">🥩</span><span class="question-copy"><strong>¿Por qué la carne suelta agua?</strong><span>Temperatura, cantidad y cómo conseguir un buen dorado.</span></span><span class="question-arrow">→</span>
			</a>
			<a class="question-row" href="<?php echo esc_url( home_url( '/?s=jamon+denominacion+origen' ) ); ?>">
				<span class="question-icon" aria-hidden="true">🍖</span><span class="question-copy"><strong>¿Qué significan las denominaciones de origen del jamón?</strong><span>Cómo reconocerlas y qué información aportan.</span></span><span class="question-arrow">→</span>
			</a>
			<a class="question-row" href="<?php echo esc_url( home_url( '/?s=carne+proteina+grasa' ) ); ?>">
				<span class="question-icon" aria-hidden="true">⚖️</span><span class="question-copy"><strong>¿Qué carnes tienen más proteína y menos grasa?</strong><span>Una comparativa práctica para entender sus diferencias.</span></span><span class="question-arrow">→</span>
			</a>
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
