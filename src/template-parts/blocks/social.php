<?php
/**
 * BLOCK: Social links.
 *
 * Instagram and Facebook as white line icons, side by side. The addresses
 * live on Site Details → Social so they're typed once; an icon whose
 * address is empty is left out, and with neither the block prints nothing
 * (a note in the editor says where to add them).
 *
 *   circles — each icon in an ink circle, sitting on the cream page.
 *   band    — bare icons on an ink strip edge to edge.
 *
 * ACF fields: eyebrow · style
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'    => '',
		'style'      => 'circles',
		'is_preview' => false,
		'classes'    => '',
		'anchor'     => '',
	)
);

$rf_style = in_array( $rf['style'], array( 'circles', 'band' ), true ) ? $rf['style'] : 'circles';

// Line icons drawn on a 24px grid; the stroke comes from the stylesheet.
$rf_networks = array(
	'instagram' => array(
		'label' => __( 'Instagram', 'rise-and-flour' ),
		'url'   => rise_and_flour_option( 'social_instagram' ),
		'icon'  => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle class="block-social__dot" cx="17.25" cy="6.75" r="1.1"/>',
	),
	'facebook'  => array(
		'label' => __( 'Facebook', 'rise-and-flour' ),
		'url'   => rise_and_flour_option( 'social_facebook' ),
		'icon'  => '<path d="M10 21v-6.5H7.5V11H10V8.5a5 5 0 0 1 5-5h2V7h-2a1.5 1.5 0 0 0-1.5 1.5V11H17l-.5 3.5h-3V21z"/>',
	),
);

$rf_networks = array_filter(
	$rf_networks,
	function ( $network ) {
		return '' !== $network['url'];
	}
);

if ( ! $rf_networks ) {
	if ( $rf['is_preview'] ) {
		echo '<p class="block-social__empty">' . esc_html__( 'Social links — add the Instagram and Facebook addresses under Site Details → Social.', 'rise-and-flour' ) . '</p>';
	}
	return;
}

$rf_classes = array( 'block-social', 'block-social--' . $rf_style );
if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>
	<div class="block-social__inner">

		<?php if ( $rf['eyebrow'] ) : ?>
			<p class="eyebrow block-social__eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
		<?php endif; ?>

		<ul class="block-social__list">
			<?php foreach ( $rf_networks as $rf_key => $rf_network ) : ?>
				<li>
					<a
						class="block-social__link block-social__link--<?php echo esc_attr( $rf_key ); ?>"
						href="<?php echo esc_url( $rf_network['url'] ); ?>"
						target="_blank"
						rel="noopener"
						aria-label="<?php echo esc_attr( $rf_network['label'] ); ?>"
					>
						<svg class="block-social__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><?php echo $rf_network['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
