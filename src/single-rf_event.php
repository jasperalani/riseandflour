<?php
/**
 * Single event.
 *
 * Follows the "Supper Club" page from the design export: back link, centred
 * head (eyebrow · title · italic subtitle · bordered date strip), the
 * featured image (unless switched off on the event), the write-up in a narrow column, an ink "On the night"
 * panel listing the courses/lines, and a terracotta reserve button with a
 * line beneath. Every piece of text comes from the event's fields.
 *
 * @package Rise_&_Flour
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		$rise_and_flour_event = rise_and_flour_event( get_post() );
		$rise_and_flour_back  = rise_and_flour_events_page_url();

		$rise_and_flour_eyebrow = $rise_and_flour_event['eyebrow'];
		if ( ! $rise_and_flour_eyebrow ) {
			$rise_and_flour_eyebrow = $rise_and_flour_event['is_past'] ? __( 'Past event', 'rise-and-flour' ) : __( 'Event', 'rise-and-flour' );
		}

		// The date strip: [text, accent?]. Price is the one item in terracotta.
		$rise_and_flour_strip = array();
		foreach ( array( 'date_short', 'time', 'location', 'price', 'capacity' ) as $rise_and_flour_key ) {
			if ( '' !== $rise_and_flour_event[ $rise_and_flour_key ] ) {
				$rise_and_flour_strip[] = array( $rise_and_flour_event[ $rise_and_flour_key ], 'price' === $rise_and_flour_key );
			}
		}
		?>

		<article <?php post_class( 'event' ); ?>>
			<div class="event__inner">

				<?php if ( $rise_and_flour_back ) : ?>
					<a class="event__back" href="<?php echo esc_url( $rise_and_flour_back ); ?>">&#8592; <?php esc_html_e( 'All events', 'rise-and-flour' ); ?></a>
				<?php endif; ?>

				<header class="event__head">
					<p class="eyebrow event__eyebrow"><?php echo esc_html( $rise_and_flour_eyebrow ); ?></p>
					<h1 class="event__title"><?php the_title(); ?></h1>

					<?php if ( $rise_and_flour_event['subtitle'] ) : ?>
						<p class="event__subtitle"><?php echo esc_html( $rise_and_flour_event['subtitle'] ); ?></p>
					<?php endif; ?>

					<?php if ( $rise_and_flour_strip ) : ?>
						<p class="event__strip">
							<?php foreach ( $rise_and_flour_strip as $rise_and_flour_i => $rise_and_flour_part ) : ?>
								<?php if ( $rise_and_flour_i > 0 ) : ?>
									<span class="event__dot" aria-hidden="true">·</span>
								<?php endif; ?>
								<?php if ( 0 === $rise_and_flour_i && $rise_and_flour_event['iso'] ) : ?>
									<time datetime="<?php echo esc_attr( $rise_and_flour_event['iso'] ); ?>"><?php echo esc_html( $rise_and_flour_part[0] ); ?></time>
								<?php else : ?>
									<span<?php echo $rise_and_flour_part[1] ? ' class="event__accent"' : ''; ?>><?php echo esc_html( $rise_and_flour_part[0] ); ?></span>
								<?php endif; ?>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</header>

				<?php if ( $rise_and_flour_event['show_image'] && has_post_thumbnail() ) : ?>
					<div class="event__media">
						<?php
						rise_and_flour_media(
							array(
								'image' => get_post_thumbnail_id(),
								'size'  => 'large',
							)
						);
						?>
					</div>
				<?php endif; ?>

				<div class="event__body">

					<div class="event__content">
						<?php the_content(); ?>
					</div>

					<?php if ( $rise_and_flour_event['courses'] ) : ?>
						<aside class="event__panel" aria-label="<?php echo esc_attr( $rise_and_flour_event['panel_label'] ? $rise_and_flour_event['panel_label'] : __( 'On the night', 'rise-and-flour' ) ); ?>">
							<div class="event__panel-inner">
								<?php if ( $rise_and_flour_event['panel_label'] ) : ?>
									<p class="event__panel-label"><?php echo esc_html( $rise_and_flour_event['panel_label'] ); ?></p>
								<?php endif; ?>

								<ul class="event__courses">
									<?php foreach ( $rise_and_flour_event['courses'] as $rise_and_flour_line ) : ?>
										<li><?php echo esc_html( $rise_and_flour_line ); ?></li>
									<?php endforeach; ?>
								</ul>

								<?php if ( $rise_and_flour_event['panel_note'] ) : ?>
									<p class="event__panel-label event__panel-note"><?php echo esc_html( $rise_and_flour_event['panel_note'] ); ?></p>
								<?php endif; ?>
							</div>
						</aside>
					<?php endif; ?>

					<div class="event__cta">
						<?php if ( $rise_and_flour_event['is_past'] ) : ?>
							<p class="event__passed"><?php esc_html_e( 'This event has passed.', 'rise-and-flour' ); ?></p>
						<?php elseif ( $rise_and_flour_event['booking_url'] ) : ?>
							<a class="event__button" href="<?php echo esc_url( $rise_and_flour_event['booking_url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $rise_and_flour_event['booking_text'] ? $rise_and_flour_event['booking_text'] : __( 'Book a place', 'rise-and-flour' ) ); ?></a>
							<?php if ( $rise_and_flour_event['booking_note'] ) : ?>
								<p class="event__booking-note"><?php echo esc_html( $rise_and_flour_event['booking_note'] ); ?></p>
							<?php endif; ?>
						<?php endif; ?>
					</div>

				</div>

			</div>
		</article>

		<?php
	endwhile;
	?>

</main><!-- #primary -->

<?php
get_footer();
