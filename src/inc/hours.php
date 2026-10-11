<?php
/**
 * Opening hours.
 *
 * The hours live once, as structured rows on Site Details (Hours tab):
 * groups ("Café open", "Cooked food served") of rows, each a day range with
 * an opening and closing time — or marked closed. Everything that shows
 * hours reads from here, so the footer, Location page, Menu panel, Contact
 * details and the Opening hours block never disagree, and the formatting
 * ("Monday – Friday, 8:30am – 3pm") is generated, not typed.
 *
 * Because the times are real data the theme can also emit schema.org
 * openingHoursSpecification for Google.
 *
 * Sites that predate this keep working: with no rows defined, the legacy
 * free-text hours field is used as before.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Day names, ISO order (1 = Monday).
 *
 * @return array
 */
function rise_and_flour_day_names() {
	return array(
		1 => __( 'Monday', 'rise-and-flour' ),
		2 => __( 'Tuesday', 'rise-and-flour' ),
		3 => __( 'Wednesday', 'rise-and-flour' ),
		4 => __( 'Thursday', 'rise-and-flour' ),
		5 => __( 'Friday', 'rise-and-flour' ),
		6 => __( 'Saturday', 'rise-and-flour' ),
		7 => __( 'Sunday', 'rise-and-flour' ),
	);
}

/**
 * "8:30am" / "3pm" from an H:i string — the brand's casual clock.
 *
 * @param string $time H:i.
 * @return string
 */
function rise_and_flour_clock( $time ) {
	if ( ! preg_match( '/^(\d{1,2}):(\d{2})/', (string) $time, $m ) ) {
		return (string) $time;
	}
	$h    = (int) $m[1];
	$i    = $m[2];
	$ampm = $h >= 12 ? 'pm' : 'am';
	$h12  = $h % 12 ? $h % 12 : 12;
	return $h12 . ( '00' === $i ? '' : ':' . $i ) . $ampm;
}

/**
 * The structured hours, normalised.
 *
 * @return array[] [ [ label, rows: [ [ days: int[], from, to, opens, closes, closed, note ] ] ] ]
 */
function rise_and_flour_hours_groups() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$groups = function_exists( 'get_field' ) ? get_field( 'hours_groups', 'option' ) : null;
	$out    = array();

	foreach ( (array) $groups as $group ) {
		if ( ! is_array( $group ) ) {
			continue;
		}
		$rows = array();
		foreach ( (array) ( $group['rows'] ?? array() ) as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$from = (int) ( $row['day_from'] ?? 0 );
			$to   = (int) ( $row['day_to'] ?? 0 );
			if ( $from < 1 || $from > 7 ) {
				continue;
			}
			if ( $to < 1 || $to > 7 ) {
				$to = $from;
			}
			// Walk from → to, wrapping past Sunday (Fri – Mon is 5,6,7,1).
			$days = array();
			$d    = $from;
			do {
				$days[] = $d;
				if ( $d === $to ) {
					break;
				}
				$d = ( $d % 7 ) + 1;
			} while ( count( $days ) < 7 );

			$closed = ! empty( $row['closed'] );
			$opens  = $closed ? '' : substr( (string) ( $row['opens'] ?? '' ), 0, 5 );
			$closes = $closed ? '' : substr( (string) ( $row['closes'] ?? '' ), 0, 5 );
			if ( ! $closed && ( ! $opens || ! $closes ) ) {
				continue;
			}

			$rows[] = array(
				'days'   => $days,
				'from'   => $from,
				'to'     => $to,
				'opens'  => $opens,
				'closes' => $closes,
				'closed' => $closed,
				'note'   => trim( (string) ( $row['note'] ?? '' ) ),
			);
		}
		if ( $rows ) {
			$out[] = array(
				'label' => trim( (string) ( $group['label'] ?? '' ) ),
				'rows'  => $rows,
			);
		}
	}

	$cache = $out;
	return $cache;
}

/**
 * Free-text exception lines ("Closed 24–27 December"), one per entry.
 *
 * @return string[]
 */
function rise_and_flour_hours_exceptions() {
	$raw = (string) rise_and_flour_option( 'hours_exceptions', '' );
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $raw ) ) ) );
}

/**
 * One row as a sentence: "Monday – Friday, 8:30am – 3pm".
 *
 * @param array $row Normalised row.
 * @return string
 */
