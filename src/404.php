<?php
/**
 * 404 template.
 *
 * @package Rise_&_Flour
 */

get_header();
?>

<main id="primary" class="site-main">

	<section class="block-text section--default section--last">
		<p class="eyebrow"><?php esc_html_e( '· Four oh four ·', 'rise-and-flour' ); ?></p>
		<h1><?php esc_html_e( 'This page has run out', 'rise-and-flour' ); ?></h1>
		<p><?php esc_html_e( 'Like most things here, it was gone by noon. Try the menu, or come and find us on the Market Place.', 'rise-and-flour' ); ?></p>
		<p class="block-text__actions">
			<a class="link-caps" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to the café', 'rise-and-flour' ); ?>
			</a>
		</p>
	</section>

</main><!-- #primary -->

<?php
get_footer();
