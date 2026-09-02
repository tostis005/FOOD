<?php
get_header();

$key      = get_query_var( 'food_editorial_page' );
$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
$pages    = function_exists( 'food_editorial_pages' ) ? food_editorial_pages() : array();
$page     = isset( $pages[ $key ][ $language ] ) ? $pages[ $key ][ $language ] : null;

$food_editorial_public_text = static function( $text ) use ( $language ) {
	$text = (string) $text;
	if ( 'en' === $language ) {
		return strtr(
			$text,
			array(
				'Guides' => 'Articles',
				'guides' => 'articles',
				'Guide'  => 'Article',
				'guide'  => 'article',
			)
		);
	}

	return strtr(
		$text,
		array(
			'Cómo construimos una guía' => 'Cómo construimos un artículo',
			'cómo construimos una guía' => 'cómo construimos un artículo',
			'Guías pensadas'            => 'Artículos pensados',
			'guías pensadas'            => 'artículos pensados',
			'Cada guía'                  => 'Cada artículo',
			'cada guía'                  => 'cada artículo',
			'Una guía'                   => 'Un artículo',
			'una guía'                   => 'un artículo',
			'Guías'                      => 'Artículos',
			'guías'                      => 'artículos',
			'Guía'                       => 'Artículo',
			'guía'                       => 'artículo',
		)
	);
};

if ( $page && 'about' === $key ) {
	if ( 'en' === $language ) {
		$page['intro'] = 'Quinnoa is an editorial publication for understanding food better and making more informed everyday choices. We publish clear articles on nutrition, quality, food safety, storage, buying and cooking, with enough context to explain not only what happens, but why.';
		$page['sections'] = array(
			array(
				'title' => 'Understanding the food we eat',
				'paragraphs' => array(
					'We start with real questions that come up when buying, storing or cooking food: why meat releases water, which foods provide more protein, how long something keeps in the fridge, how two options compare, or what a nutrition number actually means.',
					'The aim is not to add noise or fill space. We want each article to give a useful answer quickly, then add the figures, comparisons and explanation needed to put that answer in context.',
				),
			),
			array(
				'title' => 'How we work',
				'paragraphs' => array(
					'Each article begins with a specific reader question and moves from the practical answer into the explanation. When a comparison depends on numbers, we include quantities, units and like-for-like references so “more”, “less” or “better” has a concrete meaning.',
				),
				'items' => array(
					'We prioritize public authorities, established databases and scientific literature for nutrition and food-safety topics.',
					'We separate facts, practical context and recommendations.',
					'We avoid claims of professional review when that review has not taken place.',
					'We update content when relevant evidence, guidance or standards change.',
				),
			),
			array(
				'title' => 'Editorial scope',
				'paragraphs' => array(
					'Quinnoa provides educational information and does not replace diagnosis, treatment or individual medical advice. For food-safety or health decisions, guidance from competent authorities and qualified professionals takes priority.',
				),
			),
		);
	} else {
		$page['intro'] = 'Quinnoa es un medio editorial para entender mejor los alimentos y tomar decisiones cotidianas con más criterio. Publicamos artículos claros sobre nutrición, calidad, seguridad alimentaria, conservación, compra y cocina, con el contexto necesario para explicar no solo qué ocurre, sino también por qué.';
		$page['sections'] = array(
			array(
				'title' => 'Entender mejor lo que comemos',
				'paragraphs' => array(
					'Partimos de dudas que aparecen de verdad al comprar, conservar o cocinar: por qué una carne suelta agua, qué alimentos aportan más proteína, cuánto dura algo en la nevera, cómo comparar dos opciones o qué significa realmente una cifra nutricional.',
					'La idea no es añadir ruido ni llenar páginas. Queremos que cada artículo dé una respuesta útil desde el principio y, después, aporte las cifras, comparaciones y explicaciones necesarias para poner esa respuesta en contexto.',
				),
			),
			array(
				'title' => 'Cómo trabajamos',
				'paragraphs' => array(
					'Cada artículo empieza por una pregunta concreta y avanza desde la respuesta práctica hacia la explicación. Cuando una comparación depende de números, incluimos cantidades, unidades y referencias equivalentes para que “más”, “menos” o “mejor” tengan un significado concreto.',
				),
				'items' => array(
					'Priorizamos organismos oficiales, bases de datos reconocidas y literatura científica en nutrición y seguridad alimentaria.',
					'Separamos los hechos del contexto práctico y de las recomendaciones.',
					'No atribuimos revisiones profesionales que no hayan ocurrido.',
					'Actualizamos los contenidos cuando cambian datos, recomendaciones o criterios relevantes.',
				),
			),
			array(
				'title' => 'Alcance editorial',
				'paragraphs' => array(
					'Quinnoa ofrece información divulgativa y no sustituye el diagnóstico, el tratamiento ni el consejo individual de un profesional sanitario. En cuestiones de seguridad alimentaria o salud, tienen prioridad las indicaciones de las autoridades competentes y de profesionales cualificados.',
				),
			),
		);
	}
}

