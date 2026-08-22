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
			<img src="<?php echo esc_url( $rf['logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		</div>
	<?php endif; ?>

	<?php if ( $rf['caption'] ) : ?>
		<p class="hero__caption"><?php echo esc_html( $rf['caption'] ); ?></p>
	<?php endif; ?>

</section>
