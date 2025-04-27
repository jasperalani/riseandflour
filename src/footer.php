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

			<div class="col-lg-2 col-md-6 col-sm-12 col-12 site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					Rise & Flour
				</a>
			</div>

			<div class="col-lg-1 col-md-6" id="social-icons">

				<div style="display: flex;">

					<a target="_blank" href="<?= get_field('instagram') ?>">
						<i id='instagram-icon' class="fa-brands fa-instagram"></i>
					</a>

					<a target="_blank" href="<?= get_field('facebook') ?>">
						<i id='facebook-icon' class="fa-brands fa-facebook"></i>
					</a>

				</div>

			</div>

			<div class="col-lg-9 col-md-12">

				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
					)
				);

		// <div class="d-flex justify-content-center flex-column back-to-top">
		// 	<i class="fa-solid fa-chevron-up"></i>
		// </div> 

				?>
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
