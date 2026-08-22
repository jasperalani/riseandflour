<?php
/**
 * Events.
 *
 * A custom post type so each event gets its own page (single-rf_event.php),
 * with the Events block (blocks/events) listing them on the Events page —
 * upcoming first, then past. Dates live in ACF fields (see acf-fields.php);
 * the helpers here turn them into something templates can print.
 *
 * URLs: /events/ is the ordinary Events page; /events/<slug>/ is an event.
 * The post type has no archive of its own, so the page is the only list.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the post type.
 */
function rise_and_flour_register_events() {
	register_post_type(
		'rf_event',
		array(
			'labels'        => array(
				'name'               => __( 'Events', 'rise-and-flour' ),
				'singular_name'      => __( 'Event', 'rise-and-flour' ),
				'add_new'            => __( 'Add event', 'rise-and-flour' ),
				'add_new_item'       => __( 'Add event', 'rise-and-flour' ),
				'edit_item'          => __( 'Edit event', 'rise-and-flour' ),
				'new_item'           => __( 'New event', 'rise-and-flour' ),
				'view_item'          => __( 'View event', 'rise-and-flour' ),
				'all_items'          => __( 'All events', 'rise-and-flour' ),
				'search_items'       => __( 'Search events', 'rise-and-flour' ),
				'not_found'          => __( 'No events yet.', 'rise-and-flour' ),
				'not_found_in_trash' => __( 'No events in the bin.', 'rise-and-flour' ),
				'menu_name'          => __( 'Events', 'rise-and-flour' ),
			),
			'description'   => __( 'Workshops, tastings and happenings at the café.', 'rise-and-flour' ),
			'public'        => true,
			'has_archive'   => false,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'       => array(
				'slug'       => 'events',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'rise_and_flour_register_events' );

/**
 * Everything a template needs to print an event's when/where.
 *
 * Start and end are stored by ACF as "Y-m-d H:i:s" in the site's timezone.
 * An event counts as past once it has finished — or, with no end time, once
 * its day is over — so a morning workshop still shows as "today" all day.
 *
 * @param int|WP_Post|null $post Event.
 * @return array|null
 */
function rise_and_flour_event( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || 'rf_event' !== $post->post_type ) {
		return null;
	}

	$tz  = wp_timezone();
	$now = new DateTimeImmutable( 'now', $tz );

	$parse = function ( $value ) use ( $tz ) {
		$value = (string) $value;
		if ( ! $value ) {
			return null;
		}
		$date = date_create_immutable( $value, $tz );
		return $date ? $date : null;
	};

	$start = $parse( function_exists( 'get_field' ) ? get_field( 'start', $post->ID ) : get_post_meta( $post->ID, 'start', true ) );
	$end   = $parse( function_exists( 'get_field' ) ? get_field( 'end', $post->ID ) : get_post_meta( $post->ID, 'end', true ) );

	if ( $start && $end && $end < $start ) {
		$end = null;
	}

	$reference = $end ? $end : ( $start ? $start->setTime( 23, 59, 59 ) : null );
	$is_past   = $reference ? $reference < $now : false;

	// "10am", "10:30am" — the brand's casual register, not 10:00 AM.
	$clock = function ( DateTimeImmutable $d ) {
		return '00' === $d->format( 'i' ) ? $d->format( 'ga' ) : $d->format( 'g:ia' );
	};

	$date       = '';
	$date_short = '';
	$time       = '';
	if ( $start ) {
		$same_year = $start->format( 'Y' ) === $now->format( 'Y' );
		$multi_day = $end && $end->format( 'Y-m-d' ) !== $start->format( 'Y-m-d' );

		if ( $multi_day ) {
			// "12 – 14 September" / "30 September – 2 October 2027".
			$same_month = $start->format( 'm' ) === $end->format( 'm' );
			$date       = wp_date( $same_month ? 'j' : 'j F', $start->getTimestamp(), $tz )
				. ' – ' . wp_date( $same_year ? 'j F' : 'j F Y', $end->getTimestamp(), $tz );
			$date_short = wp_date( $same_month ? 'j' : 'j M', $start->getTimestamp(), $tz )
				. ' – ' . wp_date( $same_year ? 'j M' : 'j M Y', $end->getTimestamp(), $tz );
		} else {
			$date       = wp_date( $same_year ? 'l j F' : 'l j F Y', $start->getTimestamp(), $tz );
			$date_short = wp_date( $same_year ? 'D j M' : 'D j M Y', $start->getTimestamp(), $tz );
			$time       = $clock( $start ) . ( $end ? ' – ' . $clock( $end ) : '' );
		}
	}

	$field = function ( $name ) use ( $post ) {
		$value = function_exists( 'get_field' ) ? get_field( $name, $post->ID ) : get_post_meta( $post->ID, $name, true );
		return trim( (string) $value );
	};

	return array(
		'start'        => $start,
		'end'          => $end,
		'is_past'      => $is_past,
		'date'         => $date,
		'date_short'   => $date_short,
		'time'         => $time,
		'day'          => $start ? $start->format( 'j' ) : '',
		'month'        => $start ? wp_date( 'M', $start->getTimestamp(), $tz ) : '',
		'iso'          => $start ? $start->format( 'c' ) : '',
		'location'     => $field( 'location' ),
		'price'        => $field( 'price' ),
		'capacity'     => $field( 'capacity' ),
		'eyebrow'      => $field( 'eyebrow' ),
		'subtitle'     => $field( 'subtitle' ),
		'panel_label'  => $field( 'panel_label' ),
		'panel_note'   => $field( 'panel_note' ),
		'courses'      => rise_and_flour_event_courses( $post->ID ),
		'booking_url'  => $field( 'booking_url' ),
		'booking_text' => $field( 'booking_text' ),
		'booking_note' => $field( 'booking_note' ),
	);
}

/**
 * The "On the night" lines, as plain strings.
 *
 * @param int $post_id Event ID.
 * @return string[]
 */
function rise_and_flour_event_courses( $post_id ) {
	$rows = function_exists( 'get_field' ) ? get_field( 'courses', $post_id ) : null;
	if ( ! is_array( $rows ) ) {
		return array();
	}
	$out = array();
	foreach ( $rows as $row ) {
		$text = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
		if ( '' !== $text ) {
			$out[] = $text;
		}
	}
	return $out;
}

/**
 * Events in date order.
 *
 * "Upcoming" is anything from today onwards (by start date), soonest first;
 * "past" is everything before today, most recent first.
 *
 * @param string $which 'upcoming' | 'past'.
 * @param int    $limit 0 for all.
 * @return WP_Post[]
 */
function rise_and_flour_events( $which = 'upcoming', $limit = 0 ) {
	$today = ( new DateTimeImmutable( 'now', wp_timezone() ) )->format( 'Y-m-d 00:00:00' );
	$past  = 'past' === $which;

	return get_posts(
		array(
			'post_type'      => 'rf_event',
			'post_status'    => 'publish',
			'posts_per_page' => $limit > 0 ? (int) $limit : -1,
			'meta_key'       => 'start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_type'      => 'DATETIME',
			'orderby'        => 'meta_value',
			'order'          => $past ? 'DESC' : 'ASC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'start',
					'value'   => $today,
					'compare' => $past ? '<' : '>=',
					'type'    => 'DATETIME',
				),
			),
		)
	);
}

