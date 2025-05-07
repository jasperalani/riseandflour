<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package riseandflour
 */

?>

<footer id="footer">
	<div class="container">

		<div class="row">

			<div class="col-lg-6 col-sm-12 col-12 site-title">
				<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
					Rise & Flour
				</a>
			</div>

			<div class="col-lg-6" id="social-icons">

				<div style="display: flex;">

					<a target="_blank" href="https://www.instagram.com/rise.and.flour/?hl=en">
						<i id='instagram-icon' class="fa-brands fa-instagram"></i>
					</a>

					<a target="_blank" href="https://www.facebook.com/p/Rise-Flour-Artisan-Sourdough-Bakery-Cafe-100082989786663/">
						<i id='facebook-icon' class="fa-brands fa-facebook"></i>
					</a>

				</div>

			</div>

		</div> <!-- end row -->

	</div>
</footer><!-- #colophon -->

</div><!-- #page -->

<?php

footer_credit();
show_scroll_to_top_button();
wp_footer();

?>

</body>
</html>
