<?php
/**
 * BLOCK: Image.
 *
 * ACF fields: image · caption · width (contained|full) · height (small|large) ·
 *             position (top|middle|bottom)
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'image'       => null,
		'caption'     => '',
		'width'       => 'contained',
		'height'      => 'small',
		'position'    => 'middle',
		'placeholder' => __( 'Full-width image', 'rise-and-flour' ),
		'classes'     => '',
		'anchor'      => '',
	)
);

$rf_classes = array( 'block-image' );

if ( 'full' === $rf['width'] ) {
	$rf_classes[] = 'block-image--full';
}

if ( 'large' === $rf['height'] ) {
	$rf_classes[] = 'block-image--tall';
}

if ( in_array( $rf['position'], array( 'top', 'bottom' ), true ) ) {
	$rf_classes[] = 'block-image--focus-' . $rf['position'];
}

if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<figure
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<div class="block-image__media">
		<?php
		rise_and_flour_media(
			array(
				'image' => $rf['image'],
				'label' => $rf['placeholder'],
			)
		);
		?>
	</div>

	<?php if ( $rf['caption'] ) : ?>
		<figcaption class="block-image__caption"><?php echo esc_html( $rf['caption'] ); ?></figcaption>
	<?php endif; ?>

</figure>
