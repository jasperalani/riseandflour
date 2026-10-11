<?php
/**
 * BLOCK: Location.
 *
 * Address, hours and contact details beside a map. Address, phone, email
 * and hours fall back to Site Details, so the usual case is to leave those
 * fields empty and keep one source of truth for the footer and this page.
 *
 * The map is a Google Maps embed: paste either the embed URL or the whole
 * <iframe> snippet from "Share → Embed a map". Only Google Maps embed URLs
 * (and OpenStreetMap's) are allowed through; anything else shows the grey
 * placeholder rather than an arbitrary iframe.
 *
 * ACF fields: eyebrow · heading · tag · intro · address · phone · email ·
 *             show_hours · note · directions_url · map · map_position
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'        => '',
		'heading'        => '',
		'tag'            => 'h1',
		'intro'          => '',
		'address'        => '',
		'phone'          => '',
		'email'          => '',
		'show_hours'     => true,
		'note_label'     => '',
		'note'           => '',
		'directions_url' => '',
		'map'            => '',
		'map_position'   => 'right',
		'classes'        => '',
		'anchor'         => '',
	)
);

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2' ), true ) ? $rf['tag'] : 'h1';

// Site Details fallbacks. The option values carry <br>s (new_lines => br);
// the block's own textareas are plain, so both go through nl2br + kses.
$rf_address = $rf['address'] ? $rf['address'] : rise_and_flour_option( 'footer_address', '' );
$rf_phone   = $rf['phone'] ? $rf['phone'] : rise_and_flour_option( 'footer_phone', '' );
$rf_email   = $rf['email'] ? $rf['email'] : rise_and_flour_option( 'footer_email', '' );
$rf_hours   = $rf['show_hours'] ? rise_and_flour_hours_value() : '';

$rf_lines = function ( $text ) {
	// Collapse any existing <br>s (and the newline that follows them) to a
	// plain newline first, or nl2br would double every break.
	$text = preg_replace( '/<br\s*\/?>\s*/i', "\n", (string) $text );
	return wp_kses( nl2br( trim( $text ) ), array( 'br' => array() ) );
};

// Resolve the map field to a safe embed URL, or '' for the placeholder.
$rf_map_src = '';
if ( $rf['map'] ) {
	$rf_candidate = trim( (string) $rf['map'] );
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $rf_candidate, $rf_m ) ) {
		$rf_candidate = $rf_m[1];
	}
	$rf_candidate = html_entity_decode( $rf_candidate, ENT_QUOTES );
	$rf_host      = wp_parse_url( $rf_candidate, PHP_URL_HOST );
	$rf_path      = (string) wp_parse_url( $rf_candidate, PHP_URL_PATH );
	$rf_ok        =
		( in_array( $rf_host, array( 'www.google.com', 'maps.google.com', 'google.com' ), true ) && 0 === strpos( $rf_path, '/maps/embed' ) )
		|| ( 'www.openstreetmap.org' === $rf_host && 0 === strpos( $rf_path, '/export/embed' ) );
	if ( $rf_ok ) {
		$rf_map_src = $rf_candidate;
	}
}

$rf_classes = array( 'block-location' );
if ( 'left' === $rf['map_position'] ) {
	$rf_classes[] = 'block-location--map-left';
}
if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}

$rf_tel = preg_replace( '/[^0-9+]/', '', (string) $rf_phone );
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<div class="block-location__body">

		<?php if ( $rf['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
		<?php endif; ?>

		<?php if ( $rf['heading'] ) : ?>
			<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
		<?php endif; ?>

		<?php if ( $rf['intro'] ) : ?>
			<div class="block-location__intro"><?php echo wp_kses_post( wpautop( $rf['intro'] ) ); ?></div>
		<?php endif; ?>

		<dl class="block-location__details">
			<?php if ( $rf_address ) : ?>
				<div class="block-location__row">
					<dt><?php esc_html_e( 'Address', 'rise-and-flour' ); ?></dt>
					<dd><?php echo $rf_lines( $rf_address ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- kses'd above. ?></dd>
				</div>
			<?php endif; ?>

			<?php if ( $rf_hours ) : ?>
				<div class="block-location__row">
					<dt><?php esc_html_e( 'Hours', 'rise-and-flour' ); ?></dt>
					<dd>
						<?php echo $rf_lines( $rf_hours ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</dd>
				</div>
			<?php endif; ?>

			<?php if ( $rf['note'] ) : ?>
				<div class="block-location__row">
					<dt><?php echo esc_html( $rf['note_label'] ? $rf['note_label'] : __( 'Getting here', 'rise-and-flour' ) ); ?></dt>
					<dd class="block-location__note"><?php echo wp_kses_post( wpautop( $rf['note'] ) ); ?></dd>
				</div>
			<?php endif; ?>

			<?php if ( $rf_email || $rf_phone ) : ?>
				<div class="block-location__row">
					<dt><?php esc_html_e( 'Contact', 'rise-and-flour' ); ?></dt>
					<dd>
						<?php if ( $rf_email ) : ?>
							<a class="block-location__accent" href="mailto:<?php echo esc_attr( $rf_email ); ?>"><?php echo esc_html( $rf_email ); ?></a><br>
						<?php endif; ?>
						<?php if ( $rf_phone ) : ?>
							<a href="tel:<?php echo esc_attr( $rf_tel ); ?>"><?php echo esc_html( $rf_phone ); ?></a>
						<?php endif; ?>
					</dd>
				</div>
			<?php endif; ?>
		</dl>

		<?php if ( $rf['directions_url'] ) : ?>
			<a class="link-caps block-location__directions" href="<?php echo esc_url( $rf['directions_url'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get directions', 'rise-and-flour' ); ?></a>
		<?php endif; ?>

	</div>

	<div class="block-location__map">
		<?php if ( $rf_map_src ) : ?>
			<iframe
				src="<?php echo esc_url( $rf_map_src ); ?>"
				title="<?php esc_attr_e( 'Map showing where to find Rise & Flour', 'rise-and-flour' ); ?>"
				loading="lazy"
				allowfullscreen
				referrerpolicy="no-referrer-when-downgrade"
			></iframe>
		<?php else : ?>
			<div class="ph"><?php esc_html_e( 'Map', 'rise-and-flour' ); ?></div>
		<?php endif; ?>
	</div>

</section>
