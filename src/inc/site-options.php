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
					'instructions'  => __( 'Legacy free text — only used when the Hours tab has no rows. Prefer the Hours tab.', 'rise-and-flour' ),
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

				array(
					'key'   => 'field_rf_opt_tab_hours',
					'label' => __( 'Hours', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_rf_opt_hours_groups',
					'label'        => __( 'Opening hours', 'rise-and-flour' ),
					'name'         => 'hours_groups',
					'type'         => 'repeater',
					'instructions' => __( 'The one place hours are kept. The first group is the café’s main hours — it feeds the footer, the Location and Contact pages, the Opening hours block, the "Open now" line and Google. Further groups (e.g. "Cooked food served") show on the Menu page. With no rows here, the old free-text Hours field below is used instead.', 'rise-and-flour' ),
					'layout'       => 'block',
					'button_label' => __( 'Add group', 'rise-and-flour' ),
					'min'          => 0,
					'sub_fields'   => array(
						array(
							'key'          => 'field_rf_opt_hours_group_label',
							'label'        => __( 'Group label', 'rise-and-flour' ),
							'name'         => 'label',
							'type'         => 'text',
							'instructions' => __( 'e.g. "Café open" or "Cooked food served". Shown on the Menu page when there is more than one group.', 'rise-and-flour' ),
						),
						array(
							'key'          => 'field_rf_opt_hours_rows',
							'label'        => __( 'Times', 'rise-and-flour' ),
							'name'         => 'rows',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => __( 'Add days', 'rise-and-flour' ),
							'min'          => 0,
							'sub_fields'   => array(
								array(
									'key'           => 'field_rf_opt_hours_day_from',
									'label'         => __( 'From', 'rise-and-flour' ),
									'name'          => 'day_from',
									'type'          => 'select',
									'choices'       => array(
										1 => __( 'Monday', 'rise-and-flour' ),
										2 => __( 'Tuesday', 'rise-and-flour' ),
										3 => __( 'Wednesday', 'rise-and-flour' ),
										4 => __( 'Thursday', 'rise-and-flour' ),
										5 => __( 'Friday', 'rise-and-flour' ),
										6 => __( 'Saturday', 'rise-and-flour' ),
										7 => __( 'Sunday', 'rise-and-flour' ),
									),
									'default_value' => 1,
									'return_format' => 'value',
									'wrapper'       => array( 'width' => '18' ),
								),
								array(
									'key'           => 'field_rf_opt_hours_day_to',
									'label'         => __( 'To', 'rise-and-flour' ),
									'name'          => 'day_to',
									'type'          => 'select',
									'instructions'  => __( 'Same as From for a single day.', 'rise-and-flour' ),
									'choices'       => array(
										1 => __( 'Monday', 'rise-and-flour' ),
										2 => __( 'Tuesday', 'rise-and-flour' ),
										3 => __( 'Wednesday', 'rise-and-flour' ),
										4 => __( 'Thursday', 'rise-and-flour' ),
										5 => __( 'Friday', 'rise-and-flour' ),
										6 => __( 'Saturday', 'rise-and-flour' ),
										7 => __( 'Sunday', 'rise-and-flour' ),
									),
									'default_value' => 5,
									'return_format' => 'value',
									'wrapper'       => array( 'width' => '18' ),
								),
								array(
									'key'           => 'field_rf_opt_hours_closed',
									'label'         => __( 'Closed', 'rise-and-flour' ),
									'name'          => 'closed',
									'type'          => 'true_false',
									'ui'            => 1,
									'default_value' => 0,
									'wrapper'       => array( 'width' => '12' ),
								),
								array(
									'key'               => 'field_rf_opt_hours_opens',
									'label'             => __( 'Opens', 'rise-and-flour' ),
									'name'              => 'opens',
									'type'              => 'time_picker',
									'display_format'    => 'g:i a',
									'return_format'     => 'H:i',
									'wrapper'           => array( 'width' => '16' ),
									'conditional_logic' => array( array( array( 'field' => 'field_rf_opt_hours_closed', 'operator' => '!=', 'value' => '1' ) ) ),
								),
								array(
									'key'               => 'field_rf_opt_hours_closes',
									'label'             => __( 'Closes', 'rise-and-flour' ),
									'name'              => 'closes',
									'type'              => 'time_picker',
									'display_format'    => 'g:i a',
									'return_format'     => 'H:i',
									'wrapper'           => array( 'width' => '16' ),
									'conditional_logic' => array( array( array( 'field' => 'field_rf_opt_hours_closed', 'operator' => '!=', 'value' => '1' ) ) ),
								),
								array(
									'key'          => 'field_rf_opt_hours_note',
									'label'        => __( 'Note', 'rise-and-flour' ),
									'name'         => 'note',
									'type'         => 'text',
									'instructions' => __( 'Optional, shown in brackets.', 'rise-and-flour' ),
									'wrapper'      => array( 'width' => '20' ),
								),
							),
						),
					),
				),
				array(
					'key'          => 'field_rf_opt_hours_exceptions',
					'label'        => __( 'Exceptions', 'rise-and-flour' ),
					'name'         => 'hours_exceptions',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => '',
					'instructions' => __( 'Free lines shown after the hours wherever they appear, e.g. "Closed 24–27 December".', 'rise-and-flour' ),
				),

				array(
					'key'   => 'field_rf_opt_tab_legal',
					'label' => __( 'Legal pages', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_rf_opt_terms_page',
					'label'         => __( 'Terms of Use page', 'rise-and-flour' ),
					'name'          => 'terms_page',
					'type'          => 'post_object',
					'post_type'     => array( 'page' ),
					'post_status'   => array( 'publish', 'draft', 'pending', 'private' ),
					'return_format' => 'id',
					'allow_null'    => 1,
					'ui'            => 1,
					'instructions'  => __( 'Linked from the footer beside the Privacy Policy, once the page is published. The Privacy Policy page itself is chosen under Settings → Privacy.', 'rise-and-flour' ),
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
