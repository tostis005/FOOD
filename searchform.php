<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="site-search"><?php esc_html_e( 'Buscar:', 'food' ); ?></label>
	<input id="site-search" type="search" class="search-field" placeholder="Buscar en FOOD…" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="search-submit"><?php esc_html_e( 'Buscar', 'food' ); ?></button>
</form>
