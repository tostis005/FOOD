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
				'title' => 'Acerca de',
				'eyebrow' => 'Pometum',
				'intro' => 'Pometum es un medio editorial sobre alimentos, calidad, nutrición, seguridad y cocina. Nuestro objetivo es explicar qué hay detrás de lo que comemos con claridad, contexto y criterio.',
				'sections' => array(
					array(
						'title' => 'Entender la comida, sin ruido',
						'paragraphs' => array(
							'Publicamos guías pensadas para resolver preguntas concretas: cómo elegir un alimento, qué diferencias existen entre dos opciones, cuánto dura en buenas condiciones, por qué cambia al cocinarlo o qué aporta realmente su composición.',
							'Buscamos que la respuesta sea útil desde el primer momento, pero también que explique lo suficiente para entender el porqué y poder tomar mejores decisiones en situaciones parecidas.',
						),
					),
					array(
						'title' => 'Cómo trabajamos',
						'paragraphs' => array(
							'Cada artículo parte de una intención clara de búsqueda y se estructura desde la respuesta práctica hacia la explicación. Cuando una comparación necesita cifras, incluimos cantidades, unidades y referencias equivalentes para que la diferencia tenga significado.',
						),
						'items' => array(
							'Priorizamos fuentes oficiales, bases de datos reconocidas y literatura científica en nutrición y seguridad alimentaria.',
							'Distinguimos entre hechos, contexto práctico y recomendaciones.',
							'No atribuimos revisiones profesionales que no hayan ocurrido.',
							'Actualizamos los contenidos cuando cambian datos, recomendaciones o criterios relevantes.',
						),
					),
					array(
						'title' => 'Alcance editorial',
						'paragraphs' => array(
							'Pometum ofrece información divulgativa y no sustituye el diagnóstico, el tratamiento ni el consejo individual de un profesional sanitario. En cuestiones de seguridad alimentaria o salud, las indicaciones de las autoridades competentes y de profesionales cualificados tienen prioridad.',
						),
					),
				),
			),
			'en' => array(
				'slug' => 'about',
				'title' => 'About',
				'eyebrow' => 'Pometum',
				'intro' => 'Pometum is an editorial publication about food, quality, nutrition, safety and cooking. Our aim is to explain what sits behind the food we eat with clarity, context and sound judgment.',
				'sections' => array(
					array(
						'title' => 'Understanding food without the noise',
						'paragraphs' => array(
							'We publish guides built around practical questions: how to choose a food, what differs between two options, how long something keeps, why it changes during cooking, or what its composition actually means.',
							'We want the answer to be useful immediately, while still giving enough context to understand why it is true and apply the same reasoning elsewhere.',
						),
					),
					array(
						'title' => 'How we work',
						'paragraphs' => array(
							'Each article starts with a clear reader need and moves from the practical answer into the explanation. When a comparison depends on numbers, we include quantities, units and like-for-like references so the difference is meaningful.',
						),
						'items' => array(
							'We prioritize public authorities, established databases and scientific literature for nutrition and food-safety topics.',
							'We distinguish facts from practical context and recommendations.',
							'We do not claim professional review that has not taken place.',
							'We update content when relevant evidence, guidance or standards change.',
						),
					),
					array(
						'title' => 'Editorial scope',
						'paragraphs' => array(
							'Pometum provides educational information and does not replace diagnosis, treatment or individual medical advice. For food-safety or health decisions, guidance from competent authorities and qualified professionals takes priority.',
						),
					),
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
				'eyebrow' => 'Pometum',
				'intro' => '¿Quieres contactar con nosotros? Escríbenos a través del formulario.',
				'sections' => array(),
			),
			'en' => array(
				'slug' => 'contact',
				'title' => 'Contact',
				'eyebrow' => 'Pometum',
				'intro' => 'Want to get in touch? Send us a message using the form below.',
				'sections' => array(),
			),
		),
		'privacy' => array(
			'es' => array(
				'slug' => 'privacidad',
				'title' => 'Privacidad y cookies',
				'eyebrow' => 'Información legal',
				'intro' => 'En esta página reunimos la información sobre privacidad, datos personales y cookies de Pometum para que sea fácil de consultar. Última actualización: 2 de septiembre de 2026.',
				'sections' => array(
					array(
						'title' => 'Responsable y contacto',
						'paragraphs' => array(
							'Pometum es la denominación editorial de este sitio. Para cualquier consulta relacionada con privacidad o protección de datos puedes utilizar la página de Contacto.',
							'Si el proyecto incorpora monetización u otra actividad económica que exija información identificativa adicional del prestador, esta sección se completará con los datos legalmente exigibles antes de activar dicha actividad.',
						),
					),
					array(
						'title' => 'Qué datos tratamos y para qué',
						'paragraphs' => array(
							'Si utilizas el formulario de contacto, tratamos el nombre, el correo electrónico y el contenido del mensaje para gestionar y responder tu solicitud. La infraestructura técnica también puede generar registros de seguridad y funcionamiento, como dirección IP, fecha, navegador o URL solicitada, necesarios para proteger y mantener el servicio.',
							'La base para tratar los datos del formulario es la gestión de la solicitud que nos envías. Los registros estrictamente necesarios para seguridad y prevención de abuso se tratan por el interés legítimo en mantener el sitio protegido y operativo.',
						),
					),
					array(
						'title' => 'Conservación y proveedores',
						'paragraphs' => array(
							'Conservamos los mensajes durante el tiempo razonablemente necesario para responderlos y mantener un historial operativo cuando resulte necesario. No vendemos datos personales.',
							'El alojamiento, el correo y otros proveedores técnicos pueden tratar datos únicamente para prestar sus servicios. Cuando un proveedor implique transferencias internacionales, se aplicarán las garantías que correspondan conforme a la normativa aplicable.',
						),
					),
					array(
						'title' => 'Cookies',
						'paragraphs' => array(
							'Pometum puede utilizar cookies técnicas estrictamente necesarias para el funcionamiento, la seguridad y las funciones básicas del sitio. Estas cookies no se utilizan para elaborar perfiles publicitarios.',
							'Si se incorporan cookies de analítica, publicidad u otros usos no esenciales, se informará de su finalidad y, cuando la normativa lo exija, no se activarán hasta obtener una elección válida del usuario. Las opciones de aceptar y rechazar se ofrecerán con una visibilidad y facilidad equivalentes.',
						),
					),
					array(
						'title' => 'Publicidad y servicios de Google',
						'paragraphs' => array(
							'Pometum podrá utilizar Google AdSense u otros servicios publicitarios para financiar el proyecto. Cuando Google AdSense esté activo, Google y otros proveedores autorizados podrán utilizar cookies, direcciones IP u otros identificadores para servir, limitar y medir anuncios, de acuerdo con la configuración aplicable y las elecciones de privacidad del usuario.',
							'Antes de activar publicidad que requiera consentimiento para usuarios del Espacio Económico Europeo, Reino Unido o Suiza, se implementará el mecanismo de consentimiento exigible y se actualizará esta política con los proveedores y opciones que estén realmente activos.',
						),
					),
					array(
						'title' => 'Tus derechos',
						'paragraphs' => array(
							'Puedes solicitar, cuando corresponda, acceso, rectificación, supresión, oposición, limitación o portabilidad de tus datos a través de la página de Contacto. También puedes presentar una reclamación ante la Agencia Española de Protección de Datos si consideras que el tratamiento de tus datos no se ajusta a la normativa.',
						),
					),
				),
			),
			'en' => array(
				'slug' => 'privacy',
				'title' => 'Privacy & cookies',
				'eyebrow' => 'Legal information',
				'intro' => 'This page brings together Pometum’s privacy, personal-data and cookie information in one place. Last updated: September 2, 2026.',
				'sections' => array(
					array(
						'title' => 'Controller and contact',
						'paragraphs' => array(
							'Pometum is the editorial name of this website. For privacy or data-protection questions, please use the Contact page.',
							'If the project introduces monetization or another economic activity that requires additional provider identification, this section will be completed with the legally required details before that activity is activated.',
						),
					),
					array(
						'title' => 'Data we process and why',
						'paragraphs' => array(
							'If you use the contact form, we process your name, email address and message in order to handle and respond to your request. The technical infrastructure may also generate security and operational logs, such as IP address, date, browser or requested URL, where needed to protect and maintain the service.',
							'Contact-form data is processed in order to handle the request you send us. Strictly necessary security and abuse-prevention logs are processed on the basis of the legitimate interest in keeping the site secure and operational.',
						),
					),
					array(
						'title' => 'Retention and providers',
						'paragraphs' => array(
							'We keep messages for the period reasonably necessary to respond and maintain an operational record where needed. We do not sell personal data.',
							'Hosting, email and other technical providers may process information only as needed to provide their services. Where a provider involves international data transfers, the safeguards required by applicable law will be used.',
						),
					),
					array(
						'title' => 'Cookies',
						'paragraphs' => array(
							'Pometum may use strictly necessary technical cookies for operation, security and basic site functionality. These cookies are not used to build advertising profiles.',
							'If analytics, advertising or other non-essential cookies are introduced, their purpose will be disclosed and, where required by law, they will not be activated until the user has made a valid choice. Accept and reject options will be presented with equivalent prominence and ease of use.',
						),
					),
					array(
						'title' => 'Advertising and Google services',
						'paragraphs' => array(
							'Pometum may use Google AdSense or other advertising services to support the publication. When Google AdSense is active, Google and authorized vendors may use cookies, IP addresses or other identifiers to serve, limit and measure advertising in accordance with the applicable settings and the user’s privacy choices.',
							'Before advertising that requires consent is enabled for users in the European Economic Area, the United Kingdom or Switzerland, the required consent mechanism will be implemented and this policy will be updated to reflect the providers and options actually in use.',
						),
					),
					array(
						'title' => 'Your rights',
						'paragraphs' => array(
							'Where applicable, you may request access, correction, deletion, restriction, objection or portability of your personal data through the Contact page. If you are in Spain, you may also lodge a complaint with the Spanish Data Protection Agency if you believe your data has been handled unlawfully.',
						),
					),
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
	if ( '4' !== get_option( 'food_editorial_pages_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'food_editorial_pages_rewrite_version', '4' );
	}
}
add_action( 'init', 'food_register_editorial_page_rewrites', 99 );

/**
 * Resolve editorial routes independently of WordPress rewrite precedence.
 * This keeps /en/about/ et al. from ever being interpreted as article slugs.
 */
function food_resolve_editorial_page_request( $wp ) {
	if ( ! $wp instanceof WP ) {
		return;
	}

	$request = trim( (string) $wp->request, '/' );
	if ( '' === $request ) {
		return;
	}

	foreach ( food_editorial_pages() as $key => $languages ) {
		foreach ( array( 'es', 'en' ) as $language ) {
			$expected = 'en' === $language ? 'en/' . $languages[ $language ]['slug'] : $languages[ $language ]['slug'];
			if ( $request !== $expected ) {
				continue;
			}

			$wp->query_vars = array(
				'food_editorial_page' => $key,
				'food_lang'           => $language,
			);
			return;
		}
	}
}
add_action( 'parse_request', 'food_resolve_editorial_page_request', 1 );

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
