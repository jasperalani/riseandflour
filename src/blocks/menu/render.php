<?php
/**
 * ACF block render: Menu.
 *
 * @package Rise_&_Flour
 *
 * @var array $block      The block settings and attributes.
 * @var bool  $is_preview True when rendering inside the editor.
 */

$rf_sections = array();

if ( have_rows( 'sections' ) ) {
	while ( have_rows( 'sections' ) ) {
		the_row();

		$rf_items = array();

		if ( have_rows( 'items' ) ) {
			while ( have_rows( 'items' ) ) {
				the_row();
				$rf_items[] = array(
					'name'        => get_sub_field( 'name' ),
					'price'       => get_sub_field( 'price' ),
					'description' => get_sub_field( 'description' ),
					'vegetarian'  => (bool) get_sub_field( 'vegetarian' ),
					'extras'      => get_sub_field( 'extras' ),
					'note'        => get_sub_field( 'note' ),
				);
			}
		}

		$rf_sections[] = array(
			'title' => get_sub_field( 'title' ),
			'note'  => get_sub_field( 'note' ),
			'items' => $rf_items,
		);
	}
}

// A menu with nothing on it renders nothing, which looks broken in the
// editor. Show a placeholder dish there so the block stays visible.
if ( empty( $rf_sections ) && ! empty( $is_preview ) ) {
	$rf_sections = array(
		array(
			'title' => __( 'Served all day', 'rise-and-flour' ),
			'items' => array(
				array(
					'name'        => __( 'Add dishes', 'rise-and-flour' ),
					'price'       => '0.00',
					'description' => __( 'Each section holds a list of dishes with a price and description.', 'rise-and-flour' ),
				),
			),
		),
	);
}

$rf_file = get_field( 'file' );

get_template_part(
	'template-parts/blocks/menu',
	null,
	array(
		'eyebrow'       => get_field( 'eyebrow' ),
		'heading'       => get_field( 'heading' ),
		'tag'           => get_field( 'tag' ) ? get_field( 'tag' ) : 'h1',
		'intro'         => get_field( 'intro' ),
		'sections'      => $rf_sections,
		'hours_heading' => get_field( 'hours_heading' ),
		'hours'         => get_field( 'hours' ),
		'notes'         => get_field( 'notes' ),
		'file_url'      => is_array( $rf_file ) && ! empty( $rf_file['url'] ) ? $rf_file['url'] : '',
		'classes'       => rise_and_flour_block_classes( $block ),
		'anchor'        => rise_and_flour_block_anchor( $block ),
	)
);
