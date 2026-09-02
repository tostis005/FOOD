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
				echo esc_html( ! empty( $labels ) ? implode( ' · ', $labels ) : ( $food_english ? 'Quinnoa guide' : 'Guía Quinnoa' ) );
				?>
			</div>
			<h2 class="card-title"><?php the_title(); ?></h2>
			<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="card-meta"><span><?php echo esc_html( function_exists( 'food_localized_reading_time' ) ? food_localized_reading_time() : food_reading_time() ); ?></span></div>
		</div>
	</a>
</article>
