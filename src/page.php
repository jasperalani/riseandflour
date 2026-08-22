<?php
/**
 * The template for displaying pages — including the front page.
 *
 * There is deliberately no front-page.php: every page, homepage included, is
 * built by dragging ACF blocks in the editor. header.php still switches nav
 * variants on is_front_page().
 *
 * @package Rise_&_Flour
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		// A page opening with a hero states its own title; a second heading
		// above it would fight the design.
		$rise_and_flour_has_hero = has_block( 'acf/rf-hero', get_post() );
		?>

		<?php if ( ! $rise_and_flour_has_hero ) : ?>
			<header class="block-text section--default">
				<h1><?php the_title(); ?></h1>
			</header>
		<?php endif; ?>

		<div class="entry-content page-blocks <?php echo $rise_and_flour_has_hero ? '' : 'section--tight'; ?>">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>

		<?php
	endwhile;
	?>

</main><!-- #primary -->

<?php
get_footer();
