<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package riseandflour
 */

get_header();

$fields = get_fields() ?? [];

?>

<div class="container">
	<main id="primary" class="site-main col">

		<div class="hero-image" style=~>
			<img src='<?= $fields['hero_image']['url'] ?>'/>
		</div>

	</main><!-- #main -->
</div>
<?php
// get_sidebar();
get_footer();
