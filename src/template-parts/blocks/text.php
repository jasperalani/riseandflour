<?php
/**
 * BLOCK: Text.
 *
 * ACF fields: eyebrow · heading · body · variant · align · tag · link_text ·
 *             link_type · link_page · link_url
 *
 * `variant` => 'intro' is the larger setting used directly beneath the hero.
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => '',
		'heading' => '',
		'body'    => '',
		'align'   => 'center',
		'variant' => '',
		'tag'     => 'h2',
		'link_text' => '',
		'link_url'  => '',
		'classes' => '',
		'anchor'  => '',
	)
);

$rf_classes = array( 'block-text' );

if ( 'intro' === $rf['variant'] ) {
	$rf_classes[] = 'block-text--intro';
}

if ( 'left' === $rf['align'] ) {
	$rf_classes[] = 'block-text--left';
}

if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $rf['tag'] : 'h2';
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<?php if ( $rf['eyebrow'] ) : ?>
		<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
	<?php endif; ?>

	<?php if ( $rf['heading'] ) : ?>
		<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
	<?php endif; ?>

	<?php if ( $rf['body'] ) : ?>
		<?php // wpautop so blank lines in the textarea become real paragraphs. ?>
		<?php echo wp_kses_post( wpautop( $rf['body'] ) ); ?>
	<?php endif; ?>

	<?php if ( $rf['link_text'] && $rf['link_url'] ) : ?>
		<div class="block-text__actions">
			<a class="link-caps" href="<?php echo esc_url( $rf['link_url'] ); ?>"><?php echo esc_html( $rf['link_text'] ); ?></a>
		</div>
	<?php endif; ?>

</section>
