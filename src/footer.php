<?php
/**
 * Site footer.
 *
 * --full on the front page (stamp + three info columns), --slim everywhere
 * else. The details are editable under "Site Details" in wp-admin; the
 * fallbacks mirror the design's original values.
 *
 * @package Rise_&_Flour
 */

$rise_and_flour_full = is_front_page();

$rise_and_flour_address  = rise_and_flour_option( 'footer_address', '14 Market Place<br>Cirencester GL7 2NW' );
$rise_and_flour_hours    = rise_and_flour_option( 'footer_hours', 'Tue – Sat, 8am – 4pm<br>Sun, 9am – 2pm' );
$rise_and_flour_email    = rise_and_flour_option( 'footer_email', 'hello@riseandflour.co.uk' );
$rise_and_flour_phone    = rise_and_flour_option( 'footer_phone', '01285 000 000' );
$rise_and_flour_est_line = rise_and_flour_option( 'footer_est_line', '· Est. Cirencester ·' );
$rise_and_flour_slim     = rise_and_flour_option( 'footer_slim_line', '14 Market Place · Cirencester' );

// Brand wordmark with the terracotta ampersand.
$rise_and_flour_wordmark = sprintf(
	/* translators: %s: terracotta ampersand markup. */
	esc_html__( 'Rise %s Flour', 'rise-and-flour' ),
	'<span class="amp">&amp;</span>'
);

// Legal links: the Privacy Policy page WordPress knows about (Settings →
// Privacy) and the Terms of Use page chosen under Site Details → Legal
// pages (falling back to a page at /terms-of-use/). Each appears only once
// published, so nothing links to a draft.
$rise_and_flour_legal = array();

$rise_and_flour_privacy_url = get_privacy_policy_url();
if ( $rise_and_flour_privacy_url ) {
	$rise_and_flour_legal[ $rise_and_flour_privacy_url ] = __( 'Privacy Policy', 'rise-and-flour' );
}

$rise_and_flour_terms_id = (int) rise_and_flour_option( 'terms_page', 0 );
$rise_and_flour_terms    = $rise_and_flour_terms_id ? get_post( $rise_and_flour_terms_id ) : get_page_by_path( 'terms-of-use' );
if ( $rise_and_flour_terms && 'page' === $rise_and_flour_terms->post_type && 'publish' === $rise_and_flour_terms->post_status ) {
	$rise_and_flour_legal[ get_permalink( $rise_and_flour_terms ) ] = __( 'Terms of Use', 'rise-and-flour' );
}

/**
 * Print the legal links row, if there are any.
 *
 * @param array $links URL => label.
 */
function rise_and_flour_footer_legal( $links ) {
	if ( ! $links ) {
		return;
	}
	echo '<nav class="site-footer__legal" aria-label="' . esc_attr__( 'Legal', 'rise-and-flour' ) . '">';
	foreach ( $links as $url => $label ) {
		printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</nav>';
}
?>

<footer id="colophon" class="site-footer <?php echo $rise_and_flour_full ? 'site-footer--full' : 'site-footer--slim'; ?>">

	<?php if ( $rise_and_flour_full ) : ?>
		<div class="site-footer__inner">
			<img
				class="site-footer__stamp"
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-stamp.png' ); ?>"
				alt="<?php esc_attr_e( 'Rise &amp; Flour stamp', 'rise-and-flour' ); ?>"
			>
			<div>
				<p class="site-footer__label"><?php esc_html_e( 'Find us', 'rise-and-flour' ); ?></p>
				<p class="site-footer__text"><?php echo wp_kses( $rise_and_flour_address, array( 'br' => array() ) ); ?></p>
			</div>
			<div>
				<p class="site-footer__label"><?php esc_html_e( 'Hours', 'rise-and-flour' ); ?></p>
				<p class="site-footer__text"><?php echo wp_kses( $rise_and_flour_hours, array( 'br' => array() ) ); ?></p>
			</div>
			<div>
				<p class="site-footer__label"><?php esc_html_e( 'Say hello', 'rise-and-flour' ); ?></p>
				<p class="site-footer__text"><?php echo esc_html( $rise_and_flour_email ); ?><br><?php echo esc_html( $rise_and_flour_phone ); ?></p>
			</div>
		</div>

		<div class="site-footer__baseline">
			<a class="site-footer__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo wp_kses_post( $rise_and_flour_wordmark ); ?></a>
			<?php rise_and_flour_footer_legal( $rise_and_flour_legal ); ?>
			<span><?php echo esc_html( $rise_and_flour_est_line ); ?></span>
		</div>

	<?php else : ?>
		<div class="site-footer__baseline">
			<a class="site-footer__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo wp_kses_post( $rise_and_flour_wordmark ); ?></a>
			<?php rise_and_flour_footer_legal( $rise_and_flour_legal ); ?>
			<span><?php echo esc_html( $rise_and_flour_slim ); ?></span>
		</div>
	<?php endif; ?>

</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
