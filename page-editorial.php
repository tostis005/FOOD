<?php
get_header();

$key      = get_query_var( 'food_editorial_page' );
$language = function_exists( 'food_current_language' ) ? food_current_language() : 'es';
$pages    = function_exists( 'food_editorial_pages' ) ? food_editorial_pages() : array();
$page     = isset( $pages[ $key ][ $language ] ) ? $pages[ $key ][ $language ] : null;

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
		<div class="eyebrow"><?php echo esc_html( $page['eyebrow'] ); ?></div>
		<h1><?php echo esc_html( $page['title'] ); ?></h1>
		<p><?php echo esc_html( $page['intro'] ); ?></p>
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
