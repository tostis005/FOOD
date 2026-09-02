<?php
$food_card_category = function_exists( 'food_get_primary_food_category' ) ? food_get_primary_food_category() : null;
$food_card_topic    = function_exists( 'food_get_primary_topic' ) ? food_get_primary_topic() : null;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<div class="card-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'food-card', array( 'loading' => 'lazy' ) ); ?>
			<?php else : ?>
				<div class="card-placeholder card-placeholder-illustrated family-<?php echo esc_attr( $food_card_category ? $food_card_category->slug : 'general' ); ?>" aria-hidden="true">
					<?php echo function_exists( 'food_category_icon_svg' ) ? food_category_icon_svg( $food_card_category ? $food_card_category->slug : '' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="card-kicker">
				<?php
				$labels = array();
				if ( $food_card_category ) {
					$labels[] = $food_card_category->name;
				}
				if ( $food_card_topic ) {
					$labels[] = $food_card_topic->name;
				}
				echo esc_html( ! empty( $labels ) ? implode( ' · ', $labels ) : __( 'Guía FOOD', 'food' ) );
				?>
			</div>
			<h2 class="card-title"><?php the_title(); ?></h2>
			<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="card-meta"><span>Guía FOOD</span><span>·</span><span><?php echo esc_html( food_reading_time() ); ?></span></div>
		</div>
	</a>
</article>
