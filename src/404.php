<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package riseandflour
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title text-center m-5" style="font-family: 'Roboto', sans-serif;"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'riseandflour' ); ?></h1>
			</header><!-- .page-header -->


		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
wp_footer();
// get_footer();
