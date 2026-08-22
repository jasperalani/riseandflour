<?php
/**
 * BLOCK: Image carousel.
 *
 * One focused slide sits centred on the page with the next one peeking in
 * from the right. Moving on, the focused slide wipes closed from its right
 * edge (its left edge never moves) while the next slides into its place.
 * Captions float over the image and roll vertically — down for "next",
 * up for "previous" — like a second carousel riding on the first.
 *
 * The initial state is rendered server-side (--rel per slide, .is-active on
 * the first) so there's no layout flash before main.js takes over, and the
 * block looks right in the editor where main.js never runs.
 *
 * ACF fields: heading · slides (repeater: image + caption)
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'heading' => '',
		'slides'  => array(),
		'classes' => '',
		'anchor'  => '',
	)
);

if ( empty( $rf['slides'] ) ) {
	return;
}

$rf_slides = array();
foreach ( $rf['slides'] as $rf_slide ) {
	$rf_slides[] = wp_parse_args(
		$rf_slide,
		array(
			'image'       => null,
			'caption'     => '',
			'placeholder' => __( 'Slide image', 'rise-and-flour' ),
		)
	);
}

$rf_total = count( $rf_slides );
?>

<section
	class="carousel <?php echo esc_attr( $rf['classes'] ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
	data-carousel
>

	<?php // Only emitted with something to show — an empty head would still carry its margin. ?>
	<?php if ( $rf['heading'] || $rf_total > 1 ) : ?>
		<div class="carousel__head">
			<div>
				<?php if ( $rf['heading'] ) : ?>
					<h2><?php echo esc_html( $rf['heading'] ); ?></h2>
				<?php endif; ?>
			</div>

			<?php if ( $rf_total > 1 ) : ?>
				<?php // aria-disabled rather than disabled: a disabled control drops keyboard focus, which would strand arrow-key users at either end. ?>
				<div class="carousel__controls">
					<button class="carousel__btn" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous slide', 'rise-and-flour' ); ?>" aria-disabled="true">&#8592;</button>
					<button class="carousel__btn" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next slide', 'rise-and-flour' ); ?>" aria-disabled="false">&#8594;</button>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="carousel__stage" data-carousel-stage>
		<div class="carousel__slides">
			<?php foreach ( $rf_slides as $rf_i => $rf_slide ) : ?>
				<div
					class="carousel__slide<?php echo 0 === $rf_i ? ' is-active' : ''; ?>"
					style="--rel: <?php echo (int) $rf_i; ?>"
					data-carousel-slide
					aria-hidden="<?php echo 0 === $rf_i ? 'false' : 'true'; ?>"
				>
					<div class="carousel__media">
						<?php
						rise_and_flour_media(
							array(
								'image' => $rf_slide['image'],
								'label' => $rf_slide['placeholder'],
								// Darker placeholder, as in the hero — a cream caption sits on it.
								'class' => 'ph--hero',
							)
						);
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php // One caption per slide, even when empty, so indexes line up. ?>
		<div class="carousel__captions" aria-live="polite">
			<?php foreach ( $rf_slides as $rf_i => $rf_slide ) : ?>
				<p
					class="carousel__caption<?php echo 0 === $rf_i ? ' is-active' : ''; ?>"
					data-carousel-caption
					aria-hidden="<?php echo 0 === $rf_i ? 'false' : 'true'; ?>"
				><?php echo esc_html( $rf_slide['caption'] ); ?></p>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( $rf_total > 1 ) : ?>
		<div class="carousel__pager">
			<?php for ( $rf_i = 0; $rf_i < $rf_total; $rf_i++ ) : ?>
				<button
					class="carousel__dash<?php echo 0 === $rf_i ? ' is-active' : ''; ?>"
					type="button"
					data-carousel-go="<?php echo (int) $rf_i; ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number. */ __( 'Go to slide %d', 'rise-and-flour' ), $rf_i + 1 ) ); ?>"
					<?php echo 0 === $rf_i ? 'aria-current="true"' : ''; ?>
				></button>
			<?php endfor; ?>
		</div>
	<?php endif; ?>

</section>
