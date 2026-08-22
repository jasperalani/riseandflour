<?php
/**
 * Site Details options page.
 *
 * Global, non-page content — the footer's address, hours and contact lines —
 * editable under "Site Details" in wp-admin rather than hardcoded in
 * footer.php. Registered in code like the block field groups, so it appears
 * on a fresh install with no import step.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a Site Details option with a fallback.
 *
 * Safe to call even if ACF is deactivated — the fallback keeps the front
 * end rendering the design's original values.
 *
 * @param string $name    Field name.
 * @param string $default Value used when unset or ACF is absent.
 * @return string
 */
function rise_and_flour_option( $name, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name, 'option' ) : null;
	return $value ? $value : $default;
}

/**
 * Register the options page and its fields.
 */
function rise_and_flour_register_site_options() {

	if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Details', 'rise-and-flour' ),
			'menu_title' => __( 'Site Details', 'rise-and-flour' ),
			'menu_slug'  => 'rf-site-details',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-store',
			'position'   => 60,
			'redirect'   => false,
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_site_details',
			'title'    => __( 'Footer', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_opt_address',
					'label'         => __( 'Address', 'rise-and-flour' ),
					'name'          => 'footer_address',
					'type'          => 'textarea',
					'rows'          => 2,
					'new_lines'     => 'br',
					'instructions'  => __( 'The "Find us" column. Line breaks are kept.', 'rise-and-flour' ),
					'default_value' => "14 Market Place\nCirencester GL7 2NW",
				),
				array(
					'key'           => 'field_rf_opt_hours',
					'label'         => __( 'Hours', 'rise-and-flour' ),
					'name'          => 'footer_hours',
					'type'          => 'textarea',
					'rows'          => 2,
					'new_lines'     => 'br',
					'instructions'  => __( 'One range per line, e.g. "Tue – Sat, 8am – 4pm".', 'rise-and-flour' ),
					'default_value' => "Tue – Sat, 8am – 4pm\nSun, 9am – 2pm",
				),
				array(
					'key'           => 'field_rf_opt_email',
					'label'         => __( 'Email', 'rise-and-flour' ),
					'name'          => 'footer_email',
					'type'          => 'email',
					'default_value' => 'hello@riseandflour.co.uk',
				),
				array(
					'key'           => 'field_rf_opt_phone',
					'label'         => __( 'Phone', 'rise-and-flour' ),
					'name'          => 'footer_phone',
					'type'          => 'text',
					'default_value' => '01285 000 000',
				),
				array(
					'key'           => 'field_rf_opt_est_line',
					'label'         => __( 'Baseline — front page', 'rise-and-flour' ),
					'name'          => 'footer_est_line',
					'type'          => 'text',
					'instructions'  => __( 'Sits beside the wordmark at the very bottom of the front page.', 'rise-and-flour' ),
					'default_value' => '· Est. Cirencester ·',
				),
				array(
					'key'           => 'field_rf_opt_slim_line',
					'label'         => __( 'Baseline — other pages', 'rise-and-flour' ),
					'name'          => 'footer_slim_line',
					'type'          => 'text',
					'instructions'  => __( 'The slim footer used everywhere except the front page.', 'rise-and-flour' ),
					'default_value' => '14 Market Place · Cirencester',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'rf-site-details',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'rise_and_flour_register_site_options' );