function rise_and_flour_hours_line( $row ) {
	$names = rise_and_flour_day_names();
	$days  = $row['from'] === $row['to']
		? $names[ $row['from'] ]
		: $names[ $row['from'] ] . ' – ' . $names[ $row['to'] ];

	if ( $row['closed'] ) {
		$line = sprintf( '%s, %s', $days, __( 'closed', 'rise-and-flour' ) );
	} else {
		$line = sprintf( '%s, %s – %s', $days, rise_and_flour_clock( $row['opens'] ), rise_and_flour_clock( $row['closes'] ) );
	}
	if ( $row['note'] ) {
		$line .= ' (' . $row['note'] . ')';
	}
	return $line;
}

/**
 * The day part of a row: "Mon – Fri" for a range, "Saturday" for one day
 * (or "Sat" when $short_single is set).
 *
 * @param array $row          Normalised row.
 * @param bool  $short_single Short form for single days too.
 * @return string
 */
function rise_and_flour_hours_day_label( $row, $short_single = false ) {
	$full  = rise_and_flour_day_names();
	$short = array( 1 => 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
	if ( $row['from'] === $row['to'] ) {
		return $short_single ? $short[ $row['from'] ] : $full[ $row['from'] ];
	}
	return $short[ $row['from'] ] . ' – ' . $short[ $row['to'] ];
}

/**
 * A group as [days, time] pairs for tabular layouts — "Mon – Fri" /
 * "8:30am – 3pm". Falls back to splitting the legacy text on its first
 * comma. Exceptions are not included; they're prose, not rows.
 *
 * @param int  $group        Group index.
 * @param bool $short_single Short form for single days.
 * @return array[] [ [ 'days' => string, 'time' => string ] ]
 */
function rise_and_flour_hours_pairs( $group = 0, $short_single = false ) {
	$groups = rise_and_flour_hours_groups();
	$pairs  = array();

	if ( isset( $groups[ $group ] ) ) {
		foreach ( $groups[ $group ]['rows'] as $row ) {
			$time = $row['closed']
				? __( 'Closed', 'rise-and-flour' )
				: rise_and_flour_clock( $row['opens'] ) . ' – ' . rise_and_flour_clock( $row['closes'] );
			if ( $row['note'] ) {
				$time .= ' (' . $row['note'] . ')';
			}
			$pairs[] = array(
				'days' => rise_and_flour_hours_day_label( $row, $short_single ),
				'time' => $time,
			);
		}
		return $pairs;
	}

	if ( 0 === $group ) {
		$legacy = (string) rise_and_flour_option( 'footer_hours', '' );
		foreach ( preg_split( '/\s*<br\s*\/?>\s*|\r\n|\r|\n/i', $legacy ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			$bits    = explode( ',', $line, 2 );
			$pairs[] = array(
				'days' => trim( $bits[0] ),
				'time' => isset( $bits[1] ) ? trim( $bits[1] ) : '',
			);
		}
	}
	return $pairs;
}

/**
 * An extra hours group as one sentence, for the Framed and Band styles:
 * "Cooked food served until 2:30pm, Sundays 2pm." The first row gives the
 * closing time; later rows are listed by day. Opening times are left out —
 * the group reads against the main hours above it.
 *
 * @param int $group Group index (1 upwards; 0 is the main hours).
 * @return string Empty if the group doesn't exist.
 */
function rise_and_flour_hours_group_sentence( $group ) {
	$groups = rise_and_flour_hours_groups();
	if ( ! isset( $groups[ $group ] ) ) {
		return '';
	}
	$full  = rise_and_flour_day_names();
	$label = $groups[ $group ]['label'] ? $groups[ $group ]['label'] : __( 'Kitchen open', 'rise-and-flour' );
	$parts = array();

	foreach ( $groups[ $group ]['rows'] as $i => $row ) {
		if ( 0 === $i ) {
			$part = $row['closed']
				? __( 'closed', 'rise-and-flour' )
				/* translators: %s: closing time, e.g. 2:30pm */
				: sprintf( __( 'until %s', 'rise-and-flour' ), rise_and_flour_clock( $row['closes'] ) );
		} else {
			$days = $row['from'] === $row['to']
				/* translators: %s: day name; the plural, as in "Sundays 2pm" */
				? sprintf( __( '%ss', 'rise-and-flour' ), $full[ $row['from'] ] )
				: rise_and_flour_hours_day_label( $row );
			$part = $row['closed']
				/* translators: %s: day(s), e.g. "Sundays" or "Sat – Sun" */
				? sprintf( __( 'closed %s', 'rise-and-flour' ), $days )
				: $days . ' ' . rise_and_flour_clock( $row['closes'] );
		}
		if ( $row['note'] ) {
			$part .= ' (' . $row['note'] . ')';
		}
		$parts[] = $part;
	}

	return $label . ' ' . implode( ', ', $parts ) . '.';
}

/**
 * Sentences for every group after the main one.
 *
 * @return string[]
 */
function rise_and_flour_hours_extra_sentences() {
	$out = array();
	foreach ( array_keys( rise_and_flour_hours_groups() ) as $i ) {
		if ( $i > 0 ) {
			$out[] = rise_and_flour_hours_group_sentence( $i );
		}
	}
	return $out;
}

/**
 * The lines of one group, with any exceptions appended to the main one.
 *
 * @param int $group Group index; 0 is the main one the footer shows.
 * @return string[]
 */
function rise_and_flour_hours_lines( $group = 0 ) {
	$groups = rise_and_flour_hours_groups();
	if ( ! isset( $groups[ $group ] ) ) {
		return array();
	}
	$lines = array_map( 'rise_and_flour_hours_line', $groups[ $group ]['rows'] );
	if ( 0 === $group ) {
		$lines = array_merge( $lines, rise_and_flour_hours_exceptions() );
	}
	return $lines;
}

/**
 * The main hours as the <br>-separated text the templates always used —
 * generated from the rows, or the legacy free-text field if none exist.
 *
 * @return string
 */
function rise_and_flour_hours_value() {
	$lines = rise_and_flour_hours_lines( 0 );
	if ( $lines ) {
		return implode( '<br>', array_map( 'esc_html', $lines ) );
	}
	return (string) rise_and_flour_option( 'footer_hours', '' );
}

/**
 * Every group, in the Menu block's convention: a <strong> label line, one
 * range per line, a blank line between groups.
 *
 * @return string
 */
function rise_and_flour_hours_all_text() {
	$groups = rise_and_flour_hours_groups();
	if ( ! $groups ) {
		$legacy = (string) rise_and_flour_option( 'footer_hours', '' );
		return preg_replace( '/<br\s*\/?>\s*/i', "\n", $legacy );
	}
	$blocks = array();
	foreach ( $groups as $i => $group ) {
		$lines = array_map( 'rise_and_flour_hours_line', $group['rows'] );
		if ( 0 === $i ) {
			$lines = array_merge( $lines, rise_and_flour_hours_exceptions() );
		}
		if ( $group['label'] && count( $groups ) > 1 ) {
			array_unshift( $lines, '<strong>' . esc_html( $group['label'] ) . '</strong>' );
		}
		$blocks[] = implode( "\n", $lines );
	}
	return implode( "\n\n", $blocks );
}

/**
 * schema.org LocalBusiness (CafeOrCoffeeShop) with openingHoursSpecification,
 * printed in <head> on every page so Google can show hours in search and
 * Maps. Address lines come from Site Details; the postcode is picked out of
 * the last line when it looks like one.
 */
function rise_and_flour_hours_schema() {
	$groups = rise_and_flour_hours_groups();

	$address_raw = (string) rise_and_flour_option( 'footer_address', '' );
	$lines       = array_values( array_filter( array_map( 'trim', preg_split( '/\s*<br\s*\/?>\s*|\r\n|\r|\n/i', $address_raw ) ) ) );

	$address = array(
		'@type'          => 'PostalAddress',
		'addressCountry' => 'GB',
	);
	if ( $lines ) {
		$address['streetAddress'] = $lines[0];
		$rest                     = implode( ', ', array_slice( $lines, 1 ) );
		if ( preg_match( '/\b([A-Z]{1,2}\d[A-Z\d]?\s*\d[A-Z]{2})\b/i', $rest, $m ) ) {
			$address['postalCode'] = strtoupper( preg_replace( '/\s+/', ' ', $m[1] ) );
			$rest                  = trim( str_replace( $m[1], '', $rest ), " ,\t" );
		}
		if ( $rest ) {
			$address['addressLocality'] = $rest;
		}
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'CafeOrCoffeeShop',
		'@id'      => home_url( '/#cafe' ),
		'name'     => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'url'      => home_url( '/' ),
		'address'  => $address,
	);

	$phone = (string) rise_and_flour_option( 'footer_phone', '' );
	$email = (string) rise_and_flour_option( 'footer_email', '' );
	if ( $phone ) {
		$data['telephone'] = $phone;
	}
	if ( $email ) {
		$data['email'] = $email;
	}

	if ( ! empty( $groups[0] ) ) {
		$names = array( 1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
		$spec  = array();
		foreach ( $groups[0]['rows'] as $row ) {
			if ( $row['closed'] ) {
				continue;
			}
			$spec[] = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array_map(
					function ( $d ) use ( $names ) {
						return $names[ $d ];
					},
					$row['days']
				),
				'opens'     => $row['opens'],
				'closes'    => $row['closes'],
			);
		}
		if ( $spec ) {
			$data['openingHoursSpecification'] = $spec;
		}
	}

	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'rise_and_flour_hours_schema', 20 );
