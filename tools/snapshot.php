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

// The Site Details options page (footer address, hours, contact…). ACF
// stores each field twice: options_<name> holds the value, _options_<name>
// the field key — both are needed for get_field() to format the value.
global $wpdb;

$rf_rows = $wpdb->get_results(
	"SELECT option_name, option_value FROM {$wpdb->options}
	 WHERE option_name LIKE 'options\_footer\_%'
	    OR option_name LIKE '\_options\_footer\_%'"
);

$rf_out['options'] = array();
foreach ( $rf_rows as $rf_row ) {
	$rf_out['options'][ $rf_row->option_name ] = $rf_row->option_value;
}

// Unescaped unicode keeps é and — literal; see the note in seed.php.
echo wp_json_encode( $rf_out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "\n";
