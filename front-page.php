<?php get_header(); ?>

<section class="hero">
	<div class="container hero-grid">
		<div class="hero-copy">
			<div class="eyebrow">Comer mejor empieza por entender</div>
			<h1>Todo lo que quieres saber sobre comida.</h1>
			<p>Respuestas claras sobre alimentos, cocina, conservación, nutrición práctica, origen y calidad del producto.</p>
			<form class="hero-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="food-search">Buscar</label>
				<input id="food-search" type="search" name="s" placeholder="Ej.: ¿se puede comer una patata verde?" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit">Buscar respuesta</button>
			</form>
		</div>
		<div class="hero-visual" aria-hidden="true">
			<div class="hero-label">
				<span>Pregunta de la semana</span>
				<strong>¿Por qué suelta agua la carne en la sartén?</strong>
			</div>
		</div>
	</div>
</section>

<section class="topic-strip">
	<div class="container topic-list">
		<?php
		$topics = array( 'Carnes', 'Jamón', 'Quesos', 'Aceites', 'Legumbres', 'Frutas y verduras', 'Seguridad alimentaria' );
		foreach ( $topics as $topic ) {
			printf( '<a href="%s">%s</a>', esc_url( home_url( '/?s=' . rawurlencode( $topic ) ) ), esc_html( $topic ) );
		}
		?>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head">
			<div><div class="eyebrow">Resolver dudas</div><h2>Preguntas que nos hacemos todos</h2></div>
			<p>Contenido directo para consultas concretas: seguridad, conservación, cocina y producto.</p>
		</div>
		<div class="quick-grid">
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=patata+verde' ) ); ?>"><span class="quick-icon">🥔</span><span><strong>¿Una patata verde se puede comer?</strong><br><span>Solanina, zonas verdes y cuándo descartarla.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=carne+agua+sarten' ) ); ?>"><span class="quick-icon">🥩</span><span><strong>¿Por qué la carne suelta agua?</strong><br><span>Temperatura, cantidad y cómo conseguir buen dorado.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=jamon+denominacion+origen' ) ); ?>"><span class="quick-icon">🍖</span><span><strong>Denominaciones de origen del jamón</strong><br><span>Qué significan y cómo diferenciarlas.</span></span><span class="quick-arrow">→</span></a>
			<a class="quick-link" href="<?php echo esc_url( home_url( '/?s=carne+proteina+grasa' ) ); ?>"><span class="quick-icon">⚖️</span><span><strong>Carnes con más proteína y menos grasa</strong><br><span>Comparativa para elegir según tus objetivos.</span></span><span class="quick-arrow">→</span></a>
		</div>
	</div>
</section>

<?php if ( is_active_sidebar( 'home-ad' ) ) : ?>
	<div class="container ad-slot"><?php dynamic_sidebar( 'home-ad' ); ?></div>
<?php else : ?>
	<div class="container ad-slot">Espacio preparado para publicidad / AdSense</div>
<?php endif; ?>

<section class="section">
	<div class="container">
		<div class="section-head">
			<div><div class="eyebrow">Últimas guías</div><h2>Aprende a elegir, guardar y cocinar</h2></div>
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
					<article class="post-card"><div class="card-media"><div class="card-placeholder"></div></div><div class="card-body"><div class="card-kicker">Próximamente</div><h2 class="card-title">Aquí aparecerán tus artículos</h2><p class="card-excerpt">Publica una entrada con imagen destacada y extracto para verla integrada automáticamente en esta portada.</p></div></article>
				<?php endfor;
			endif;
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
