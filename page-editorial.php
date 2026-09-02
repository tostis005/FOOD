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
		$page['intro'] = 'Quinnoa is an editorial publication about food for people who want to understand better what they eat. We cover everyday ingredients and products through nutrition, quality, storage, food safety and cooking.';
		$page['sections'] = array(
			array(
				'title' => 'Getting to know food better',
				'paragraphs' => array(
					'Behind a cheese, an olive oil, a piece of fruit, a legume or a cut of meat there is a lot worth knowing: where its characteristics come from, what it contains, how to recognize quality, how to store it and what changes when it is cooked.',
					'Quinnoa brings that knowledge together in one place, with an accessible approach that helps make food easier to understand rather than more complicated.',
				),
			),
			array(
				'title' => 'From the shop to the kitchen',
				'paragraphs' => array(
					'We are interested in food as it appears in everyday life: the products we buy, the ingredients we keep at home and the dishes we prepare. That is why Quinnoa covers meat, fish, dairy, legumes, fruit, vegetables, grains, oils and many other foods, alongside broader topics such as nutrition, food safety, storage and cooking.',
					'The goal is simple: to make it easier to know what a food is, what makes it different, how to look after it and how to enjoy it better.',
				),
			),
			array(
				'title' => 'Information with context',
				'paragraphs' => array(
					'Quinnoa is an educational publication. We want to provide useful context so readers can understand food better and make their own decisions with more information.',
					'Our content does not replace diagnosis, treatment or individual medical advice. For health or food-safety decisions, guidance from competent authorities and qualified professionals takes priority.',
				),
			),
		);
	} else {
		$page['intro'] = 'Quinnoa es un medio editorial sobre alimentos para quienes quieren conocer mejor lo que comen. Hablamos de ingredientes y productos cotidianos desde la nutrición, la calidad, la conservación, la seguridad alimentaria y la cocina.';
		$page['sections'] = array(
			array(
				'title' => 'Conocer mejor los alimentos',
				'paragraphs' => array(
					'Detrás de un queso, un aceite, una fruta, una legumbre o un corte de carne hay muchas cosas que merece la pena conocer: de dónde vienen sus características, qué aporta, cómo reconocer su calidad, cómo conservarlo o qué cambia cuando lo cocinamos.',
					'Quinnoa reúne ese conocimiento en un mismo lugar, con un enfoque accesible que ayude a entender mejor la comida sin convertirla en algo más complicado de lo que es.',
				),
			),
			array(
				'title' => 'De la compra a la cocina',
				'paragraphs' => array(
					'Nos interesan los alimentos tal y como aparecen en la vida cotidiana: los productos que compramos, los ingredientes que guardamos en casa y lo que cocinamos con ellos. Por eso en Quinnoa hablamos de carnes, pescados, lácteos, legumbres, frutas, verduras, cereales, aceites y muchos otros alimentos, además de temas como nutrición, seguridad alimentaria, conservación y cocina.',
					'El objetivo es sencillo: que resulte más fácil saber qué es un alimento, qué lo diferencia, cómo cuidarlo y cómo disfrutarlo mejor.',
				),
			),
			array(
				'title' => 'Información con contexto',
				'paragraphs' => array(
					'Quinnoa es un medio divulgativo. Queremos aportar contexto útil para conocer mejor los alimentos y tomar decisiones propias con más información.',
					'Nuestros contenidos no sustituyen el diagnóstico, el tratamiento ni el consejo individual de un profesional sanitario. En cuestiones de salud o seguridad alimentaria, tienen prioridad las indicaciones de las autoridades competentes y de profesionales cualificados.',
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
