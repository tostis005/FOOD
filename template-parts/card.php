<?php
$food_card_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
$food_card_topic    = function_exists( 'food_get_primary_topic' ) ? food_get_primary_topic() : null;
$food_card_visual   = function_exists( 'food_get_post_visual_context' ) ? food_get_post_visual_context() : null;
$food_english       = function_exists( 'food_is_english' ) && food_is_english();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<div class="card-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'food-card', array( 'loading' => 'lazy' ) ); ?>
			<?php else : ?>
				<div class="card-placeholder card-placeholder-illustrated <?php echo esc_attr( $food_card_visual ? $food_card_visual['class'] : 'family-general' ); ?>" aria-hidden="true">
					<?php echo $food_card_visual ? $food_card_visual['svg'] : ( function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( '' ) : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="card-kicker">
				<?php
				$labels = array();
				if ( $food_card_category ) {
					$labels[] = function_exists( 'food_family_display' ) ? food_family_display( $food_card_category->slug ) : $food_card_category->name;
				}
				if ( $food_card_topic ) {
					$labels[] = function_exists( 'food_topic_display' ) ? food_topic_display( $food_card_topic ) : $food_card_topic->name;
				}
				echo esc_html( ! empty( $labels ) ? implode( ' · ', $labels ) : ( $food_english ? 'Quinnoa article' : 'Artículo Quinnoa' ) );
				?>
			</div>
			<h2 class="card-title"><?php the_title(); ?></h2>
			<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="card-meta"><span><?php echo esc_html( function_exists( 'food_localized_reading_time' ) ? food_localized_reading_time() : food_reading_time() ); ?></span></div>
		</div>
	</a>
	<div class="card-share">
		<button
			class="card-share-button"
			type="button"
			data-share-url="<?php echo esc_url( get_permalink() ); ?>"
			data-share-title="<?php echo esc_attr( get_the_title() ); ?>"
			data-share-label="<?php echo esc_attr( $food_english ? 'Share' : 'Compartir' ); ?>"
			data-copy-label="<?php echo esc_attr( $food_english ? 'Copied' : 'Copiado' ); ?>"
		>
			<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="2.5"></circle><circle cx="6" cy="12" r="2.5"></circle><circle cx="18" cy="19" r="2.5"></circle><path d="m8.2 10.8 7.6-4.5M8.2 13.2l7.6 4.5"></path></svg>
			<span class="card-share-label"><?php echo esc_html( $food_english ? 'Share' : 'Compartir' ); ?></span>
		</button>
		<span class="card-share-status screen-reader-text" aria-live="polite"></span>
	</div>
</article>
