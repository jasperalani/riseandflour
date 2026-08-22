<?php
/**
 * Restores site content from content-snapshot.json — run with `wp eval-file`
 * after a database wipe (`docker compose down -v`), once WordPress is
 * installed and the theme + ACF are active:
 *
 *   docker compose --profile cli run --rm wpcli wp eval-file /tools/seed.php
 *
 * The snapshot holds every page's block markup plus the Site Details
 * options. Refresh it whenever content changes:
 *
 *   docker compose --profile cli run --rm -T wpcli wp eval-file /tools/snapshot.php > tools/content-snapshot.json
 *
 * Attachment IDs in the markup won't exist in a fresh database, so image
 * blocks fall back to their grey placeholders — re-add photography in the
 * editor. Safe to re-run; pages update by slug.
 *
 * @package Rise_&_Flour
 */

/**
 * Create or update a page by slug.
 *
 * @param array $page slug / title / status / menu_order / content.
 * @return int Post ID.
 */
function rf_seed_page( $page ) {
	$existing = get_page_by_path( $page['slug'] );

	$args = array(
		'post_type'    => 'page',
		'post_name'    => $page['slug'],
		'post_title'   => $page['title'],
		// wp_insert_post() unslashes its input, so anything with a literal
		// backslash must be slashed first or it silently loses characters
		// (é becomes "u00e9").
		'post_content' => wp_slash( $page['content'] ),
		'post_status'  => $page['status'],
		'menu_order'   => (int) $page['menu_order'],
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		return wp_update_post( $args );
	}

	return wp_insert_post( $args );
}

$rf_file = __DIR__ . '/content-snapshot.json';

if ( ! file_exists( $rf_file ) ) {
	WP_CLI::error( "No snapshot at {$rf_file} — run snapshot.php first (see the header of this file)." );
}

$rf_data = json_decode( file_get_contents( $rf_file ), true );

if ( ! is_array( $rf_data ) || empty( $rf_data['pages'] ) ) {
	WP_CLI::error( "Snapshot at {$rf_file} is empty or unreadable." );
}

/* =============================================================
 * Pages
 * ============================================================= */

$rf_count = 0;
foreach ( $rf_data['pages'] as $rf_page ) {
	$rf_id = rf_seed_page( $rf_page );
	if ( $rf_id && ! is_wp_error( $rf_id ) ) {
		$rf_count++;
	} else {
		WP_CLI::warning( "Could not seed '{$rf_page['slug']}'." );
	}
}

/* =============================================================
 * Events
 * ============================================================= */

$rf_event_count = 0;
foreach ( isset( $rf_data['events'] ) ? $rf_data['events'] : array() as $rf_event ) {
	$rf_existing = get_posts(
		array(
			'post_type'   => 'rf_event',
			'name'        => $rf_event['slug'],
			'post_status' => 'any',
			'numberposts' => 1,
		)
	);

	$rf_args = array(
		'post_type'    => 'rf_event',
		'post_name'    => $rf_event['slug'],
		'post_title'   => $rf_event['title'],
		'post_content' => wp_slash( $rf_event['content'] ),
		'post_excerpt' => wp_slash( $rf_event['excerpt'] ),
		'post_status'  => $rf_event['status'],
		'meta_input'   => $rf_event['meta'],
	);

	if ( $rf_existing ) {
		$rf_args['ID'] = $rf_existing[0]->ID;
		$rf_id         = wp_update_post( $rf_args );
	} else {
		$rf_id = wp_insert_post( $rf_args );
	}

	if ( $rf_id && ! is_wp_error( $rf_id ) ) {
		$rf_event_count++;
	} else {
		WP_CLI::warning( "Could not seed event '{$rf_event['slug']}'." );
	}
}

/* =============================================================
 * Front page
 * ============================================================= */

if ( ! empty( $rf_data['front'] ) ) {
	$rf_front = get_page_by_path( $rf_data['front'] );
	if ( $rf_front ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $rf_front->ID );
	}
}

/* =============================================================
 * Site Details options (footer address, hours, contact…)
 * ============================================================= */

if ( ! empty( $rf_data['options'] ) ) {
	foreach ( $rf_data['options'] as $rf_name => $rf_value ) {
		update_option( $rf_name, $rf_value );
	}
}

WP_CLI::success(
	sprintf(
		'%d pages and %d events restored from snapshot (saved %s); front page set to "%s".',
		$rf_count,
		$rf_event_count,
		isset( $rf_data['saved'] ) ? $rf_data['saved'] : '?',
		isset( $rf_data['front'] ) ? $rf_data['front'] : '—'
	)
);
