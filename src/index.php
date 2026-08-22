<?php
/**
 * Fallback template.
 *
 * WordPress requires this file. The café site is built from pages, so nothing
 * should normally route here — it exists so archives and stray queries still
 * render in the site's own styling rather than falling apart.
 *
 * @package Rise_&_Flour
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php if ( have_posts() ) : ?>

		<header class="block-text section--default">
			<h1>
				<?php
				if ( is_home() ) {
					single_post_title();
				} else {
					the_archive_title();
				}
				?>
			</h1>
		</header>

		<div class="entry-list section--tight section--last">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'entry-list__item' ); ?>>
					<h2 class="entry-list__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="entry-list__excerpt"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;

			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => '&#8592;',
					'next_text' => '&#8594;',
				)
			);
			?>
		</div>

	<?php else : ?>

		<section class="block-text section--default section--last">
			<h1><?php esc_html_e( 'Nothing here', 'rise-and-flour' ); ?></h1>
			<p><?php esc_html_e( 'There is nothing to show at this address.', 'rise-and-flour' ); ?></p>
		</section>

	<?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();
