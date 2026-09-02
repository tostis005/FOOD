<?php
get_header();

$key      = get_query_var( 'food_editorial_page' );
$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
$pages    = function_exists( 'food_editorial_pages' ) ? food_editorial_pages() : array();
$page     = isset( $pages[ $key ][ $language ] ) ? $pages[ $key ][ $language ] : null;

if ( $page && 'about' === $key ) {
	if ( 'en' === $language ) {
		$page['intro'] = 'Quinnoa is a digital publication devoted to food and the culture around it. We cover nutrition, quality, storage, food safety and cooking with an accessible and rigorous approach.';
		$page['sections'] = array(
			array(
				'title' => 'Food in all its variety',
				'paragraphs' => array(
					'From everyday ingredients to products with a long tradition, food can be understood from many angles. Quinnoa explores meat, fish, fruit, vegetables, legumes, grains, dairy, oils and other foods, as well as the questions that connect them.',
					'We are interested in what makes each food distinctive: its composition, origin, quality, seasonality, preservation, preparation and place in everyday cooking.',
				),
			),
			array(
				'title' => 'From origin to table',
				'paragraphs' => array(
					'Good food knowledge goes beyond nutrition labels. It also includes understanding how a product is made, how to recognize its qualities, how it changes over time and what happens to it in the kitchen.',
					'Quinnoa brings these perspectives together in a publication designed for curious readers who enjoy knowing more about what they eat.',
				),
			),
			array(
				'title' => 'Editorial scope',
				'paragraphs' => array(
					'Quinnoa provides general educational information about food. Its content does not replace individual medical advice, diagnosis or treatment. For health and food-safety matters, guidance from qualified professionals and competent authorities takes priority.',
				),
			),
		);
	} else {
		$page['intro'] = 'Quinnoa es una publicación digital dedicada a los alimentos y a la cultura que los rodea. Hablamos de nutrición, calidad, conservación, seguridad alimentaria y cocina con un enfoque cercano y riguroso.';
		$page['sections'] = array(
			array(
				'title' => 'Los alimentos en toda su variedad',
				'paragraphs' => array(
					'Desde los ingredientes de todos los días hasta productos con una larga tradición, la comida se puede conocer desde muchos ángulos. En Quinnoa hablamos de carnes, pescados, frutas, verduras, legumbres, cereales, lácteos, aceites y muchos otros alimentos, además de los temas que los conectan.',
					'Nos interesa aquello que hace diferente a cada alimento: su composición, su origen, su calidad, su temporada, su conservación, su preparación y el lugar que ocupa en la cocina cotidiana.',
				),
			),
			array(
				'title' => 'Del origen a la mesa',
				'paragraphs' => array(
					'Conocer un alimento va mucho más allá de leer una etiqueta nutricional. También significa entender cómo se elabora, cómo reconocer sus cualidades, cómo evoluciona con el tiempo y qué ocurre cuando llega a la cocina.',
					'Quinnoa reúne esas distintas miradas en una publicación pensada para quienes sienten curiosidad por saber más sobre lo que comen.',
				),
			),
			array(
				'title' => 'Alcance editorial',
				'paragraphs' => array(
					'Quinnoa ofrece información divulgativa de carácter general sobre alimentación. Sus contenidos no sustituyen el consejo médico individual, el diagnóstico ni el tratamiento. En cuestiones de salud y seguridad alimentaria, tienen prioridad las indicaciones de profesionales cualificados y organismos competentes.',
				),
			),
		);
	}
}

if ( $page && 'methodology' === $key ) {
	if ( 'en' === $language ) {
		$page['intro'] = 'Quinnoa aims to publish reliable, understandable and useful information about food.';
		$page['sections'] = array(
			array(
				'title' => 'Sources and editorial judgment',
				'paragraphs' => array(
					'For nutrition and food-safety topics, we give particular weight to public authorities, recognized food-composition databases, scientific institutions and relevant research. For quality, production and cooking topics, we also use technical and specialist sources appropriate to the subject.',
				),
			),
			array(
				'title' => 'Accuracy and updates',
				'paragraphs' => array(
					'Food knowledge changes over time. We review and update published information when important data, official guidance or established evidence changes, and we correct errors when they are identified.',
				),
			),
		);
	} else {
		$page['intro'] = 'En Quinnoa buscamos publicar información fiable, comprensible y útil sobre alimentación.';
		$page['sections'] = array(
			array(
				'title' => 'Fuentes y criterio editorial',
				'paragraphs' => array(
					'En nutrición y seguridad alimentaria damos especial importancia a organismos públicos, bases de datos reconocidas de composición de alimentos, instituciones científicas y literatura relevante. Para temas de calidad, elaboración y cocina recurrimos también a fuentes técnicas y especializadas adecuadas a cada materia.',
				),
			),
			array(
				'title' => 'Precisión y actualización',
				'paragraphs' => array(
					'El conocimiento sobre alimentación evoluciona. Revisamos y actualizamos la información publicada cuando cambian datos importantes, recomendaciones oficiales o evidencias consolidadas, y corregimos los errores que detectamos.',
				),
			),
		);
	}
}