/**
 * The page that lists events — wherever the Events block lives.
 *
 * @return string URL, or '' if no page has the block.
 */
function rise_and_flour_events_page_url() {
	$cached = get_transient( 'rf_events_page_url' );
	if ( false !== $cached ) {
		return $cached;
	}

	$url   = '';
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			's'              => 'wp:acf/rf-events',
		)
	);
	foreach ( $pages as $page ) {
		if ( has_block( 'acf/rf-events', $page ) ) {
			$url = get_permalink( $page );
			break;
		}
	}

	set_transient( 'rf_events_page_url', $url, HOUR_IN_SECONDS );
	return $url;
}

/**
 * Forget the cached events-page URL when any page is saved.
 */
function rise_and_flour_events_page_url_flush( $post_id, $post ) {
	if ( 'page' === $post->post_type ) {
		delete_transient( 'rf_events_page_url' );
	}
}
add_action( 'save_post', 'rise_and_flour_events_page_url_flush', 10, 2 );

/**
 * Extra admin columns: when and where, plus sorting by date.
 *
 * @param array $columns Columns.
 * @return array
 */
function rise_and_flour_event_columns( $columns ) {
	$out = array();
	foreach ( $columns as $key => $label ) {
		$out[ $key ] = $label;
		if ( 'title' === $key ) {
			$out['rf_start']    = __( 'When', 'rise-and-flour' );
			$out['rf_location'] = __( 'Where', 'rise-and-flour' );
		}
	}
	return $out;
}
add_filter( 'manage_rf_event_posts_columns', 'rise_and_flour_event_columns' );

/**
 * Render those columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function rise_and_flour_event_column( $column, $post_id ) {
	$event = rise_and_flour_event( $post_id );
	if ( ! $event ) {
		return;
	}
	if ( 'rf_start' === $column ) {
		echo esc_html( trim( $event['date'] . ( $event['time'] ? ', ' . $event['time'] : '' ) ) );
		if ( $event['is_past'] ) {
			echo ' <span style="color:#888">— ' . esc_html__( 'past', 'rise-and-flour' ) . '</span>';
		}
	} elseif ( 'rf_location' === $column ) {
		echo esc_html( $event['location'] ? $event['location'] : __( 'The café', 'rise-and-flour' ) );
	}
}
add_action( 'manage_rf_event_posts_custom_column', 'rise_and_flour_event_column', 10, 2 );

/**
 * Make the "When" column sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function rise_and_flour_event_sortable( $columns ) {
	$columns['rf_start'] = 'rf_start';
	return $columns;
}
add_filter( 'manage_edit-rf_event_sortable_columns', 'rise_and_flour_event_sortable' );

/**
 * Sort the admin list by start date when asked, and by default.
 *
 * @param WP_Query $query Admin query.
 */
function rise_and_flour_event_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'rf_event' !== $query->get( 'post_type' ) ) {
		return;
	}
	$orderby = $query->get( 'orderby' );
	if ( 'rf_start' === $orderby || ! $orderby ) {
		$query->set( 'meta_key', 'start' );
		$query->set( 'meta_type', 'DATETIME' );
		$query->set( 'orderby', 'meta_value' );
		if ( ! $query->get( 'order' ) || ! $orderby ) {
			$query->set( 'order', 'DESC' );
		}
	}
}
add_action( 'pre_get_posts', 'rise_and_flour_event_admin_order' );
