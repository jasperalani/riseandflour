<?php
/**
 * ACF block render: Location.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/location',
	null,
	array(
		'eyebrow'        => get_field( 'eyebrow' ),
		'heading'        => get_field( 'heading' ),
		'tag'            => get_field( 'tag' ) ? get_field( 'tag' ) : 'h1',
		'intro'          => get_field( 'intro' ),
		'address'        => get_field( 'address' ),
		'phone'          => get_field( 'phone' ),
		'email'          => get_field( 'email' ),
		'show_hours'     => null === get_field( 'show_hours' ) ? true : (bool) get_field( 'show_hours' ),
		'note_label'     => get_field( 'note_label' ),
		'note'           => get_field( 'note' ),
		'directions_url' => get_field( 'directions_url' ),
		'map'            => get_field( 'map' ),
		'map_position'   => get_field( 'map_position' ) ? get_field( 'map_position' ) : 'right',
		'classes'        => rise_and_flour_block_classes( $block ),
		'anchor'         => rise_and_flour_block_anchor( $block ),
	)
);