if ( $page && 'contact' === $key ) {
	$page['intro']    = 'en' === $language ? 'You can contact us using the form below.' : 'Puedes ponerte en contacto con nosotros a través del siguiente formulario.';
	$page['sections'] = array();
}

if ( $page && 'privacy' === $key ) {
	if ( 'en' === $language ) {
		$page['intro'] = 'This policy explains how personal information and cookies are handled on Quinnoa. Last updated: September 2, 2026.';
		$page['sections'] = array(
			array(
				'title' => 'Contact information',
				'paragraphs' => array(
					'Quinnoa is the editorial name of this website. Privacy and data-protection enquiries can be sent through the Contact page.',
				),
			),
			array(
				'title' => 'Personal information',
				'paragraphs' => array(
					'When you use the contact form, we receive the name, email address and message you provide. We use this information only to manage and respond to your communication.',
					'The website and its hosting infrastructure may also generate technical records needed for security and operation, such as IP address, browser information, date and requested pages.',
				),
			),
			array(
				'title' => 'Retention and service providers',
				'paragraphs' => array(
					'Contact messages are retained only for as long as reasonably necessary to manage the correspondence and related administrative needs. Personal information submitted through the contact form is not sold.',
					'Hosting, email and other technical providers may process information to the extent required to provide their services and keep the website available and secure.',
				),
			),
			array(
				'title' => 'Cookies',
				'paragraphs' => array(
					'The site may use technical cookies that are necessary for basic operation, security and WordPress functionality. These cookies are not intended to create advertising profiles.',
				),
			),
			array(
				'title' => 'Your rights',
				'paragraphs' => array(
					'Where applicable, you may request access, correction, deletion, restriction, objection or portability of your personal information through the Contact page. In Spain, you may also lodge a complaint with the Spanish Data Protection Agency.',
				),
			),
		);
	} else {
		$page['intro'] = 'Esta política explica cómo se tratan los datos personales y las cookies en Quinnoa. Última actualización: 2 de septiembre de 2026.';
		$page['sections'] = array(
			array(
				'title' => 'Contacto en materia de privacidad',
				'paragraphs' => array(
					'Quinnoa es la denominación editorial de este sitio web. Las consultas relacionadas con privacidad y protección de datos pueden enviarse a través de la página de Contacto.',
				),
			),
			array(
				'title' => 'Datos personales',
				'paragraphs' => array(
					'Cuando utilizas el formulario de contacto recibimos el nombre, la dirección de correo electrónico y el mensaje que facilitas. Estos datos se utilizan únicamente para gestionar y responder la comunicación.',
					'El sitio web y su infraestructura de alojamiento también pueden generar registros técnicos necesarios para su seguridad y funcionamiento, como la dirección IP, información del navegador, la fecha y las páginas solicitadas.',
				),
			),
			array(
				'title' => 'Conservación y proveedores',
				'paragraphs' => array(
					'Los mensajes de contacto se conservan únicamente durante el tiempo razonablemente necesario para gestionar la comunicación y las necesidades administrativas relacionadas. Los datos personales enviados mediante el formulario no se venden.',
					'Los proveedores de alojamiento, correo y otros servicios técnicos pueden tratar información en la medida necesaria para prestar sus servicios y mantener el sitio disponible y seguro.',
				),
			),
			array(
				'title' => 'Cookies',
				'paragraphs' => array(
					'El sitio puede utilizar cookies técnicas necesarias para su funcionamiento básico, la seguridad y determinadas funciones de WordPress. Estas cookies no están destinadas a crear perfiles publicitarios.',
				),
			),
			array(
				'title' => 'Tus derechos',
				'paragraphs' => array(
					'Cuando corresponda, puedes solicitar el acceso, rectificación, supresión, oposición, limitación o portabilidad de tus datos personales a través de la página de Contacto. También puedes presentar una reclamación ante la Agencia Española de Protección de Datos.',
				),
			),
		);
	}
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
		<span aria-current="page"><?php echo esc_html( $page['title'] ); ?></span>
	</nav>

	<header class="editorial-page-header">
		<h1><?php echo esc_html( $page['title'] ); ?></h1>
		<?php if ( ! empty( $page['intro'] ) ) : ?><p><?php echo esc_html( $page['intro'] ); ?></p><?php endif; ?>
	</header>

	<div class="editorial-page-content">
		<?php foreach ( $page['sections'] as $section ) : ?>
			<section>
				<h2><?php echo esc_html( $section['title'] ); ?></h2>
				<?php foreach ( $section['paragraphs'] as $paragraph ) : ?>
					<p><?php echo esc_html( $paragraph ); ?></p>
				<?php endforeach; ?>
				<?php if ( ! empty( $section['items'] ) ) : ?>
					<ul>
						<?php foreach ( $section['items'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
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