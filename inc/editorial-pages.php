<?php
/**
 * Virtual bilingual editorial pages for Pometum.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_editorial_pages() {
	return array(
		'about' => array(
			'es' => array(
				'slug' => 'sobre-pometum',
				'title' => 'Sobre Pometum',
				'eyebrow' => 'Quiénes somos',
				'intro' => 'Pometum es un proyecto editorial dedicado a explicar mejor los alimentos: qué contienen, cómo reconocer su calidad, cómo conservarlos y qué ocurre cuando los cocinamos.',
				'sections' => array(
					array( 'title' => 'Una mirada práctica a la comida', 'paragraphs' => array( 'Nos interesan las preguntas que aparecen antes, durante y después de comer: cómo elegir un producto, qué diferencias hay entre dos alimentos, cuánto dura en buen estado, por qué cambia su textura o qué aporta realmente desde el punto de vista nutricional.', 'El objetivo es convertir información técnica en respuestas claras y útiles, sin simplificar tanto como para perder precisión.' ) ),
					array( 'title' => 'Qué queremos que encuentres aquí', 'paragraphs' => array( 'Guías evergreen sobre alimentos, nutrición, seguridad alimentaria, conservación, calidad, producción y cocina. Priorizamos contenidos que puedan seguir siendo útiles con el tiempo y que ayuden a tomar mejores decisiones en el día a día.', 'Pometum no pretende sustituir el consejo médico ni las indicaciones de las autoridades sanitarias. Cuando una cuestión afecta a salud o seguridad, damos prioridad a fuentes oficiales y a la evidencia disponible.' ) ),
				),
			),
			'en' => array(
				'slug' => 'about',
				'title' => 'About',
				'eyebrow' => 'About Pometum',
				'intro' => 'Pometum is an editorial project focused on understanding food better: what it contains, how to judge quality, how to store it and what happens when we cook it.',
				'sections' => array(
					array( 'title' => 'A practical way to understand food', 'paragraphs' => array( 'We focus on the questions that come up before, during and after eating: how to choose a product, how two foods differ, how long something keeps, why texture changes or what a food actually contributes nutritionally.', 'Our aim is to turn technical information into clear, useful answers without removing the context needed to understand them properly.' ) ),
					array( 'title' => 'What you will find here', 'paragraphs' => array( 'Evergreen guides on food, nutrition, food safety, storage, quality, production and cooking. We prioritize information that remains useful over time and supports better everyday decisions.', 'Pometum does not replace medical advice or official food-safety guidance. When a topic involves health or safety, we prioritize authoritative sources and the best available evidence.' ) ),
				),
			),
		),
		'methodology' => array(
			'es' => array(
				'slug' => 'metodologia',
				'title' => 'Metodología',
				'eyebrow' => 'Cómo trabajamos',
				'intro' => 'Cada guía de Pometum parte de una pregunta concreta y busca responderla de forma directa, verificable y útil.',
				'sections' => array(
					array( 'title' => 'Cómo construimos una guía', 'paragraphs' => array( 'Primero definimos exactamente qué necesita saber una persona y qué comparaciones o cifras hacen falta para entender la respuesta. Después estructuramos el contenido desde la conclusión práctica hacia la explicación, evitando rodeos innecesarios.' ), 'items' => array( 'Respuesta clara al principio cuando la consulta lo permite.', 'Cantidades, unidades y referencias comparables cuando decimos que algo tiene más, menos o dura más.', 'Separación entre hechos, contexto práctico y recomendaciones.', 'Fuentes oficiales, científicas o técnicas especialmente en nutrición y seguridad alimentaria.', 'Revisión del contenido cuando cambian datos, recomendaciones o criterios relevantes.' ) ),
					array( 'title' => 'Fuentes y precisión', 'paragraphs' => array( 'Para seguridad alimentaria y nutrición damos prioridad a organismos públicos, bases de datos de composición, instituciones sanitarias y literatura científica cuando aporta contexto adicional. En cuestiones culinarias o de calidad combinamos esa base con explicación técnica y aplicaciones prácticas.', 'No atribuimos revisiones profesionales que no hayan ocurrido y procuramos dejar claro cuándo una afirmación depende del tipo de alimento, la preparación, la cantidad o las condiciones de conservación.' ) ),
				),
			),
			'en' => array(
				'slug' => 'methodology',
				'title' => 'Methodology',
				'eyebrow' => 'How we work',
				'intro' => 'Every Pometum guide starts with a specific question and aims to answer it directly, accurately and usefully.',
				'sections' => array(
					array( 'title' => 'How we build a guide', 'paragraphs' => array( 'We first define exactly what a reader needs to know and which figures or comparisons are necessary to make the answer meaningful. We then structure the piece from the practical conclusion toward the explanation, avoiding unnecessary detours.' ), 'items' => array( 'A clear answer near the beginning whenever the question allows it.', 'Quantities, units and comparable references when we say something has more, less or lasts longer.', 'A clear distinction between facts, practical context and recommendations.', 'Authoritative, scientific or technical sources, especially for nutrition and food safety.', 'Updates when relevant data, recommendations or standards change.' ) ),
					array( 'title' => 'Sources and accuracy', 'paragraphs' => array( 'For food safety and nutrition we prioritize public authorities, food-composition databases, health institutions and scientific literature when it adds useful context. For cooking and quality questions, we combine that foundation with technical explanation and practical application.', 'We do not claim professional review that has not taken place, and we aim to explain when an answer depends on the food, preparation, portion or storage conditions.' ) ),
				),
			),
		),
		'contact' => array(
			'es' => array(
				'slug' => 'contacto',
				'title' => 'Contacto',
				'eyebrow' => 'Escríbenos',
				'intro' => 'Puedes utilizar este formulario para enviarnos correcciones, propuestas editoriales, consultas sobre Pometum o cuestiones relacionadas con privacidad.',
				'sections' => array(
					array( 'title' => 'Antes de escribir', 'paragraphs' => array( 'Si has detectado un dato que crees que debe corregirse, indícanos la guía concreta y, si es posible, la fuente que respalda el cambio. Esto nos ayuda a revisarlo con más rapidez.', 'No utilices este formulario para solicitar diagnóstico médico ni para situaciones urgentes de seguridad alimentaria.' ) ),
				),
			),
			'en' => array(
				'slug' => 'contact',
				'title' => 'Contact',
				'eyebrow' => 'Get in touch',
				'intro' => 'Use this form to send corrections, editorial suggestions, questions about Pometum or privacy-related requests.',
				'sections' => array(
					array( 'title' => 'Before you write', 'paragraphs' => array( 'If you have found information that you believe should be corrected, please identify the specific guide and, where possible, include the source supporting the change. This helps us review it more efficiently.', 'Please do not use this form for medical diagnosis or urgent food-safety situations.' ) ),
				),
			),
		),
		'privacy' => array(
			'es' => array(
				'slug' => 'privacidad',
				'title' => 'Privacidad',
				'eyebrow' => 'Privacidad y datos',
				'intro' => 'Esta página resume cómo se tratan los datos personales cuando utilizas Pometum o nos escribes a través del formulario de contacto.',
				'sections' => array(
					array( 'title' => 'Datos que puedes facilitarnos', 'paragraphs' => array( 'Si utilizas el formulario de contacto, recibimos el nombre, la dirección de correo electrónico y el mensaje que decidas enviar. Utilizamos esos datos únicamente para gestionar y responder la consulta.' ) ),
					array( 'title' => 'Datos técnicos', 'paragraphs' => array( 'El alojamiento y la infraestructura de la web pueden tratar registros técnicos necesarios para funcionamiento, seguridad, prevención de abuso y diagnóstico de errores. Pometum puede utilizar cookies estrictamente necesarias para prestar funciones del sitio.', 'Si en el futuro se incorporan servicios de analítica, publicidad u otros proveedores que requieran información adicional o consentimiento, esta política y los mecanismos de privacidad se actualizarán antes o junto con su activación.' ) ),
					array( 'title' => 'Conservación y derechos', 'paragraphs' => array( 'Los mensajes se conservan durante el tiempo razonablemente necesario para atender la consulta y mantener un historial operativo cuando sea necesario. No vendemos datos personales.', 'Para solicitar acceso, rectificación o eliminación de datos enviados mediante el formulario, utiliza la página de Contacto e indica que se trata de una solicitud de privacidad.' ) ),
				),
			),
			'en' => array(
				'slug' => 'privacy',
				'title' => 'Privacy',
				'eyebrow' => 'Privacy and data',
				'intro' => 'This page summarizes how personal information is handled when you use Pometum or contact us through the site.',
				'sections' => array(
					array( 'title' => 'Information you choose to send', 'paragraphs' => array( 'If you use the contact form, we receive the name, email address and message you choose to provide. We use this information to manage and respond to your request.' ) ),
					array( 'title' => 'Technical data', 'paragraphs' => array( 'The hosting and website infrastructure may process technical logs needed for operation, security, abuse prevention and error diagnosis. Pometum may use strictly necessary cookies to provide site functionality.', 'If analytics, advertising or other third-party services requiring additional information or consent are introduced, this policy and the relevant privacy controls will be updated before or alongside their activation.' ) ),
					array( 'title' => 'Retention and your choices', 'paragraphs' => array( 'Messages are kept for the period reasonably necessary to respond and maintain an operational record where needed. We do not sell personal data.', 'To request access, correction or deletion of information sent through the contact form, use the Contact page and state that your message is a privacy request.' ) ),
				),
			),
		),
	);
}

function food_editorial_page_url( $key, $language = '' ) {
	$pages = food_editorial_pages();
	$language = $language ?: ( function_exists( 'food_current_language' ) ? food_current_language() : 'es' );
	if ( ! isset( $pages[ $key ][ $language ] ) ) {
		return function_exists( 'food_language_home_url' ) ? food_language_home_url( $language ) : home_url( '/' );
	}
	$slug = $pages[ $key ][ $language ]['slug'];
	return 'en' === $language ? home_url( '/en/' . $slug . '/' ) : home_url( '/' . $slug . '/' );
}

function food_editorial_page_query_vars( $vars ) {
	$vars[] = 'food_editorial_page';
	return $vars;
}
add_filter( 'query_vars', 'food_editorial_page_query_vars' );

function food_register_editorial_page_rewrites() {
	foreach ( food_editorial_pages() as $key => $languages ) {
		add_rewrite_rule( '^' . preg_quote( $languages['es']['slug'], '#' ) . '/?$', 'index.php?food_editorial_page=' . $key . '&food_lang=es', 'top' );
		add_rewrite_rule( '^en/' . preg_quote( $languages['en']['slug'], '#' ) . '/?$', 'index.php?food_editorial_page=' . $key . '&food_lang=en', 'top' );
	}
	if ( '2' !== get_option( 'food_editorial_pages_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'food_editorial_pages_rewrite_version', '2' );
	}
}
add_action( 'init', 'food_register_editorial_page_rewrites', 89 );

function food_prepare_editorial_page_query( $query ) {
	if ( ! $query instanceof WP_Query || ! $query->is_main_query() || ! $query->get( 'food_editorial_page' ) ) {
		return;
	}
	$query->is_404 = false;
	$query->is_home = false;
}
add_action( 'parse_query', 'food_prepare_editorial_page_query', 1 );

function food_editorial_page_prevent_404( $preempt, $query ) {
	if ( $query instanceof WP_Query && $query->get( 'food_editorial_page' ) ) {
		$query->is_404 = false;
		return false;
	}
	return $preempt;
}
add_filter( 'pre_handle_404', 'food_editorial_page_prevent_404', 10, 2 );

function food_editorial_page_template( $template ) {
	if ( get_query_var( 'food_editorial_page' ) ) {
		$editorial_template = get_template_directory() . '/page-editorial.php';
		return file_exists( $editorial_template ) ? $editorial_template : $template;
	}
	return $template;
}
add_filter( 'template_include', 'food_editorial_page_template', 98 );

function food_editorial_page_document_title( $title ) {
	$key = get_query_var( 'food_editorial_page' );
	if ( ! $key ) {
		return $title;
	}
	$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	$pages = food_editorial_pages();
	return isset( $pages[ $key ][ $language ] ) ? $pages[ $key ][ $language ]['title'] . ' | Pometum' : $title;
}
add_filter( 'pre_get_document_title', 'food_editorial_page_document_title', 20 );

function food_editorial_page_head_links() {
	$key = get_query_var( 'food_editorial_page' );
	if ( ! $key || ! isset( food_editorial_pages()[ $key ] ) ) {
		return;
	}
	$current = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	echo '<link rel="canonical" href="' . esc_url( food_editorial_page_url( $key, $current ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( food_editorial_page_url( $key, 'es' ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( food_editorial_page_url( $key, 'en' ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( food_editorial_page_url( $key, 'es' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'food_editorial_page_head_links', 4 );

function food_handle_contact_form() {
	if ( 'contact' !== get_query_var( 'food_editorial_page' ) || 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
	$current_url = food_editorial_page_url( 'contact', $language );
	if ( ! isset( $_POST['food_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['food_contact_nonce'] ) ), 'food_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $current_url ) );
		exit;
	}
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $current_url ) );
		exit;
	}
	$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( '' === $name || ! is_email( $email ) || strlen( $message ) < 10 ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $current_url ) );
		exit;
	}
	$subject = 'en' === $language ? 'Pometum contact form' : 'Formulario de contacto Pometum';
	$body = "Name: {$name}\nEmail: {$email}\nLanguage: {$language}\n\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent = wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );
	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', $current_url ) );
	exit;
}
add_action( 'template_redirect', 'food_handle_contact_form', 8 );
