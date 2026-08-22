<?php
/**
 * BLOCK: Events.
 *
 * Lists events from the rf_event post type: upcoming (soonest first), then
 * past (most recent first) under a quieter sub-heading. Each row links to
 * the event's own page, and an upcoming event with a booking link gets it
 * right there in the list.
 *
 * Past events are shown a batch at a time: the first `past_batch` render
 * with the page and a "Show more" button fetches the next batch from the
 * rf/v1/events endpoint (see inc/events.php) and appends it — rows are
 * rendered by the same template part either way.
 *
 * ACF fields: eyebrow · heading · tag · intro · show · limit · past_batch ·
 *             past_heading · empty_text
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'      => '',
		'heading'      => '',
		'tag'          => 'h1',
		'intro'        => '',
		'show'         => 'both',
		'limit'        => 0,
		'past_batch'   => 3,
		'past_heading' => '',
		'empty_text'   => '',
		'classes'      => '',
		'anchor'       => '',
	)
);

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2' ), true ) ? $rf['tag'] : 'h1';

$rf_upcoming = 'past' === $rf['show'] ? array() : rise_and_flour_events( 'upcoming', $rf['limit'] );

$rf_past       = array();
$rf_past_total = 0;
if ( 'upcoming' !== $rf['show'] ) {
	$rf_past_query = rise_and_flour_events_query( 'past', (int) $rf['past_batch'] );
	$rf_past       = $rf_past_query->posts;
	$rf_past_total = (int) $rf_past_query->found_posts;
}
$rf_past_more = (int) $rf['past_batch'] > 0 && $rf_past_total > count( $rf_past );

$rf_past_heading = $rf['past_heading'] ? $rf['past_heading'] : __( 'Past events', 'rise-and-flour' );
$rf_empty_text   = $rf['empty_text'] ? $rf['empty_text'] : __( 'Nothing in the diary just yet — check back soon.', 'rise-and-flour' );
?>

<section
	class="block-events <?php echo esc_attr( $rf['classes'] ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<?php if ( $rf['eyebrow'] || $rf['heading'] || $rf['intro'] ) : ?>
		<header class="block-events__head">
			<?php if ( $rf['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( $rf['heading'] ) : ?>
				<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
			<?php endif; ?>
			<?php if ( $rf['intro'] ) : ?>
				<div class="block-events__intro"><?php echo wp_kses_post( wpautop( $rf['intro'] ) ); ?></div>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<?php if ( $rf_upcoming ) : ?>
		<ul class="block-events__list">
			<?php
			foreach ( $rf_upcoming as $rf_post ) {
				get_template_part(
					'template-parts/event-card',
					null,
					array(
						'post'  => $rf_post,
						'muted' => false,
					)
				);
			}
			?>
		</ul>
	<?php elseif ( 'past' !== $rf['show'] ) : ?>
		<p class="block-events__empty"><?php echo esc_html( $rf_empty_text ); ?></p>
	<?php endif; ?>

	<?php if ( $rf_past ) : ?>
		<h2 class="block-events__sub"><?php echo esc_html( $rf_past_heading ); ?></h2>
		<ul class="block-events__list block-events__list--past" data-events-past>
			<?php
			foreach ( $rf_past as $rf_post ) {
				get_template_part(
					'template-parts/event-card',
					null,
					array(
						'post'  => $rf_post,
						'muted' => true,
					)
				);
			}
			?>
		</ul>

		<?php if ( $rf_past_more ) : ?>
			<div class="block-events__more">
				<button
					class="block-events__more-btn"
					type="button"
					data-events-more
					data-endpoint="<?php echo esc_url( rest_url( 'rf/v1/events' ) ); ?>"
					data-offset="<?php echo (int) count( $rf_past ); ?>"
					data-batch="<?php echo (int) $rf['past_batch']; ?>"
					data-total="<?php echo (int) $rf_past_total; ?>"
					data-label="<?php esc_attr_e( 'Show more', 'rise-and-flour' ); ?>"
					data-loading="<?php esc_attr_e( 'Loading…', 'rise-and-flour' ); ?>"
					data-error="<?php esc_attr_e( 'Couldn’t load more — try again', 'rise-and-flour' ); ?>"
				><?php esc_html_e( 'Show more', 'rise-and-flour' ); ?></button>
				<p class="block-events__count" data-events-count aria-live="polite">
					<?php
					printf(
						/* translators: 1: shown so far, 2: total. */
						esc_html__( 'Showing %1$d of %2$d', 'rise-and-flour' ),
						(int) count( $rf_past ),
						(int) $rf_past_total
					);
					?>
				</p>
			</div>
		<?php endif; ?>
	<?php endif; ?>

</section>
