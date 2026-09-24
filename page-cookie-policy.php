<?php
get_header();

$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
$english  = 'en' === $language;
$home_url = function_exists( 'food_language_home_url' ) ? food_language_home_url( $language ) : home_url( '/' );
$contact  = function_exists( 'food_editorial_page_url' ) ? food_editorial_page_url( 'contact', $language ) : home_url( $english ? '/en/contact/' : '/contacto/' );
$title    = $english ? 'Cookie policy' : 'Política de cookies';
?>

<div class="editorial-page-shell">
	<nav class="breadcrumbs" aria-label="<?php echo esc_attr( $english ? 'Breadcrumbs' : 'Migas de pan' ); ?>">
		<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( $english ? 'Home' : 'Inicio' ); ?></a>
		<span aria-hidden="true">›</span>
		<span aria-current="page"><?php echo esc_html( $title ); ?></span>
	</nav>

	<header class="editorial-page-header">
		<h1><?php echo esc_html( $title ); ?></h1>
		<p><?php echo esc_html( $english
			? 'This policy explains which cookies Quinnoa uses, why they are used and how you can manage them. Last updated: September 24, 2026.'
			: 'Esta política explica qué cookies utiliza Quinnoa, para qué se usan y cómo puedes gestionarlas. Última actualización: 24 de septiembre de 2026.' ); ?></p>
	</header>

	<div class="editorial-page-content">
		<section>
			<h2><?php echo esc_html( $english ? 'What a cookie is' : 'Qué es una cookie' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'A cookie is a small file that a website can store in the browser to remember information between visits. Depending on their purpose, cookies may be used for technical functions, preferences, measurement or advertising.'
				: 'Una cookie es un pequeño archivo que un sitio web puede guardar en el navegador para recordar información entre visitas. Según su finalidad, las cookies pueden utilizarse para funciones técnicas, preferencias, medición o publicidad.' ); ?></p>
		</section>

		<section>
			<h2><?php echo esc_html( $english ? 'Cookies and measurement used by Quinnoa' : 'Cookies y medición utilizadas por Quinnoa' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'Quinnoa uses two first-party preference cookies: one to remember the language you explicitly select and another to remember your cookie choice. Google Analytics is configured with analytics storage denied by default and is only granted analytics storage if you choose to allow analytics cookies.'
				: 'Quinnoa utiliza dos cookies propias de preferencia: una para recordar el idioma que eliges expresamente y otra para recordar tu elección sobre cookies. Google Analytics está configurado con el almacenamiento analítico denegado por defecto y solo recibe permiso de almacenamiento analítico si eliges permitir cookies analíticas.' ); ?></p>
			<ul>
				<li><strong>quinnoa_language</strong> — <?php echo esc_html( $english
					? 'Provider: Quinnoa. Purpose: remember Spanish or English after an explicit language choice. Duration: 6 months.'
					: 'Proveedor: Quinnoa. Finalidad: recordar español o inglés después de una elección expresa de idioma. Duración: 6 meses.' ); ?></li>
				<li><strong>quinnoa_cookie_consent</strong> — <?php echo esc_html( $english
					? 'Provider: Quinnoa. Purpose: remember whether you accepted analytics or chose necessary cookies only. Duration: 12 months.'
					: 'Proveedor: Quinnoa. Finalidad: recordar si aceptaste analítica o elegiste solo cookies necesarias. Duración: 12 meses.' ); ?></li>
			</ul>
			<p><?php echo esc_html( $english
				? 'The site also contains Google AdSense code used for advertising setup and verification. Advertising storage, ad user data and ad personalization consent signals are denied by default in the current site configuration. Google services may still make technical requests needed to load or measure their services.'
				: 'El sitio también contiene código de Google AdSense utilizado para la configuración y verificación publicitaria. En la configuración actual de la web, las señales de consentimiento para almacenamiento publicitario, datos de usuario publicitarios y personalización de anuncios están denegadas por defecto. Los servicios de Google pueden realizar solicitudes técnicas necesarias para cargar o medir sus servicios.' ); ?></p>
		</section>
		<section>
			<h2><?php echo esc_html( $english ? 'Browser-language detection' : 'Detección del idioma del navegador' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'If there is no saved language preference and you enter through the Spanish home page, Quinnoa may use the Accept-Language information sent by your browser to choose between the Spanish and English home pages. This check is used for that request and does not itself create the language cookie.'
				: 'Si no existe una preferencia de idioma guardada y entras por la portada en español, Quinnoa puede utilizar la información Accept-Language que envía tu navegador para elegir entre la portada en español y la portada en inglés. Esta comprobación se usa para esa petición y no crea por sí misma la cookie de idioma.' ); ?></p>
			<p><?php echo esc_html( $english
				? 'Once you select a language from the Quinnoa language selector, your explicit choice takes priority over automatic browser-language detection.'
				: 'Cuando eliges un idioma desde el selector de Quinnoa, tu elección expresa pasa a tener prioridad sobre la detección automática del navegador.' ); ?></p>
		</section>

		<section>
			<h2><?php echo esc_html( $english ? 'Why this preference does not require a cookie banner' : 'Por qué esta preferencia no requiere un banner de cookies' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'The Spanish Data Protection Agency guidance treats a language-preference cookie as exempt from prior consent when the user personally chooses that preference and the cookie is used exclusively to provide the requested setting. Quinnoa limits this cookie to that purpose.'
				: 'La guía de la Agencia Española de Protección de Datos considera exenta de consentimiento previo una cookie de preferencia de idioma cuando es el propio usuario quien elige esa configuración y la cookie se utiliza exclusivamente para prestar la preferencia solicitada. Quinnoa limita esta cookie a esa finalidad.' ); ?></p>
			<p>
				<a href="https://www.aepd.es/guias/guia-cookies.pdf" rel="noopener noreferrer" target="_blank"><?php echo esc_html( $english ? 'Spanish Data Protection Agency cookie guidance' : 'Guía sobre el uso de las cookies de la AEPD' ); ?></a>
			</p>
		</section>

		<section>
			<h2><?php echo esc_html( $english ? 'How to manage or delete the cookie' : 'Cómo gestionar o eliminar la cookie' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'You can change the saved value at any time by selecting another language in Quinnoa. You can also delete the cookie through your browser settings. If you delete it, Quinnoa may again use your browser language when you later enter through the Spanish home page.'
				: 'Puedes cambiar el valor guardado en cualquier momento seleccionando otro idioma en Quinnoa. También puedes eliminar la cookie desde la configuración de tu navegador. Si la borras, Quinnoa podrá volver a utilizar el idioma del navegador cuando entres posteriormente por la portada en español.' ); ?></p>
		</section>

		<section>
			<h2><?php echo esc_html( $english ? 'Advertising consent and future changes' : 'Consentimiento publicitario y cambios futuros' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'If Quinnoa serves ads to users in the EEA, the United Kingdom or Switzerland in a way that requires Google-certified consent management, the site will use the consent mechanism required for that advertising configuration. The current preference panel should not be interpreted as a substitute for a certified advertising CMP where Google requires one.'
				: 'Si Quinnoa sirve anuncios a usuarios del EEE, Reino Unido o Suiza de una forma que requiera gestión de consentimiento certificada por Google, la web utilizará el mecanismo de consentimiento exigido para esa configuración publicitaria. El panel de preferencias actual no debe interpretarse como sustituto de una CMP publicitaria certificada cuando Google la requiera.' ); ?></p>
			<p><?php echo esc_html( $english ? 'For questions about this policy, use the Contact page.' : 'Para cualquier consulta sobre esta política, utiliza la página de Contacto.' ); ?> <a href="<?php echo esc_url( $contact ); ?>"><?php echo esc_html( $english ? 'Contact' : 'Contacto' ); ?></a>.</p>
		</section>
	</div>
</div>

<?php get_footer(); ?>