if ( $page && 'contact' === $key ) {
	$page['intro'] = 'en' === $language
		? 'If you have spotted an error, want to suggest a topic or simply need to get in touch with Quinnoa, send us a message using the form below.'
		: 'Si has detectado un error, quieres proponer un tema o simplemente necesitas ponerte en contacto con Quinnoa, escríbenos a través del formulario.';
}

if ( ! $page ) {
	status_header( 404 );
	?>
	<div class="editorial-page-shell"><p><?php echo esc_html( 'en' === $language ? 'Page not found.' : 'Página no encontrada.' ); ?></p></div>
	<?php
	get_footer();
	return;
}
?>

<div class="editorial-page-shell">
	<nav class="breadcrumbs" aria-label="<?php echo esc_attr( 'en' === $language ? 'Breadcrumbs' : 'Migas de pan' ); ?>">
		<a href="<?php echo esc_url( function_exists( 'food_language_home_url' ) ? food_language_home_url( $language ) : home_url( '/' ) ); ?>"><?php echo esc_html( 'en' === $language ? 'Home' : 'Inicio' ); ?></a>
		<span aria-hidden="true">›</span>
		<span aria-current="page"><?php echo esc_html( $food_editorial_public_text( $page['title'] ) ); ?></span>
	</nav>

	<header class="editorial-page-header">
		<div class="eyebrow"><?php echo esc_html( $food_editorial_public_text( $page['eyebrow'] ) ); ?></div>
		<h1><?php echo esc_html( $food_editorial_public_text( $page['title'] ) ); ?></h1>
		<p><?php echo esc_html( $food_editorial_public_text( $page['intro'] ) ); ?></p>
	</header>

	<div class="editorial-page-content">
		<?php foreach ( $page['sections'] as $section ) : ?>
			<section>
				<h2><?php echo esc_html( $food_editorial_public_text( $section['title'] ) ); ?></h2>
				<?php foreach ( $section['paragraphs'] as $paragraph ) : ?>
					<p><?php echo esc_html( $food_editorial_public_text( $paragraph ) ); ?></p>
				<?php endforeach; ?>
				<?php if ( ! empty( $section['items'] ) ) : ?>
					<ul>
						<?php foreach ( $section['items'] as $item ) : ?><li><?php echo esc_html( $food_editorial_public_text( $item ) ); ?></li><?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</section>
		<?php endforeach; ?>

		<?php if ( 'contact' === $key ) : ?>
			<section>
				<h2><?php echo esc_html( 'en' === $language ? 'Send a message' : 'Enviar un mensaje' ); ?></h2>
				<?php $contact_status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : ''; ?>
				<?php if ( 'sent' === $contact_status ) : ?>
					<p class="contact-notice"><?php echo esc_html( 'en' === $language ? 'Your message has been sent. Thank you.' : 'Tu mensaje se ha enviado. Gracias.' ); ?></p>
				<?php elseif ( 'error' === $contact_status ) : ?>
					<p class="contact-notice"><?php echo esc_html( 'en' === $language ? 'We could not send the message. Check the fields and try again.' : 'No hemos podido enviar el mensaje. Revisa los campos e inténtalo de nuevo.' ); ?></p>
				<?php endif; ?>
				<form class="pometum-contact-form" method="post" action="<?php echo esc_url( food_editorial_page_url( 'contact', $language ) ); ?>">
					<?php wp_nonce_field( 'food_contact', 'food_contact_nonce' ); ?>
					<label><?php echo esc_html( 'en' === $language ? 'Name' : 'Nombre' ); ?><input type="text" name="name" autocomplete="name" required></label>
					<label><?php echo esc_html( 'en' === $language ? 'Email' : 'Correo electrónico' ); ?><input type="email" name="email" autocomplete="email" required></label>
					<label><?php echo esc_html( 'en' === $language ? 'Message' : 'Mensaje' ); ?><textarea name="message" required></textarea></label>
					<label class="screen-reader-text" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
					<button type="submit"><?php echo esc_html( 'en' === $language ? 'Send message' : 'Enviar mensaje' ); ?></button>
				</form>
			</section>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
