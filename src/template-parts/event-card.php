<?php
/**
 * One row in an events list.
 *
 * Shared by the Events block and the "show more" REST endpoint, so rows
 * loaded later are identical to the ones rendered with the page.
 *
 * @package Rise_&_Flour
 *
 * @var array $args { post: WP_Post, muted: bool }
 */

$rf_post  = isset( $args['post'] ) ? get_post( $args['post'] ) : null;
$rf_muted = ! empty( $args['muted'] );

if ( ! $rf_post ) {
	return;
}

$rf_event = rise_and_flour_event( $rf_post );
$rf_url   = get_permalink( $rf_post );

// After the date: [text, class]. Price carries its own class so the mobile
// layout can pick it out in terracotta.
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
