<?php
/**
 * BLOCK: Founder.
 *
 * A round portrait in a double terracotta ring, with the role as a caps
 * eyebrow and the name in the brand face beneath it; beside it an eyebrow,
 * heading, a short paragraph and an italic quote. For the About page.
 *
 * ACF fields: image · name · role · eyebrow · heading · tag · body · quote
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'image'       => null,
		'name'        => '',
		'role'        => '',
		'eyebrow'     => '',
		'heading'     => '',
		'tag'         => 'h2',
		'body'        => '',
		'quote'       => '',
		'placeholder' => __( 'Portrait — square', 'rise-and-flour' ),
		'classes'     => '',
		'anchor'      => '',
	)
);

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $rf['tag'] : 'h2';

$rf_classes = array( 'block-founder' );

if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<figure class="block-founder__portrait">
		<div class="block-founder__ring">
			<div class="block-founder__photo">
				<?php
				rise_and_flour_media(
					array(
						'image' => $rf['image'],
						'label' => $rf['placeholder'],
					)
				);
				?>
			</div>
		</div>

		<?php if ( $rf['role'] || $rf['name'] ) : ?>
			<figcaption class="block-founder__caption">
				<?php if ( $rf['role'] ) : ?>
					<p class="eyebrow block-founder__role"><?php echo esc_html( $rf['role'] ); ?></p>
				<?php endif; ?>
				<?php if ( $rf['name'] ) : ?>
					<p class="block-founder__name"><?php echo esc_html( $rf['name'] ); ?></p>
				<?php endif; ?>
			</figcaption>
		<?php endif; ?>
	</figure>

	<div class="block-founder__body">

		<?php if ( $rf['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
		<?php endif; ?>

		<?php if ( $rf['heading'] ) : ?>
			<<?php echo esc_attr( $rf_tag ); ?> class="block-founder__heading"><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
		<?php endif; ?>

		<?php if ( $rf['body'] ) : ?>
			<?php echo wp_kses_post( wpautop( $rf['body'] ) ); ?>
		<?php endif; ?>

		<?php if ( $rf['quote'] ) : ?>
			<p class="block-founder__quote"><?php echo esc_html( '“' . $rf['quote'] . '”' ); ?></p>
		<?php endif; ?>

	</div>

</section>
