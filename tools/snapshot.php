<?php
/**
 * Content snapshot — dumps every page plus the site options seed.php needs
 * to stdout as JSON. Run it after editing content so a wiped database can
 * be restored:
 *
 *   docker compose --profile cli run --rm -T wpcli wp eval-file /tools/snapshot.php > tools/content-snapshot.json
 *
 * Attachment files and IDs are NOT captured — after a wipe, blocks render
 * their grey placeholders and photography is re-added in the editor.
 *
 * @package Rise_&_Flour
 */

$rf_pages = get_posts(
	array(
		'post_type'   => 'page',
		'post_status' => array( 'publish', 'draft' ),
		'numberposts' => -1,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)
);

$rf_front_id = (int) get_option( 'page_on_front' );

$rf_out = array(
	'saved' => gmdate( 'c' ),
	'front' => '',
	'pages' => array(),
);

foreach ( $rf_pages as $rf_page ) {
	$rf_out['pages'][] = array(
		'slug'       => $rf_page->post_name,
		'title'      => $rf_page->post_title,
		'status'     => $rf_page->post_status,
		'menu_order' => $rf_page->menu_order,
		'content'    => $rf_page->post_content,
	);

	if ( $rf_page->ID === $rf_front_id ) {
		$rf_out['front'] = $rf_page->post_name;
	}
}

// Events: the rf_event posts with their ACF meta. Each ACF value is stored
// alongside a _<name> key holding the field key; both are captured so the
// restore round-trips. The courses repeater is `courses` (count) plus
// `courses_N_text` rows, so keys are matched by prefix. Featured images are
// attachment IDs and are skipped.
$rf_event_meta = '/^_?(start|end|location|price|capacity|eyebrow|subtitle|panel_label|panel_note|booking_url|booking_text|booking_note|courses(_\d+_text)?)$/';

$rf_events = get_posts(
	array(
		'post_type'   => 'rf_event',
		'post_status' => array( 'publish', 'draft' ),
		'numberposts' => -1,
		'orderby'     => 'date',
		'order'       => 'ASC',
	)
);

$rf_out['events'] = array();
foreach ( $rf_events as $rf_event ) {
	$rf_meta = array();
	foreach ( get_post_meta( $rf_event->ID ) as $rf_key => $rf_values ) {
		if ( preg_match( $rf_event_meta, $rf_key ) ) {
			$rf_meta[ $rf_key ] = (string) $rf_values[0];
		}
	}
	ksort( $rf_meta );
	$rf_out['events'][] = array(
		'slug'    => $rf_event->post_name,
		'title'   => $rf_event->post_title,
		'status'  => $rf_event->post_status,
		'content' => $rf_event->post_content,
		'excerpt' => $rf_event->post_excerpt,
		'meta'    => $rf_meta,
	);
}

// The Site Details options page (footer address, hours, contact, legal
// pages…). ACF stores each field twice: options_<name> holds the value,
// _options_<name> the field key — both are needed for get_field() to format
// the value. Note options_terms_page is a page ID, which won't survive a
// database wipe — footer.php falls back to the /terms-of-use/ slug.
global $wpdb;

$rf_rows = $wpdb->get_results(
	"SELECT option_name, option_value FROM {$wpdb->options}
	 WHERE option_name LIKE 'options\_%'
	    OR option_name LIKE '\_options\_%'"
);

$rf_out['options'] = array();
foreach ( $rf_rows as $rf_row ) {
	$rf_out['options'][ $rf_row->option_name ] = $rf_row->option_value;
}

// Unescaped unicode keeps é and — literal; see the note in seed.php.
echo wp_json_encode( $rf_out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "\n";
