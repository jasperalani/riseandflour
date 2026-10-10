<?php
/**
 * BLOCK: Full-page hero.
 *
 * Future ACF fields: image · show_logo · logo · caption
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'image'       => null,
		'show_logo'   => true,
		'logo'        => get_template_directory_uri() . '/assets/img/logo-rect-stamp.png',
		'caption'     => '',
		'placeholder' => __( 'Full-page hero', 'rise-and-flour' ),
		'classes'     => '',
		'anchor'      => '',
	)
);
?>

<section
	class="hero <?php echo esc_attr( trim( ( $rf['show_logo'] ? '' : 'hero--no-logo ' ) . $rf['classes'] ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<div class="hero__media">
		<?php
		rise_and_flour_media(
			array(
				'image' => $rf['image'],
				'label' => $rf['placeholder'],
				'class' => 'ph--hero',
			)
		);
		?>
	</div>

	<div class="hero__scrim"></div>

	<?php if ( $rf['show_logo'] && $rf['logo'] ) : ?>
		<div class="hero__logo">
			<?php
			// A Media Library logo gets a srcset so the browser fetches a size
			// near the 200–280px it is shown at, not the full upload.
			$rf_logo_id = is_array( $rf['logo'] ) && isset( $rf['logo']['ID'] ) ? (int) $rf['logo']['ID'] : $rf['logo'];
			if ( is_numeric( $rf_logo_id ) && wp_attachment_is_image( (int) $rf_logo_id ) ) :
				echo wp_get_attachment_image(
					(int) $rf_logo_id,
					'medium_large',
					false,
					array(
						'alt'     => get_bloginfo( 'name' ),
						'sizes'   => '(max-width: 640px) 200px, 280px',
						'loading' => false,
					)
				);
			else :
				?>
				<img src="<?php echo esc_url( $rf['logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $rf['caption'] ) : ?>
		<p class="hero__caption"><?php echo esc_html( $rf['caption'] ); ?></p>
	<?php endif; ?>

</section>
