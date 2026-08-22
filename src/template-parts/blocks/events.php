<?php
/**
 * BLOCK: Events.
 *
 * Lists events from the rf_event post type: upcoming (soonest first), then
 * past (most recent first) under a quieter sub-heading. Each row links to
 * the event's own page, and an upcoming event with a booking link gets it
 * right there in the list.
 *
 * ACF fields: eyebrow · heading · tag · intro · show · limit ·
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
		'past_heading' => '',
		'empty_text'   => '',
		'classes'      => '',
		'anchor'       => '',
	)
);

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2' ), true ) ? $rf['tag'] : 'h1';

$rf_upcoming = 'past' === $rf['show'] ? array() : rise_and_flour_events( 'upcoming', $rf['limit'] );
$rf_past     = 'upcoming' === $rf['show'] ? array() : rise_and_flour_events( 'past', $rf['limit'] );

$rf_past_heading = $rf['past_heading'] ? $rf['past_heading'] : __( 'Past events', 'rise-and-flour' );
$rf_empty_text   = $rf['empty_text'] ? $rf['empty_text'] : __( 'Nothing in the diary just yet — check back soon.', 'rise-and-flour' );

/**
 * One event row.
 *
 * @param WP_Post $rf_post The event.
 * @param bool    $rf_muted True for the past list.
 */
$rf_card = function ( $rf_post, $rf_muted ) {
	$rf_event = rise_and_flour_event( $rf_post );
	$rf_url   = get_permalink( $rf_post );
	// After the date: [text, class]. Price carries its own class so the
	// mobile layout can pick it out in terracotta.
	$rf_meta = array();
	foreach ( array( 'time' => '', 'location' => '', 'price' => 'event-card__price' ) as $rf_key => $rf_class ) {
		if ( '' !== $rf_event[ $rf_key ] ) {
			$rf_meta[] = array( $rf_event[ $rf_key ], $rf_class );
		}
	}
	$rf_thumb = get_post_thumbnail_id( $rf_post );
	?>
	<li class="event-card<?php echo $rf_muted ? ' event-card--past' : ''; ?>">
		<div class="event-card__date" aria-hidden="true">
			<span class="event-card__day"><?php echo esc_html( $rf_event['day'] ); ?></span>
			<span class="event-card__month"><?php echo esc_html( $rf_event['month'] ); ?></span>
		</div>

		<a class="event-card__media" href="<?php echo esc_url( $rf_url ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			rise_and_flour_media(
				array(
					'image' => $rf_thumb ? $rf_thumb : null,
					'size'  => 'medium_large',
					'label' => __( 'Event image', 'rise-and-flour' ),
				)
			);
			?>
		</a>

		<div class="event-card__body">
			<h3 class="event-card__title"><a href="<?php echo esc_url( $rf_url ); ?>"><?php echo esc_html( get_the_title( $rf_post ) ); ?></a></h3>

			<?php if ( $rf_event['date'] || $rf_meta ) : ?>
				<p class="event-card__meta">
					<?php if ( $rf_event['date'] ) : ?>
						<?php // Long form on wide screens, "Sat 12 Sep" on small ones — CSS shows one. ?>
						<time datetime="<?php echo esc_attr( $rf_event['iso'] ); ?>">
							<span class="event-card__date-long"><?php echo esc_html( $rf_event['date'] ); ?></span>
							<span class="event-card__date-short"><?php echo esc_html( $rf_event['date_short'] ); ?></span>
						</time>
					<?php endif; ?>
					<?php foreach ( $rf_meta as $rf_i => $rf_part ) : ?>
						<?php if ( $rf_i > 0 || $rf_event['date'] ) : ?>
							<span class="event-card__sep" aria-hidden="true">·</span>
						<?php endif; ?>
						<span<?php echo $rf_part[1] ? ' class="' . esc_attr( $rf_part[1] ) . '"' : ''; ?>><?php echo esc_html( $rf_part[0] ); ?></span>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>

			<?php if ( has_excerpt( $rf_post ) || $rf_post->post_content ) : ?>
				<p class="event-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $rf_post ), 32 ) ); ?></p>
			<?php endif; ?>

			<p class="event-card__actions">
				<a class="link-caps" href="<?php echo esc_url( $rf_url ); ?>"><?php esc_html_e( 'Details', 'rise-and-flour' ); ?></a>
				<?php if ( ! $rf_muted && $rf_event['booking_url'] ) : ?>
					<a class="link-caps" href="<?php echo esc_url( $rf_event['booking_url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $rf_event['booking_text'] ? $rf_event['booking_text'] : __( 'Book a place', 'rise-and-flour' ) ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</li>
	<?php
};
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
				$rf_card( $rf_post, false );
			}
			?>
		</ul>
	<?php elseif ( 'past' !== $rf['show'] ) : ?>
		<p class="block-events__empty"><?php echo esc_html( $rf_empty_text ); ?></p>
	<?php endif; ?>

	<?php if ( $rf_past ) : ?>
		<h2 class="block-events__sub"><?php echo esc_html( $rf_past_heading ); ?></h2>
		<ul class="block-events__list block-events__list--past">
			<?php
			foreach ( $rf_past as $rf_post ) {
				$rf_card( $rf_post, true );
			}
			?>
		</ul>
	<?php endif; ?>

</section>
