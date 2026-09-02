<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<div class="card-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'food-card', array( 'loading' => 'lazy' ) ); ?>
			<?php else : ?>
				<div class="card-placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="card-kicker">
				<?php $cats = get_the_category(); echo ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Guía', 'food' ); ?>
			</div>
			<h2 class="card-title"><?php the_title(); ?></h2>
			<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="card-meta"><span><?php echo esc_html( get_the_date() ); ?></span><span>·</span><span><?php echo esc_html( food_reading_time() ); ?></span></div>
		</div>
	</a>
</article>
