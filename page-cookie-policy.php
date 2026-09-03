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
			? 'This policy explains which cookies Quinnoa uses, why they are used and how you can manage them. Last updated: September 3, 2026.'
			: 'Esta política explica qué cookies utiliza Quinnoa, para qué se usan y cómo puedes gestionarlas. Última actualización: 3 de septiembre de 2026.' ); ?></p>
	</header>

	<div class="editorial-page-content">
		<section>
			<h2><?php echo esc_html( $english ? 'What a cookie is' : 'Qué es una cookie' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'A cookie is a small file that a website can store in the browser to remember information between visits. Depending on their purpose, cookies may be used for technical functions, preferences, measurement or advertising.'
				: 'Una cookie es un pequeño archivo que un sitio web puede guardar en el navegador para recordar información entre visitas. Según su finalidad, las cookies pueden utilizarse para funciones técnicas, preferencias, medición o publicidad.' ); ?></p>
		</section>

		<section>
			<h2><?php echo esc_html( $english ? 'Cookie used by Quinnoa' : 'Cookie utilizada por Quinnoa' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'In normal public browsing, Quinnoa uses one first-party preference cookie to remember the language that you have explicitly selected. It is not used to identify you, build a profile, measure browsing behaviour or personalize advertising.'
				: 'En la navegación pública ordinaria, Quinnoa utiliza una única cookie propia de preferencia para recordar el idioma que has seleccionado expresamente. No se utiliza para identificarte, elaborar perfiles, medir tu navegación ni personalizar publicidad.' ); ?></p>
			<ul>
				<li><strong>quinnoa_language</strong> — <?php echo esc_html( $english
					? 'Provider: Quinnoa. Type: first-party functional/preference cookie. Purpose: remember whether you selected Spanish or English. Duration: 6 months.'
					: 'Proveedor: Quinnoa. Tipo: cookie propia funcional/de preferencia. Finalidad: recordar si has elegido español o inglés. Duración: 6 meses.' ); ?></li>
			</ul>
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
			<h2><?php echo esc_html( $english ? 'Future changes and non-essential cookies' : 'Cambios futuros y cookies no necesarias' ); ?></h2>
			<p><?php echo esc_html( $english
				? 'Quinnoa does not use the language preference for analytics, advertising or profiling. If the site later introduces cookies or similar technologies that are not exempt from consent, they will not be activated before the corresponding information and consent mechanism has been implemented.'
				: 'Quinnoa no utiliza la preferencia de idioma para analítica, publicidad ni elaboración de perfiles. Si el sitio incorpora en el futuro cookies o tecnologías similares que no estén exentas de consentimiento, no se activarán hasta que se haya implantado la información y el mecanismo de consentimiento correspondientes.' ); ?></p>
			<p><?php echo esc_html( $english ? 'For questions about this policy, use the Contact page.' : 'Para cualquier consulta sobre esta política, utiliza la página de Contacto.' ); ?> <a href="<?php echo esc_url( $contact ); ?>"><?php echo esc_html( $english ? 'Contact' : 'Contacto' ); ?></a>.</p>
		</section>
	</div>
</div>

<?php get_footer(); ?>
