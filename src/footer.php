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
			<span><?php echo wp_kses_post( $rise_and_flour_wordmark ); ?></span>
			<span><?php echo esc_html( $rise_and_flour_est_line ); ?></span>
		</div>

	<?php else : ?>
		<div class="site-footer__baseline">
			<span><?php echo wp_kses_post( $rise_and_flour_wordmark ); ?></span>
			<span><?php echo esc_html( $rise_and_flour_slim ); ?></span>
		</div>
	<?php endif; ?>

</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
