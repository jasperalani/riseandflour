<?php
/**
 * BLOCK: Image + Text.
 *
 * Future ACF fields: image · media_position · eyebrow · heading · body ·
 *                    link_text · link_url
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'image'          => null,
		'media_position' => 'left',
		'eyebrow'        => '',
		'heading'        => '',
		'body'           => '',
		'link_text'      => '',
		'link_url'       => '',
		'placeholder'    => __( 'Block image', 'rise-and-flour' ),
		'classes'        => '',
		'anchor'         => '',
	)
);

$rf_classes = array( 'block-image-text' );

if ( 'right' === $rf['media_position'] ) {
	$rf_classes[] = 'block-image-text--media-right';
}

if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<div class="block-image-text__media">
		<?php
		rise_and_flour_media(
			array(
				'image' => $rf['image'],
				'label' => $rf['placeholder'],
			)
		);
		?>
	</div>

	<div class="block-image-text__body">

		<?php if ( $rf['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
		<?php endif; ?>

		<?php if ( $rf['heading'] ) : ?>
			<h2><?php echo esc_html( $rf['heading'] ); ?></h2>
		<?php endif; ?>

		<?php if ( $rf['body'] ) : ?>
			<?php echo wp_kses_post( wpautop( $rf['body'] ) ); ?>
		<?php endif; ?>

		<?php if ( $rf['link_text'] && $rf['link_url'] ) : ?>
			<a class="link-caps" href="<?php echo esc_url( $rf['link_url'] ); ?>">
				<?php echo esc_html( $rf['link_text'] ); ?>
			</a>
		<?php endif; ?>

	</div>

</section>
